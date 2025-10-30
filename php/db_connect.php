<?php
// Central PDO connection used by the application
$host = 'localhost';
$dbname = 'matchfight';
$username = 'root';
$password = 'root';
$port = 3307; // XAMPP default MySQL port

try {
    $pdo = new PDO("mysql:host={$host};dbname={$dbname};port={$port};charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Re-throw so including scripts can catch or let it bubble up to error page
    throw $e;
}

?>
