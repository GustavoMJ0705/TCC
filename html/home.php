<?php session_start();
error_log("Debug Home: Sessão iniciada");

if (isset($_SESSION['academia_id'])) {
    // Se for academia logada, carrega só as academias dela
    require_once 'lista_minhas_academias.php';
    $academias = getMinhasAcademias($_SESSION['academia_id']); // Vamos criar essa função
} else {
    // Se não for academia, carrega todas as academias
    require_once 'Lista_Academia.php';
    $academias = getTodasAcademias(); // Vamos criar essa função
}

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MatchFight - Academias</title>
    <link rel="stylesheet" href="../css/home.css">
    <link rel="stylesheet" href="../css/navbar.css">
    <script src="../js/nav.js"></script>
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

            <?php include __DIR__ . '/inc_profile_img.php'; ?>
        </nav>
        
        <aside class="sidebar" id="sidebar">
            <span class="close-btn" onclick="toggleSidebar()">&times;</span>
            <ul>                  
                <li><a href="suporte_tecnico.php">Suporte técnico</a></li>
                <li><a href="seja_parceiro.php">Seja um parceiro</a></li>
                
                <?php if (isset($_SESSION['academia_id']) || isset($_SESSION['professor_id']) || isset($_SESSION['aluno_id'])): ?>
                    <li><a href="../php/logout.php" id="logout-link">Sair</a></li>
               
                
                <?php endif; ?>
            </ul>
        </aside>
    </header>

     <main>
        <div class="cards-container">
            <?php if (isset($_SESSION['academia_id'])): ?>
                <header><h1>Minhas Academias</h1></header>
                <div class="main">
                    <?php 
                    if (!empty($academias)) {
                        minhasAcademias($academias);
                    } else {
                        echo "<p>Você ainda não cadastrou nenhuma academia.</p>";
                    }
                    ?>
                </div>
            <?php else: ?>
                <header><h1>Academias Disponíveis</h1></header>
                <div class="main">
                    <?php 
                    if (!empty($academias)) {
                        exibirAcademias($academias);
                    } else {
                        echo "<p>Nenhuma academia cadastrada ainda.</p>";
                    }
                    ?>
                </div>
            <?php endif; ?>
        </div>
    </main>
</body>
</html>
