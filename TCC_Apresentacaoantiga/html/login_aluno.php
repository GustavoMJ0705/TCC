<?php
$host = 'localhost'; 
$dbname = 'matchfight'; 
$username = 'root'; 
$password = 'root'; 

$erroSenha = '';
if (isset($_GET['erro'])) {
    $erroSenha = $_GET['erro'];
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/logins.css">
    <title>Login Aluno</title>
</head>
<body>
    <div class="logo">
        <a href="../html/contas.html">
        <img src="../img/match_ofc2.0.png" width="600px"  alt="LogoMatch">
        </a>
    </div>
     <form action="../php/logaluno.php" method="POST">
        <img src="../img/profile-user.png" alt="perfil">

        <label for="email">E-mail:</label>
        <input type="email" id="email" name="email" required>
        <br>

        <label for="senha">Senha:</label>
        <input type="password" id="senha" name="senha" required>
         <?php if (!empty($erroSenha)): ?>
    <span style="color: red; font-size: 14px;"><?php echo htmlspecialchars($erroSenha); ?></span>
<?php endif; ?>
        <br>
        <button class="cadastrar" type="submit">Logar</button>
    </form>
</body>
</html>