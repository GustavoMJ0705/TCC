<?php
require_once __DIR__ . '/db_connect.php';

try {

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