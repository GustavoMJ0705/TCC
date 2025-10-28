
<?php
$host = 'localhost'; 
$dbname = 'matchfight'; 
$username = 'root'; 
$password = 'root'; 

$erroSenha = '';
if (isset($_GET['erro'])) {
    $erroSenha = $_GET['erro'];
}

$erroEmail = '';
if (isset($_GET['erroemail'])) {
    $erroEmail = $_GET['erroemail'];
}

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;port=3307;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "SELECT id_genero, nm_genero FROM tb_genero";
    $result = $pdo->query($sql);

    $generos = $result->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erro ao conectar ou consultar: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/logins.css">
    <title>Cadastrar-se</title>
</head>
<body>
    <div class="logo">
        <a href="../html/contas.html">
            <img src="../img/match_ofc2.0.png" width="300rem"  alt="LogoMatch">
        </a>
    </div>

    <form action="../php/autaluno.php" method="POST">
        <img src="../img/profile-user.png" alt="perfil">

        <label for="nome">Nome:</label>
        <input type="text" id="nome" name="nome" maxlength="100" required>

        <label for="nascimento">Data de nascimento:</label>
        <input type="date" id="nascimento" name="nascimento" min="1930-01-01" 
        max="2025-12-31" required>

        <label for="genero">Gênero:</label>
        <select id="genero" name="genero" required>
            <option value="" selected>Selecione seu gênero</option>
            <?php foreach ($generos as $genero): ?>
                <option value="<?php echo htmlspecialchars($genero['id_genero']); ?>">
                    <?php echo htmlspecialchars($genero['nm_genero']); ?>
                </option>
            <?php endforeach; ?>
        </select>
        
       <label for="dojoPhone">Telefone:</label>
        <input type="tel" id="telefone" name="telefone" placeholder="(XX) XXXXX-XXXX" maxlength="15" required>


        <label for="email">E-mail:</label>
        <input type="email" id="email" name="email" maxlength="40" required>
         <?php if (!empty($erroEmail)): ?>
    <span style="color: red; font-size: 14px;"><?php echo htmlspecialchars($erroEmail); ?></span>
<?php endif; ?>

        <label for="senha">Senha:</label>
        <input type="password" id="senha" name="senha" maxlength="40" required>

        <label for="confirmar_senha">Confirmar Senha:</label>
        <input type="password" id="confirmar" name="confirmar" maxlength="40" required>

        <button class="cadastrar" type="submit">
            <a>Cadastre-se</a>
        </button>
        <?php if (!empty($erroSenha)): ?>
    <span style="color: red; font-size: 14px;"><?php echo htmlspecialchars($erroSenha); ?></span>
<?php endif; ?><br>
        
    </form>

    <script>

        function apenasNumeros(event) {
            const charCode = event.keyCode;
            if (charCode > 31 && (charCode < 48 || charCode > 57)) {
                event.preventDefault();
                return false;
            }
            return true;
        }


        document.getElementById('email').addEventListener('blur', function() {
            const email = this.value.trim().toLowerCase();
            const allowedDomains = [
                '@gmail.com',
                '@outlook.com',
                '@hotmail.com',
                '@yahoo.com',
                '@icloud.com',
                '@aol.com' 
            ];
            const isValid = allowedDomains.some(domain => email.endsWith(domain));
            if (email && !isValid) {
                alert('O email registrado não é compatível.\nUse apenas:\n gmail.com, outlook.com, hotmail.com, yahoo.com, icloud.com ou aol.com');
                this.value = '';
                this.focus();
            }
        });

        document.getElementById('telefone').addEventListener('input', function(e) {
    let value = e.target.value.replace(/\D/g, '');

    if (value.length > 11) {
        value = value.substring(0, 11);
    }

    if (value.length > 0) {
        value = '(' + value.substring(0, 2);
    }
    if (value.length >= 3) {
        value += ') ' + value.substring(2, 7);
    }
    if (value.length >= 8) {
        value += '-' + value.substring(7);
    }

    e.target.value = value;
});

    </script>
</body>
</html>
