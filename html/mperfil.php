<?php

require_once __DIR__ . '/../php/db_connect.php';

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
            <a href="search.php">
                <div class="search">
                    <button class="buttonSearch hover-button">
                        <img src="../img/lupa.png" alt="Lupa" class="icon-lupa">
                        <span class="search-text">Pesquisar Dojo</span>
                    </button>
                </div>
            </a>
            <div class="logo">
                <img src="../img/match_ofc2.0.png" alt="Logo do Match Fight, um homem chutando ao lado da escrita Match Fight" width="150rem">
            </div>
            <div class="Perfil">
                <?php if (isset($_SESSION['professor_id']) || isset($_SESSION['aluno_id'])): ?>
                    <a href="mperfil.php" class="lbottom_AlunoProf"><img src="../img/Perfil.png" alt=""></a>
                <?php elseif (isset($_SESSION['academia_id'])):?>
                <?php else: ?>
                    <a href="contas.html" id="login-link" class="lbottom">Cadastrar</a>
                <?php endif; ?>
                <?php if (isset($_SESSION['academia_id'])): ?>
                    <a href="criardojo.php" class="lbottom">Criar Academia</a>
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
        </div>
        <form id="perfilForm" method="post" action="../php/updateperfil.php">
           <div class="info-group"> 
            <label for="Nome" class="title">Nome de Usuário: </label>  
             <label class="info"><?php echo htmlspecialchars($dados['nome']); ?></label>
        </div>
        
            
            <div class="info-group"> 
            <label for="telefone" class="title">Telefone: </label>  
             <label class="info"><?php echo htmlspecialchars($dados['telefone']); ?></label>
        </div>
      
           
           <div class="info-group"> 
            <label for="email" class="title">Email: </label>  
             <label class="info"><?php echo htmlspecialchars($dados['email']); ?></label>
        </div>
      


           <div class="btn-group">
                <button type="button" id="btn-apagar" class="btn-apagar">Apagar Usuário</button>
                <a href="editperfil.php" class="btn-salvar">Editar meu perfil</a>
            </div>

            <div class="modal" id="modalQuestion">
                <div class="conteudoModal" id="conteudoModal1">
                    <h2>Tem certeza que deseja apagar sua conta?</h2>
                    <p>Esta ação não pode ser desfeita.</p>
                    <button type="button" class="botaoConfirm" id="botaoConfirm">Sim, apagar</button>
                </div>
            </div>

            <div class="modal" id="modalSenha">
                <div class="conteudoModal" id="conteudoModal2">
                    <h2>Digite sua senha para confirmar:</h2>
                    <input type="password" class="senha" id="senhaInput">
                    <button type="button" class="botaoConfirm" id="botaoAvancar">Avançar</button>
                </div>
            </div>

        </form>

        <form id="formDel" method="post" style="display:none;">
    <input type="hidden" name="botaoDel" value="1">
    <input type="hidden" name="senhaDel" id="senhaDel">
</form>

<script>
// Seleciona os elementos do DOM
const btnApagar = document.getElementById('btn-apagar');
const modalQuestion = document.getElementById('modalQuestion');
const modalSenha = document.getElementById('modalSenha');
const btnConfirmar = document.getElementById('botaoConfirm');
const btnAvancar = document.getElementById('botaoAvancar');
const senhaInput = document.getElementById('senhaInput');

// 1. Abre o modal de pergunta ao clicar em "Apagar Usuário"
btnApagar.addEventListener('click', () => {
    modalQuestion.style.display = 'flex';
});

// 2. Fecha o modal de pergunta e abre o modal de senha
btnConfirmar.addEventListener('click', () => {
    modalQuestion.style.display = 'none';
    modalSenha.style.display = 'flex';
    senhaInput.focus(); 
});

// 3. Pega a senha e submete o formulário de deleção
btnAvancar.addEventListener('click', () => {
    const senha = senhaInput.value;
    if (senha) {
        document.getElementById('senhaDel').value = senha;
        document.getElementById('formDel').submit();
    } else {
        alert('Por favor, digite sua senha.');
    }
});

// Função para fechar os modais se o usuário clicar fora deles
window.addEventListener('click', (e) => {
    if (e.target === modalQuestion) {
        modalQuestion.style.display = 'none';
    }
    if (e.target === modalSenha) {
        modalSenha.style.display = 'none';
    }
});

// Recarrega a página se ela for carregada do cache do navegador (útil para o botão "voltar")
window.addEventListener('pageshow', function(event) {
    if (event.persisted) {
        window.location.reload();
    }
});
</script>

    </div>
</body>
</html>