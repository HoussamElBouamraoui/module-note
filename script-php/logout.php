<?php
// Inclure le gestionnaire de session
require_once 'session_handler.php';

// Démarrer la session
session_start();

// Détruire la session
session_destroy();

// Supprimer les cookies liés à la session
if (isset($_COOKIE['admin_id'])) {
    setcookie('admin_id', '', time() - 3600, '/'); // Expire le cookie
}
if (isset($_COOKIE['user_id'])) {
    setcookie('user_id', '', time() - 3600, '/'); // Expire le cookie
}

// Rediriger vers la page de connexion
header('Location: ../pages/login.html');
exit();
?>