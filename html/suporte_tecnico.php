
<?php
session_start();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MatchFight - Suporte Técnico</title>
    <link rel="stylesheet" href="../css/home.css">
    <link rel="stylesheet" href="../css/navbar.css">
    <script src="../js/nav.js"></script>
</head>
<body>
    <style>
        main{
            padding-left:24px;
        }
    </style>

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

    <main>
        <div class="page-header">
            <h1>Suporte Técnico</h1>
        </div>
        <div class="support-content">
            <h2>Como podemos ajudar?</h2>
            <p>Se você está enfrentando problemas técnicos ou precisa de assistência, por favor, entre em contato conosco através dos seguintes canais:</p>
            <ul>
                <li>Email: suporte@matchfight.com</li>
                <li>Telefone: (13) 98128-2224</li>
                <li>Chat ao vivo: disponível no nosso site durante o horário comercial.</li>
            </ul>
            <h3>Dúvidas Frequentes</h3>
            <p>Confira nossa seção de perguntas frequentes para soluções rápidas:</p>
            <ul>
                <li><a href="contas.html">Deseja criar uma conta?</a></li>
                <li><a href="#">Como entrar em contato com um professor?</a></li>
            </ul>
        </div>
    </main>

</body>
</html>