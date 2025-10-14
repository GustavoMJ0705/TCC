<?php
$host = 'localhost'; 
$dbname = 'matchfight'; 
$username = 'root'; 
$password = 'root'; 

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;port=3307;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Pegando o ID do usuário
    if (isset($_POST['id'])){
        $id =($_POST);
    

// Usando prepared statement para segurança
    $stmt = $pdo->prepare("DELETE FROM usuarios WHERE id = ?");
    $stmt->bind_param("i", $id);

     if ($stmt->execute()) {
        echo "Usuário apagado com sucesso!";
    } else {
        echo "Erro ao apagar usuário: " . $stmt->error;
    }
     $stmt->close();

    }else{
        echo "ID do usuário não fornecido.";
    }

    $pdo->close();
}  catch (PDOException $e) {
    echo "Erro: " . $e->getMessage();
}


?>