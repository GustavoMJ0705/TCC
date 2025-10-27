<?php
session_start();
$host = 'localhost'; 
$dbname = 'matchfight'; 
$username = 'root'; 
$password = 'root'; 

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;port=3307;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $id = $_SESSION['id_usuario'] ?? null;
    $tipo = $_SESSION['tipo'] ?? null;

    if ($id && $tipo) {
        // Confirmação de senha
        $senhaInformada = $_POST['senha_confirmacao'] ?? '';
        if ($tipo === 'aluno') {
            $stmtSenha = $pdo->prepare("SELECT nm_senha_hash FROM tb_aluno WHERE id_aluno = :id");
        } else if ($tipo === 'professor') {
            $stmtSenha = $pdo->prepare("SELECT nm_senha_hash FROM tb_professor WHERE id_professor = :id");
        } else {
            echo "Tipo de usuário inválido.";
            exit;
        }
        $stmtSenha->execute([':id' => $id]);
        $senhaHash = $stmtSenha->fetchColumn();

        if (!$senhaHash || !password_verify($senhaInformada, $senhaHash)) {
            echo "Senha incorreta. Alteração não permitida.";
            exit;
        }

    // Monta campos para atualização
        $campos = [];
        $params = [':id' => $id];

        if (!empty($_POST['nome'])) {
            if ($tipo === 'aluno') {
                $campos[] = "nm_aluno = :nome";
            } else if ($tipo === 'professor') {
                $campos[] = "nm_professor = :nome";
            }
            $params[':nome'] = $_POST['nome'];
        }
        if (!empty($_POST['telefone'])) {
            $campos[] = "nr_telefone = :telefone";
            $params[':telefone'] = $_POST['telefone'];
        }
        if (!empty($_POST['email'])) {
            $campos[] = "ds_email = :email";
            $params[':email'] = $_POST['email'];
        }

        // Verifica se o usuário quer alterar a senha
        $novaSenha = $_POST['nova_senha'] ?? '';
        $confirmaNova = $_POST['confirma_nova_senha'] ?? '';
        if (!empty($novaSenha) || !empty($confirmaNova)) {
            // checa se os dois campos foram preenchidos
            if (empty($novaSenha) || empty($confirmaNova)) {
                echo "Preencha os campos de nova senha e confirmação.";
                exit;
            }
            // checa se coincidem
            if ($novaSenha !== $confirmaNova) {
                echo "A nova senha e a confirmação não coincidem.";
                exit;
            }
            // validação básica de força (pode ser ajustada)
        
            // adiciona o campo de senha (hash)
            $campos[] = "nm_senha_hash = :senha";
            $params[':senha'] = password_hash($novaSenha, PASSWORD_DEFAULT);
        }

        if ($campos) {
            if ($tipo === 'aluno') {
                $sql = "UPDATE tb_aluno SET " . implode(', ', $campos) . " WHERE id_aluno = :id";
            } else if ($tipo === 'professor') {
                $sql = "UPDATE tb_professor SET " . implode(', ', $campos) . " WHERE id_professor = :id";
            }
            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            header("Location: logout.php");
            exit;
        } else {
            echo "Nenhum campo para atualizar.";
        }
    } else {
        echo "ID ou tipo de usuário não informado na sessão.";
    }
} catch (PDOException $e) {
    echo "Erro: " . $e->getMessage();
}
?>