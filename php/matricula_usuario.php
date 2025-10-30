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

// Captura e valida o ID do perfil da academia (id_perfil_academia)
$perfil_id = filter_input(INPUT_POST, 'perfil_id', FILTER_VALIDATE_INT);
$horarios_aula = isset($_POST['horarios_aula']) ? $_POST['horarios_aula'] : [];

if (!$perfil_id || empty($horarios_aula)) {
    header("Location: ../html/home.php?erro=dados_invalidos");
    exit();
}

// Valida se todos os horários são números inteiros
$horarios_validos = array_filter($horarios_aula, function($id) {
    return filter_var($id, FILTER_VALIDATE_INT) !== false;
});

if (count($horarios_validos) !== count($horarios_aula)) {
    header("Location: ../html/home.php?erro=horarios_invalidos");
    exit();
}

try {
    // Primeiro converte perfil_id (id_perfil_academia) para id_academia

    // Verifica se o usuário já está matriculado nesta academia (por id_academia)
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM tb_matricula WHERE id_aluno = :aluno_id AND id_perfil_academia = :perfil_id");
    $stmt->execute([
        ':aluno_id' => $_SESSION['id_usuario'],
        ':perfil_id' => $perfil_id
    ]);
    if ($stmt->fetchColumn() > 0) {
        header("Location: ../html/home.php?erro=ja_matriculado");
        exit();
    }

    // Inicia a transação
    $pdo->beginTransaction();
    
    try {
        // Insere a matrícula (associa ao id_academia correspondente)
        $stmt = $pdo->prepare("INSERT INTO tb_matricula (id_aluno, id_perfil_academia, dt_matricula) VALUES (:aluno_id, :perfil_id, NOW())");
        $stmt->execute([
            ':aluno_id' => $_SESSION['id_usuario'],
            ':perfil_id' => $perfil_id
        ]);
        
        // Pega o ID da matrícula inserida
        $id_matricula = $pdo->lastInsertId();
        
        // Associa os horários à matrícula
        $stmt = $pdo->prepare("INSERT INTO tb_matricula_aulas (id_matricula, id_aulas) VALUES (:id_matricula, :id_aulas)");
        
        foreach ($horarios_validos as $horario_id) {
            $stmt->execute([
                ':id_matricula' => $id_matricula,
                ':id_aulas' => $horario_id
            ]);
        }
        
        $pdo->commit();
    } catch (Exception $e) {
        $pdo->rollBack();
        throw $e;
    }

    // Redireciona para a página principal do aluno com mensagem de sucesso
    header("Location: ../html/home.php?sucesso=matricula");
    exit();

} catch (PDOException $e) {
    // Log do erro (em produção, use um sistema de log apropriado)
    error_log("Erro ao matricular usuário: " . $e->getMessage());
    header("Location: ../html/home.php?erro=erro_interno");
    exit();
}
?>