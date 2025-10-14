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
        <img src="../img/match_ofc2.0.png" width="600px"  alt="LogoMatch">
        </a>
    </div>

     <form action="../php/autdojo.php" method="POST">  
        <img src="../img/profile-user.png" alt="perfil">
        
         <label for="nome">Nome da academia:</label>
        <input type="text" id="nomeacad" name="nomeacad" required>
        <br>


        <label for="dojoPhone">Telefone:</label>
        <input type="tel" id="dojoPhone" name="dojoPhone" placeholder="XXXXX-XXXX" onkeydown="return apenasNumeros(event)" minlength="8" maxlength="15" required>
        <br>
        <label for="email">E-mail:</label>
        <input type="email" id="email" name="email" required>
         <?php if (!empty($erroEmail)): ?>
    <span style="color: red; font-size: 14px;"><?php echo htmlspecialchars($erroEmail); ?></span>
<?php endif; ?>
        <br>

        <label for="senha">Senha:</label>
        <input type="password" id="senha" name="senha" required>
        <br>

        <label for="confirmar_senha">Confirmar Senha:</label>
        <input type="password" id="confirmar" name="confirmar" required>
        <br>

        <button class="cadastrar"type="submit">Cadastre-se</button><br>
       <?php if (!empty($erroSenha)): ?>
    <span style="color: red; font-size: 14px;"><?php echo htmlspecialchars($erroSenha); ?></span>
<?php endif; ?>
    </form>

    <script>

         // CEP restringindo oa forma com que ele será escrito 
        document.getElementById('dojoCEP').addEventListener('input', function(e) {
            let valor = e.target.value.replace(/\D/g, ''); // Apenas números
            if (valor.length > 5) {
                valor = valor.slice(0, 5) + '-' + valor.slice(5, 8);
            }
            e.target.value = valor;
        });

        // Validação e preenchimento do CEP
        document.getElementById('dojoCEP').addEventListener('blur', function() {
            const cep = this.value.replace(/\D/g, '');
            
            if (cep.length === 8) {
                fetch(`https://viacep.com.br/ws/${cep}/json/`)
                    .then(res => res.json())
                    .then(data => {
                        if (data.erro) {
                            alert("CEP não encontrado!");
                            this.value = '';
                            this.focus();
                            return;
                        }
                        preencherDDDs(data.uf);
                    })
                } else if (cep.length > 0) {
                    alert("CEP incompleto!");
                    this.value = '';
                    this.focus();
                }
            });

            function buscarEndereco(cep) {
  fetch(`https://viacep.com.br/ws/${cep}/json/`)
    .then(response => response.json())
    .then(data => {
      if (!data.erro) {
        document.getElementById('rua').value = data.logradouro;
        document.getElementById('bairro').value = data.bairro;
        document.getElementById('cidade').value = data.localidade;
        document.getElementById('estado').value = data.uf;
      } else {
        alert('CEP não encontrado!');
      }
    });
}

              // Função para permitir apenas numero no telefone
        function apenasNumeros(event) {
            const charCode = event.keyCode;
            if (charCode > 31 && (charCode < 48 || charCode > 57)) {
                event.preventDefault();
                return false;
            }
            return true;
        }

        // Validação do domínio do email
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
        if (value.length > 2) {
        value = '(' + value.substring(0, 2) + ') ' + value.substring(2);
        }
            if (value.length > 10) {
             value = value.substring(0, 10) + '-' + value.substring(10, 15);
                }
            e.target.value = value;
             });

    </script>

</body>
</html>