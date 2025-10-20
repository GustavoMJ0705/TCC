<?php
$host = 'localhost'; 
$dbname = 'matchfight'; 
$username = 'root'; 
$password = 'root'; 

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;port=3307;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


    
}    catch (PDOException $e) {
    echo "Erro: " . $e->getMessage();}