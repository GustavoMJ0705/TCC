<?php
$host = 'localhost'; 
$dbname = 'matchfight'; 
$username = 'root'; 
$password = 'root'; 

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;port=3307;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nome = $_POST['nome'];
        $nascimento = $_POST['nascimento'];
        $genero = $_POST['genero'];
        $telefone = $_POST['telefone'];
        $email = $_POST['email'];
        $senha = $_POST['senha'];
        $confirmar = $_POST['confirmar'];

        if ($senha !== $confirmar) {
    header("Location: ../html/cad_professor.php?erro=As senhas não coincidem!");
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
            header("Location: ../html/cad_aluno.php?erroemail=E-mail já cadastrado!");
            exit();
        }

        $stmt = $pdo->prepare("INSERT INTO tb_professor 
            (nm_professor, dt_nascimento, ds_email, nm_senha_hash, nr_telefone, id_genero) 
            VALUES 
            (:nome, :nascimento, :email, :senha, :telefone, :genero)");
        $stmt->execute([
            ':nome' => $nome,
            ':nascimento' => $nascimento,
            ':email' => $email,
            ':senha' => password_hash($senha, PASSWORD_DEFAULT),
            ':telefone' => $telefone,
            ':genero' => $genero
        ]);

        header("Location: ../html/login_professor.php");
        exit();
    
    }
} catch (PDOException $e) {
    echo "Erro: " . $e->getMessage();
}