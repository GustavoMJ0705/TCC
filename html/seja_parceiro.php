
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
                <a href="home.php" class="lbottom" style="margin-left:12px;">Voltar para Home</a>
        <div class="Perfil">
                <a href="contas.html" id="login-link" class="lbottom">Cadastrar</a>
        </div>
    </nav>
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

            <label for="telefone">Telefone (opcional)</label>
            <input id="telefone" name="telefone" type="number">

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