<?php

$host = 'localhost'; 
$dbname = 'matchfight'; 
$username = 'root'; 
$password = 'root'; 

session_start();
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

$tipo = $_SESSION['tipo'] ?? null;
$id_usuario = $_SESSION['id_usuario'] ?? null;

$dados = [
    'nome' => '',
    'telefone' => '',
    'email' => '',
    'senha' => ''
];
if (!isset($_SESSION['aluno_id']) && !isset($_SESSION['professor_id'])) {
    header("Location: contas.html");
    exit();
}
if ($id_usuario && $tipo) {
    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname;port=3307;charset=utf8", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        if ($tipo === 'aluno') {
            $stmt = $pdo->prepare("SELECT nm_aluno AS nome, nr_telefone AS telefone, ds_email AS email, nm_senha_hash AS senha FROM tb_aluno WHERE id_aluno = :id");

            if(isset($_POST["botaoDel"])){
                 $senhaInformada = $_POST['senhaDel'] ?? '';
        $stmtSenha = $pdo->prepare("SELECT nm_senha_hash FROM tb_aluno WHERE id_aluno = :id");
        $stmtSenha->execute([':id' => $id_usuario]);
        $senhaHash = $stmtSenha->fetchColumn();

        if ($senhaHash && password_verify($senhaInformada, $senhaHash)) {
            $delete = $pdo->prepare("DELETE FROM tb_aluno WHERE id_aluno = :id");
            $delete->execute([':id' => $id_usuario]);
            header("Location: ../php/logout.php");
            exit;
        }else {
            echo "<script>alert('Senha incorreta!');</script>";
        }

            }

        } elseif ($tipo === 'professor') {
            $stmt = $pdo->prepare("SELECT nm_professor AS nome, nr_telefone AS telefone, ds_email AS email, nm_senha_hash AS senha FROM tb_professor WHERE id_professor = :id");

            if(isset($_POST["botaoDel"])){
            $senhaInformada = $_POST['senhaDel'] ?? '';
        $stmtSenha = $pdo->prepare("SELECT nm_senha_hash FROM tb_professor WHERE id_professor = :id");
        $stmtSenha->execute([':id' => $id_usuario]);
        $senhaHash = $stmtSenha->fetchColumn();

        if ($senhaHash && password_verify($senhaInformada, $senhaHash)) {
            $delete = $pdo->prepare("DELETE FROM tb_professor WHERE id_professor = :id");
            $delete->execute([':id' => $id_usuario]);
            header("Location: ../php/logout.php");
            exit;
        } else {
            echo "<script>alert('Senha incorreta!');</script>";
        }
    }

        if(isset($_POST["botaoSave"])){
            $update = $pdo->prepare("UPDATE tb_professor SET nm_professor = :nome, nr_telefone = :telefone, ds_email = :email, nm_senha_hash = :senha WHERE id_professor = :id");
    $update->execute([
        ':nome' => $_POST['nome'],
        ':telefone' => $_POST['telefone'],
        ':email' => $_POST['email'],
        ':senha' => password_hash($_POST['senha'], PASSWORD_DEFAULT),
        ':id' => $id_usuario
    ]);
                header("Location: ../php/logout.php");
                exit;
        }
    }
        $stmt->execute([':id' => $id_usuario]);
        $dados = $stmt->fetch(PDO::FETCH_ASSOC) ?: $dados;
    } catch (PDOException $e) {
        die("Erro ao conectar ou consultar: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Meu Perfil - Aluno</title>
    <link rel="stylesheet" href="../css/mperfil.css">
    <link rel="stylesheet" href="../css/navbar.css">
    <script src="../js/nav.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
   <header>
        <nav class="navbar">
            <div class="menu-icon" onclick="toggleSidebar()">
                <div class="bar"></div>
                <div class="bar"></div>
                <div class="bar"></div>
            </div>
           
             
          
            <div class="search-bar">
                <input type="text" placeholder="Pesquisar academias...">
            </div>
            <div class="Perfil">
                 <?php if (isset($_SESSION['professor_id']) || isset($_SESSION['aluno_id'])): ?>
                   
                <a href="mperfil.php" class="lbottom">Meu Perfil</a>
                <?php elseif (isset($_SESSION['academia_id'])):    ?>
                   <?php else: ?>
                    <a href="contas.html" id="login-link" class="lbottom">Login</a></div>
              
            <?php endif; ?>
            </div> 
        </nav>
        
        <aside class="sidebar" id="sidebar">
            <span class="close-btn" onclick="toggleSidebar()">&times;</span>
            <ul>
                <?php if (isset($_SESSION['academia_id'])): ?>
                    <li><a href="../html/criardojo.php">Criar Academia</a></li>

                <?php endif; ?>
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

    <div class="perfil-container">
        <h2>Meu Perfil</h2>
        <!-- Foto de perfil -->
        <div style="text-align:center; margin-bottom:20px;">
            <img id="fotoPerfil" src="../img/profile-user.png" alt="Foto de Perfil" style="width:100px; height:100px; aspect-ratio:1/1; border-radius:50%; object-fit:cover; border:2px solid #ccc;">
            <br>
            <input type="file" id="inputFoto" accept="image/*" style="margin-top:10px;" >
        </div>
    <form id="perfilForm" method="post" action="../php/updateperfil.php">
            <label for="nome">Nome</label>
            <input type="text" id="nome" name="nome" value="<?php echo htmlspecialchars($dados['nome']); ?>" required>

            <label for="telefone">Telefone</label>
            <input type="text" id="telefone" name="telefone" readonly value="<?php echo htmlspecialchars($dados['telefone']); ?>" required>

            <label for="email">E-mail</label>
            <input type="email" id="email" name="email" readonly value="<?php echo htmlspecialchars($dados['email']); ?>" required>

            <label for="senha_confirmacao">Digite sua senha:</label>
<input type="password" id="senha_confirmacao" name="senha_confirmacao" placeholder="Insira sua senha para confirmar alterações" required>

            <div class="senha">

             <label for="nova_senha">Digite sua nova senha:</label>
            <input type="password" id="nova_senha" name="nova_senha" placeholder="Digite uma nova senha se quiser alterar" >

        <label for="confirma_nova_senha">confirme sua nova senha:</label>
        <input type="password" id="confirma_nova_senha" name="confirma_nova_senha" placeholder="Confirme a nova senha caso queira altera-la" >

            </div>
            
           <!-- <label for="senha">Senha</label>
            <input type="password" id="senha" name="senha" value="" required> -->
            <!-- Nunca exiba o hash da senha! -->
            <!-- Peça para digitar nova senha se quiser alterar -->
<div class="btn-group">
                
                <button type="button" class="btn-apagar" onclick="validarESalvar()" name="botaoDel" style= "align-items: center">Salvar Alterações</button>
            </div>
        </form>

       


<script>
function AtualizarUsuario() {
    // Envia o formulário de edição para atualizar os dados do usuário
    document.getElementById('perfilForm').submit();

}
</script>

<script>
function validarESalvar() {
    const origem = document.getElementById('senha_confirmacao');
    const nova = document.getElementById('nova_senha');
    const confirma = document.getElementById('confirma_nova_senha');

    // confirma senha atual preenchida
    if (!origem.value) {
        alert('Digite sua senha atual para confirmar as alterações.');
        origem.focus();
        return;
    }

    

    // tudo ok, submete o formulário
    document.getElementById('perfilForm').submit();
}
</script>

    </div>
    <script>
    

window.addEventListener('pageshow', function(event) {
    if (event.persisted) {
        window.location.reload();
    }
});

// Preview da imagem de perfil
document.getElementById('inputFoto').addEventListener('change', function(event) {
    const file = event.target.files[0];
    if (file && file.type.startsWith('image/')) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const fotoPerfil = document.getElementById('fotoPerfil');
            fotoPerfil.src = e.target.result;
        };
        reader.readAsDataURL(file);
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