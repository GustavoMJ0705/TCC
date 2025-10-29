<?php
require_once __DIR__ . '/../php/db_connect.php';
session_start();

// Pega id da academia via GET
$academia_id = isset($_GET['id']) && is_numeric($_GET['id']) ? intval($_GET['id']) : null;

// Restrição: somente a conta da academia proprietária pode ver esta página
// Quando a academia está logada ela deve ter a chave de sessão 'academia_id'


$matriculas = [];
$academiaInfo = null;

if ($academia_id) {
    try {
    $sql = "SELECT dt_matricula, id_aluno, nm_aluno, a.nr_telefone, ac.nm_academia
        FROM tb_matricula
                JOIN tb_aluno a ON id_aluno = a.id_aluno
                JOIN tb_academia ac ON id_academia = ac.id_academia
                WHERE id_academia = :id
                ORDER BY dt_matricula DESC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':id' => $academia_id]);
        $matriculas = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Busca informações da academia (apenas para cabeçalho)
        $stmt2 = $pdo->prepare("SELECT nm_academia, ds_cidade, ds_estado FROM tb_academia WHERE id_academia = :id");
        $stmt2->execute([':id' => $academia_id]);
        $academiaInfo = $stmt2->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log('Erro ao buscar matriculas: ' . $e->getMessage());
        $matriculas = [];
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visualizar Matrículas - MatchFight</title>
    <link rel="stylesheet" href="../css/navbar.css">
    <link rel="stylesheet" href="../css/matricula.css">
</head>
<body>
   <header>
     <nav class="navbar">
        <div class="menu-search">
            <div class="menu-icon" onclick="toggleSidebar()">
                <div class="bar"></div>
                <div class="bar"></div>
                <div class="bar"></div>
            </div>
            <a href="search.php">
                <div class="search">
                    <button class="buttonSearch hover-button">
                        <img src="../img/lupa.png" alt="Lupa" class="icon-lupa">
                        <span class="search-text">Pesquisar Dojo</span>
                    </button>
                </div>
            </a>
        </div>
            <div class="logo">
                <img src="../img/match_ofc2.0.png" alt="Logo do Match Fight, um homem chutando ao lado da escrita Match Fight" width="150rem">
            </div>
           
            <div class="cadastrar">
                <?php
               
                if (!isset($_SESSION['professor_id']) && !isset($_SESSION['aluno_id']) && !isset($_SESSION['academia_id'])): ?>
                    <a href="contas.html" id="login-link" class="lbottom">Cadastrar</a>
                <?php endif; ?>

                <?php 
                if (isset($_SESSION['academia_id'])): ?>
                    <a href="criardojo.php" class="lbottom">Criar Academia</a>
                <?php endif; ?>
            </div>

            <div class="Perfil">
                <?php
                if (isset($_SESSION['professor_id']) || isset($_SESSION['aluno_id'])): ?>
                    <a href="mperfil.php" class="lbottom_AlunoProf"><img src="../img/Perfil.png" alt=""></a>
                <?php endif; ?>
            </div>
        </nav>
        
        <aside class="sidebar" id="sidebar">
            <span class="close-btn" onclick="toggleSidebar()">&times;</span>
            <ul>                
                <li><a href="home.php">Home</a></li>      
                <li><a href="suporte_tecnico.php">Suporte técnico</a></li>
                <li><a href="seja_parceiro.php">Seja um parceiro</a></li>
                <li><a href="#">Calendário de aulas</a></li>
                <?php if (isset($_SESSION['academia_id']) || isset($_SESSION['professor_id']) || isset($_SESSION['aluno_id'])): ?>
                    <li><a href="../php/logout.php" id="logout-link">Sair</a></li>
               
                
                <?php endif; ?>
            </ul>
        </aside>
    </header>

    <!-- Títulos de seção com classes para posicionamento -->
    <section class="page-headings">
        <h3 class="alunos-cadastrados">Alunos Cadastrados</h3>

        <?php if ($academiaInfo): ?>
            <div class="academia-info">
                <strong><?php echo htmlspecialchars($academiaInfo['nm_academia']); ?></strong>
                <span><?php echo htmlspecialchars($academiaInfo['ds_cidade'] . ' - ' . $academiaInfo['ds_estado']); ?></span>
            </div>
        <?php endif; ?>

        <div class="pedidos">
            <h4 class="pedidos-pendentes">Pedidos Pendentes:</h4>
        </div>
    </section>

    <main class="matriculas-list">
        <?php if (empty($matriculas)): ?>
            <p>Nenhuma matrícula encontrada para esta academia.</p>
        <?php else: ?>
            <table class="matriculas-table">
                <thead>
                    <tr>
                        <th>Aluno</th>
                        <th>E-mail</th>
                        <th>Telefone</th>
                        <th>Data da Matrícula</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($matriculas as $m): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($m['nm_aluno']); ?></td>
                            <td><?php echo htmlspecialchars($m['nr_telefone']); ?></td>
                            <td><?php echo htmlspecialchars(date('d/m/Y H:i', strtotime($m['dt_matricula']))); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </main>
    <script src="../js/nav.js"></script>
</body>
</html>