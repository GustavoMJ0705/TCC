<?php
session_start();


// Dados de exemplo para visualização
$matriculas = [
    [
        'nm_aluno' => 'João Silva',
        'ds_email' => 'joao@email.com',
        'nr_telefone' => '(11) 99999-9999',
        'nm_academia' => 'Academia Power Fight',
        'ds_cidade' => 'São Paulo',
        'ds_estado' => 'SP',
        'dt_matricula' => '2025-10-23'
    ],
    [
        'nm_aluno' => 'Maria Santos',
        'ds_email' => 'maria@email.com',
        'nr_telefone' => '(11) 88888-8888',
        'nm_academia' => 'Academia Dragon',
        'ds_cidade' => 'São Paulo',
        'ds_estado' => 'SP',
        'dt_matricula' => '2025-10-22'
    ]
];
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
            <div class="menu-icon" onclick="toggleSidebar()">
                <div class="bar"></div>
                <div class="bar"></div>
                <div class="bar"></div>
            </div>
            <div class="Perfil">
               <?php if (isset($_SESSION['professor_id']) || isset($_SESSION['aluno_id'])): ?>
                    <a href="mperfil.php" class="lbottom">Meu Perfil</a>
                <?php elseif (!isset($_SESSION['academia_id']) ):    ?>
                    <a href="contas.html" id="login-link" class="lbottom">Login</a>
                <?php endif; ?>
            </div>
        </nav>
        <aside class="sidebar" id="sidebar">
            <span class="close-btn" onclick="toggleSidebar()">&times;</span>
            <ul>
                <li><a href="home.php">Pagina Inicial</a></li>
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

        <div class="pedidos">
            <h4 class="pedidos-pendentes">Pedidos Pendentes:</h4>
        </div>
    </section>
    <script src="../js/nav.js"></script>
</body>
</html>
