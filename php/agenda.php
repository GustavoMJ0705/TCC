<?php
require_once __DIR__ . '/db_connect.php';

try {

    
}    catch (PDOException $e) {
    echo "Erro: " . $e->getMessage();}