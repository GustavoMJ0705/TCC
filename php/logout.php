<?php
session_start();

// Limpa todas as variáveis de sessão
$_SESSION = array();

// Se estiver usando cookies de sessão, destrói o cookie também
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Destroi a sessão
session_destroy();

// Impede cache
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

// Redireciona para a tela de login
header("Location: ../html/contas.html");
exit();