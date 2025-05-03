<?php
header('Content-Type: application/json');
require_once '../../base_donne/connexion.php';

$data = json_decode(file_get_contents("php://input"), true);
$id = $data['id'] ?? null;
if (!$id) {
    echo json_encode(['success' => false, 'message' => 'ID manquant.']);
    exit;
}

$stmt = $pdo->prepare("DELETE FROM notes WHERE id = ?");
$success = $stmt->execute([$id]);

if ($success) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => "Erreur lors de la suppression."]);
}
?>