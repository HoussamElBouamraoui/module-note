<?php
session_start();
require_once '../../base_donne/connexion.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'modules' => [], 'message' => 'Non authentifié']);
    exit;
}

$id_student = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT id, name, desciption FROM modules WHERE id_student = ?");
$stmt->execute([$id_student]);
$modules = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode(['success' => true, 'modules' => $modules]);