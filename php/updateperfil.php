<?php
session_start();
require_once __DIR__ . '/db_connect.php';

try {

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

        // Processamento de upload de imagem (se enviado)
        if (isset($_FILES['profile_image']) && isset($_FILES['profile_image']['tmp_name']) && $_FILES['profile_image']['error'] !== UPLOAD_ERR_NO_FILE) {
            $file = $_FILES['profile_image'];
            if ($file['error'] !== UPLOAD_ERR_OK) {
                echo "Erro no upload da imagem. Código: " . $file['error'];
                exit;
            }

            // Validação de tipo (usando finfo)
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime = finfo_file($finfo, $file['tmp_name']);
            finfo_close($finfo);
            $allowed = [
                'image/jpeg' => 'jpg',
                'image/png' => 'png',
                'image/gif' => 'gif',
                'image/webp' => 'webp'
            ];
            if (!array_key_exists($mime, $allowed)) {
                echo "Tipo de arquivo não permitido. Envie JPG, PNG, GIF ou WEBP.";
                exit;
            }

            $ext = $allowed[$mime];
            $uploadsDir = __DIR__ . '/../uploads';
            if (!is_dir($uploadsDir)) {
                mkdir($uploadsDir, 0755, true);
            }

            $targetName = $tipo . '_' . $id . '.' . $ext;
            $targetPath = $uploadsDir . '/' . $targetName;

            // Remove arquivos antigos do mesmo usuário (com outra extensão)
            foreach (glob($uploadsDir . '/' . $tipo . '_' . $id . '.*') as $old) {
                if ($old !== $targetPath) {
                    @unlink($old);
                }
            }

            if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
                echo "Falha ao salvar a imagem.";
                exit;
            }
            // permissões seguras
            @chmod($targetPath, 0644);
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
        $novaSenha = $_POST['novaSenha'] ?? '';
        $confirmaNova = $_POST['confirmarSenha'] ?? '';
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
            header("Location: ../html/home.php");
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