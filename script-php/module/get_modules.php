<?php
session_set_cookie_params([
    'lifetime' => 3600,
    'path' => '/',
    'httponly' => true,
    'samesite' => 'Lax'
]);

if (empty(session_id())) {
    session_start();
}
if (!isset($_SESSION['user_id']) && isset($_COOKIE['user_id'])) {
    $_SESSION['user_id'] = $_COOKIE['user_id'];
}
require_once '../../base_donne/connexion.php';
global $pdo;

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'modules' => [], 'message' => 'Non authentifié', 'session' => $_SESSION]);
    exit;
}

$id_student = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT id, name, desciption FROM modules WHERE id_student = ?");
$stmt->execute([$id_student]);
$modules = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode(['success' => true, 'modules' => $modules]);