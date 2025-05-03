<?php
session_start();
require_once '../../base_donne/connexion.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Non authentifié']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$id_module = intval($data['id'] ?? 0);
$id_student = $_SESSION['user_id'];

if (!$id_module) {
    echo json_encode(['success' => false, 'message' => 'ID module manquant']);
    exit;
}

// On vérifie que ce module appartient bien à l’étudiant connecté
$stmt = $pdo->prepare("DELETE FROM modules WHERE id = ? AND id_student = ?");
$success = $stmt->execute([$id_module, $id_student]);

if ($success && $stmt->rowCount() > 0) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Suppression impossible']);
}