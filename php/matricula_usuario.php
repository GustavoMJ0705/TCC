<?php
require_once 'db_connect.php';

session_start();
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

// Verifica se o usuário está logado
if (!isset($_SESSION['id_usuario']) || !isset($_SESSION['tipo'])) {
    header("Location: ../html/contas.html");
    exit();
}

// Captura e valida o ID da academia
$academia_id = filter_input(INPUT_POST, 'academia_id', FILTER_VALIDATE_INT);
if (!$academia_id) {
    header("Location: ../html/home.php?erro=academia_invalida");
    exit();
}

try {
    // Verifica se o usuário já está matriculado nesta academia
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM tb_matricula WHERE id_aluno = :aluno_id AND id_academia = :academia_id");
    $stmt->execute([
        ':aluno_id' => $_SESSION['id_usuario'],
        ':academia_id' => $academia_id
    ]);
    
    if ($stmt->fetchColumn() > 0) {
        header("Location: ../html/home.php?erro=ja_matriculado");
        exit();
    }

    // Verifica se a academia existe
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM tb_academia WHERE id_academia = :academia_id");
    $stmt->execute([':academia_id' => $academia_id]);
    if ($stmt->fetchColumn() == 0) {
        header("Location: ../html/home.php?erro=academia_nao_encontrada");
        exit();
    }

    // Insere a matrícula
    $stmt = $pdo->prepare("INSERT INTO tb_matricula (id_aluno, id_academia, dt_matricula) VALUES (:aluno_id, :academia_id, NOW())");
    $stmt->execute([
        ':aluno_id' => $_SESSION['id_usuario'],
        ':academia_id' => $academia_id
    ]);

    // Redireciona para a página de sucesso com o ID da academia
    header("Location: ../html/vermatricula.php?id=" . $academia_id);
    exit();

} catch (PDOException $e) {
    // Log do erro (em produção, use um sistema de log apropriado)
    error_log("Erro ao matricular usuário: " . $e->getMessage());
    header("Location: ../html/home.php?erro=erro_interno");
    exit();
}
?>