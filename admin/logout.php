<?php
/**
 * admin/logout.php
 * Encerra a sessão do usuário e redireciona para a página de login
 */

session_start();

// Destruir todas as variáveis de sessão
$_SESSION = array();

// Destruir o cookie de sessão se existir
if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time() - 42000, '/');
}

// Destruir a sessão
session_destroy();

// Redirecionar para a página de login
header('Location: ../login.php');
exit;
?>