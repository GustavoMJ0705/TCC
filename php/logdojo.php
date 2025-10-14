<?php
$host = 'localhost'; 
$dbname = 'matchfight'; 
$username = 'root'; 
$password = 'root'; 

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;port=3307;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $email = $_POST['email'];
        $senha = $_POST['senha'];

      
        $stmt = $pdo->prepare("SELECT * FROM tb_academia WHERE ds_email = :email");
        $stmt->execute([':email' => $email]);
        $academia = $stmt->fetch(PDO::FETCH_ASSOC);

       
        if ($academia && password_verify($senha, $academia['nm_senha_hash'])) {
    session_start();
    $_SESSION['academia_id'] = $academia['id_academia'];
   
    
    $_SESSION['id_usuario'] = $academia['id_academia'];
    $_SESSION['tipo'] = 'academia';
    header("Location: ../html/home.php");
    exit();
} else {
            header("Location: ../html/login_dojo.php?erro=E-mail ou senha incorretos!");
            exit();
        }
    }
} catch (PDOException $e) {
    echo "Erro: " . $e->getMessage();
}