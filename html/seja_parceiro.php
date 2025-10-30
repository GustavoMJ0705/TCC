
<?php
session_start();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>MatchFight - Seja um Parceiro</title>
    <link rel="stylesheet" href="../css/home.css">
    <link rel="stylesheet" href="../css/navbar.css">
    <link rel="stylesheet" href="../css/parceiro.css">
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
                 <li><a href="home.php">Home</a></li>
                <li><a href="suporte_tecnico.php">Suporte técnico</a></li>
                <li><a href="seja_parceiro.php">Seja um parceiro</a></li>
            
                <?php if (isset($_SESSION['academia_id']) || isset($_SESSION['professor_id']) || isset($_SESSION['aluno_id'])): ?>
                    <li><a href="../php/logout.php" id="logout-link">Sair</a></li>
               
                
                <?php endif; ?>
            </ul>
        </aside>
    </header>

<main class="partner-main">
    <section class="partner-box">
        <h1>Seja um Parceiro MatchFight</h1>

        <?php if (isset($_GET['success'])): ?>
            <div class="msg-success">Mensagem enviada com sucesso. Obrigado pelo interesse.</div>
        <?php endif; ?>
        <?php if (isset($_GET['error'])): ?>
            <div class="msg-error">Ocorreu um erro ao enviar. Verifique os campos e tente novamente.</div>
        <?php endif; ?>

        <form action="../php/seja_parceiro_enviar.php" method="post" class="partner-form">
            <label for="nome">Nome completo *</label>
            <input id="nome" name="nome" type="text" required value="<?php echo isset($_SESSION['nome']) ? htmlspecialchars($_SESSION['nome']) : ''; ?>">

            <label for="email">E-mail *</label>
            <input id="email" name="email" type="email" required>

            <label for="motivo">Por que você tem interesse em se tornar afiliado ao MatchFight? *</label>
            <textarea id="motivo" name="motivo" rows="6" required style="resize: none;"></textarea>

            <div class="partner-actions">
                <button type="submit" class="btn-primary">Enviar</button>
                <a href="home.php" class="btn-secondary" style="margin-bottom:30px;">Cancelar</a>
            </div>
        </form>
    </section>
</main>

</body>
</html>