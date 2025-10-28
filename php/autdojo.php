<?php
$host = 'localhost'; 
$dbname = 'matchfight'; 
$username = 'root'; 
$password = 'root'; 

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;port=3307;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nome = $_POST['nomeacad'];
        $telefone = $_POST['telefone'];
        $email = $_POST['email'];
        $senha = $_POST['senha'];
        $confirmar = $_POST['confirmar'];

        if ($senha !== $confirmar) {
    header("Location: ../html/cad_dojo.php?erro=As senhas não coincidem!");
    exit();
        }
        $stem = $pdo->prepare("SELECT * FROM tb_aluno WHERE ds_email = :email");
        $stem->execute([':email' => $email]);
        $alunoExistente = $stem->fetch(PDO::FETCH_ASSOC);

         $stpr = $pdo->prepare("SELECT * FROM tb_professor WHERE ds_email = :email");
        $stpr->execute([':email' => $email]);
        $ProfExistente = $stpr->fetch(PDO::FETCH_ASSOC);

        $stdo = $pdo->prepare("SELECT * FROM tb_academia WHERE ds_email = :email");
        $stdo->execute([':email' => $email]);
        $DojoExistente = $stdo->fetch(PDO::FETCH_ASSOC);
        if ($DojoExistente || $ProfExistente || $alunoExistente) {
            header("Location: ../html/cad_dojo.php?erroemail=E-mail já cadastrado!");
            exit();
        }

        $stmt = $pdo->prepare("INSERT INTO tb_academia
            (nm_academia, ds_email, nm_senha_hash, nr_telefone) 
            VALUES 
            (:nome, :email, :senha, :telefone)");
        $stmt->execute([
            ':nome' => $nome,
             ':email' => $email,
            ':senha' => password_hash($senha, PASSWORD_DEFAULT),
            ':telefone' => $telefone
           
        ]);
header("Location: ../html/login_dojo.php");
        exit();
    }
} catch (PDOException $e) {
    echo "Erro: " . $e->getMessage();
} 
