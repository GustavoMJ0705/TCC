<?php
require_once __DIR__ . '/../php/db_connect.php';
session_start();

// Pega id da academia via GET ou (quando disponível) a partir da sessão
// Uso: se a página for acessada por uma academia logada, usamos a sessão
$academia_id = null;
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $academia_id = intval($_GET['id']);
} elseif (isset($_SESSION['academia_id']) && is_numeric($_SESSION['academia_id'])) {
    $academia_id = intval($_SESSION['academia_id']);
}

// Restrição: somente a conta da academia proprietária pode ver esta página
// Quando a academia está logada ela deve ter a chave de sessão 'academia_id'
$erroSenha = '';
if (isset($_GET['erro'])) {
    $erroSenha = $_GET['erro'];
}

$erroEmail = '';
if (isset($_GET['erroemail'])) {
    $erroEmail = $_GET['erroemail'];
}

try {
    $sql = "SELECT id_genero, nm_genero FROM tb_genero";
    $result = $pdo->query($sql);

    $generos = $result->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erro ao conectar ou consultar: " . $e->getMessage());
}

$matriculas = [];
$academiaInfo = null;

// Modo debug (acione com ?debug=1 na URL)
$debugMode = isset($_GET['debug']) && $_GET['debug'] == '1';

if ($academia_id) {
    try {
    $sql = "SELECT 
            m.dt_matricula,
            m.id_aluno,
            a.nm_aluno,
            a.nr_telefone,
            a.ds_email,
                pa.nm_academia,
            h.ds_horario,
            h.ds_dia_semana,
            ma.nm_modalidade,
            m.status_da_matricula
        FROM tb_matricula m
        JOIN tb_aluno a ON m.id_aluno = a.id_aluno
            JOIN tb_perfil_academia pa ON m.id_perfil_academia = pa.id_perfil_academia
            LEFT JOIN tb_matricula_aulas ma_a ON m.id_matricula = ma_a.id_matricula
            LEFT JOIN tb_aulas h ON ma_a.id_aulas = h.id_aulas
        LEFT JOIN tb_modalidade ma ON h.id_modalidade = ma.id_modalidade
            WHERE m.id_perfil_academia = :id
        ORDER BY COALESCE(ma.nm_modalidade, 'Sem modalidade'), 
                COALESCE(h.ds_dia_semana, 'Sem dia'), 
                COALESCE(h.ds_horario, 'Sem horário'), 
                a.nm_aluno";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':id' => $academia_id]);
        $matriculas = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Busca informações da academia (apenas para cabeçalho)
    $stmt2 = $pdo->prepare("SELECT nm_academia, ds_cidade, ds_estado FROM tb_perfil_academia WHERE id_perfil_academia = :id");
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

        <?php if ($debugMode): ?>
            <div class="debug-info" style="background:#ffe; border:1px solid #cc9; padding:10px; margin:10px 0;">
                <strong>Debug:</strong>
                <div>ID usado: <?php echo htmlspecialchars(var_export($academia_id, true)); ?></div>
                <div>Registros retornados: <?php echo htmlspecialchars(count($matriculas)); ?></div>
                <details style="margin-top:8px;"><summary>Mostrar primeiros resultados (até 5)</summary>
                    <pre style="white-space:pre-wrap; max-height:300px; overflow:auto;"><?php echo htmlspecialchars(var_export(array_slice($matriculas,0,5), true)); ?></pre>
                </details>
            </div>
        <?php endif; ?>

        <?php if ($academiaInfo): ?>
            <div class="academia-info">
                <strong><?php echo htmlspecialchars($academiaInfo['nm_academia']); ?></strong>
                <span><?php echo htmlspecialchars($academiaInfo['ds_cidade'] . ' - ' . $academiaInfo['ds_estado']); ?></span>
            </div>
        <?php endif; ?>

       <!-- <div class="pedidos">
            <h4 class="pedidos-pendentes">Pedidos Pendentes:</h4>
        </div> -->
    </section>

    <main class="matriculas-list">
        <?php if (empty($matriculas)): ?>
            <p>Nenhuma matrícula encontrada para esta academia.</p>
        <?php else: ?>
            <?php
            $currentModalidade = '';
            $currentHorario = '';
            
            foreach ($matriculas as $m):
                // Verifica se é uma nova modalidade
                if ($currentModalidade != $m['nm_modalidade']):
                    // Fecha a tabela anterior se não for a primeira
                    if ($currentModalidade != ''): ?>
                        </tbody>
                        </table>
                    <?php endif; 
                    
                    $currentModalidade = $m['nm_modalidade'];
                    ?>
                    <h2 class="modalidade-titulo"><?php echo htmlspecialchars($currentModalidade); ?></h2>
                <?php endif;

                // Verifica se é um novo horário
                $horarioCompleto = $m['ds_dia_semana'] . ' - ' . $m['ds_horario'];
                if ($currentHorario != $horarioCompleto):
                    // Fecha a tabela anterior se não for a primeira
                    if ($currentHorario != '' && $currentModalidade == $m['nm_modalidade']): ?>
                        </tbody>
                        </table>
                    <?php endif;
                    
                    $currentHorario = $horarioCompleto;
                    ?>
                    <h3 class="horario-titulo"><?php echo htmlspecialchars($horarioCompleto); ?></h3>
                    <table class="matriculas-table">
                        <thead>
                            <tr>
                                <th>Aluno</th>
                                <th>E-mail</th>
                                <th>Telefone</th>
                                <th>Data da Matrícula</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                <?php endif; ?>
                
                <tr>
                    <td><?php echo htmlspecialchars($m['nm_aluno']); ?></td>
                    <td><?php echo htmlspecialchars($m['ds_email']); ?></td>
                    <td><?php echo htmlspecialchars($m['nr_telefone']); ?></td>
                    <td><?php echo htmlspecialchars(date('d/m/Y H:i', strtotime($m['dt_matricula']))); ?></td>
                    <td><?php echo htmlspecialchars($m['status_da_matricula']); ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
            </table>
        <?php endif; ?>
    </main>
    <script src="../js/nav.js"></script>
</body>
</html>