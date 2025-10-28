<?php
session_start();

require_once __DIR__ . '/db_connect.php';

// Recebe dados do formulário
$senhaAtual = $_POST['senhaAtual'] ?? '';
$novaSenha = $_POST['novaSenha'] ?? '';
$confirmarSenha = $_POST['confirmarSenha'] ?? '';

$id = $_SESSION['id_usuario'] ?? null;
$tipo = $_SESSION['tipo'] ?? null;

if (!$id || !$tipo) {
    // Usuário não autenticado
    header('Location: ../html/contas.html');
    exit;
}

if (empty($senhaAtual) || empty($novaSenha) || empty($confirmarSenha)) {
    echo 'Preencha todos os campos.';
    exit;
}

if ($novaSenha !== $confirmarSenha) {
    echo 'A nova senha e a confirmação não coincidem.';
    exit;
}



try {
   

    if ($tipo === 'aluno') {
        $stmt = $pdo->prepare('SELECT nm_senha_hash FROM tb_aluno WHERE id_aluno = :id');
    } elseif ($tipo === 'professor') {
        $stmt = $pdo->prepare('SELECT nm_senha_hash FROM tb_professor WHERE id_professor = :id');
    } else {
        echo 'Tipo de usuário inválido.';
        exit;
    }

    $stmt->execute([':id' => $id]);
    $hash = $stmt->fetchColumn();

    if (!$hash || !password_verify($senhaAtual, $hash)) {
        echo 'Senha atual incorreta.';
        exit;
    }

    $newHash = password_hash($novaSenha, PASSWORD_DEFAULT);

    if ($tipo === 'aluno') {
        $upd = $pdo->prepare('UPDATE tb_aluno SET nm_senha_hash = :senha WHERE id_aluno = :id');
    } else {
        $upd = $pdo->prepare('UPDATE tb_professor SET nm_senha_hash = :senha WHERE id_professor = :id');
    }

    $upd->execute([':senha' => $newHash, ':id' => $id]);

    // Após trocar a senha, forçar logout (padrão usado em outros fluxos)
    header('Location: logout.php');
    exit;

} catch (PDOException $e) {
    echo 'Erro ao acessar o banco: ' . $e->getMessage();
    exit;
}

?>