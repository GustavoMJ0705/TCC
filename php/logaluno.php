<?php
require_once __DIR__ . '/db_connect.php';

try {

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $email = $_POST['email'];
        $senha = $_POST['senha'];

      
        $stmt = $pdo->prepare("SELECT * FROM tb_aluno WHERE ds_email = :email");
        $stmt->execute([':email' => $email]);
        $aluno = $stmt->fetch(PDO::FETCH_ASSOC);

       
       if ($aluno && password_verify($senha, $aluno['nm_senha_hash'])) {
    session_start();
    $_SESSION['aluno_id'] = $aluno['id_aluno'];
    
    $_SESSION['id_usuario'] = $aluno['id_aluno'];
    $_SESSION['tipo'] = 'aluno';
    header("Location: ../html/home.php");
    exit();

}else {
            header("Location: ../html/login_aluno.php?erro=E-mail ou senha incorretos!");
            exit();
        }
    }
} catch (PDOException $e) {
    echo "Erro: " . $e->getMessage();
}