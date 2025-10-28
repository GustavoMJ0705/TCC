<?php
require_once __DIR__ . '/db_connect.php';

try {

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $email = $_POST['email'];
        $senha = $_POST['senha'];

       
        $stmt = $pdo->prepare("SELECT * FROM tb_professor WHERE ds_email = :email");
        $stmt->execute([':email' => $email]);
        $professor = $stmt->fetch(PDO::FETCH_ASSOC);

       
        if ($professor && password_verify($senha, $professor['nm_senha_hash'])) {
    session_start();
    $_SESSION['professor_id'] = $professor['id_professor'];

    $_SESSION['id_usuario'] = $professor['id_professor'];
    $_SESSION['tipo'] = 'professor';
    header("Location: ../html/home.php");
    exit();
} else {
            header("Location: ../html/login_professor.php?erro=E-mail ou senha incorretos!");
            exit();
        }
    }
} catch (PDOException $e) {
    echo "Erro: " . $e->getMessage();
}