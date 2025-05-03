<?php
header('Content-Type: application/json');
require_once '../../base_donne/connexion.php';

$data = json_decode(file_get_contents("php://input"), true);
$id = $data['id'] ?? null;
$commentaire = $data['commentaire'] ?? '';
if (!$id || $commentaire == '') {
    echo json_encode(['success' => false, 'message' => 'Paramètres manquants.']);
    exit;
}

$stmt = $pdo->prepare("UPDATE notes SET commentaire = ? WHERE id = ?");
$success = $stmt->execute([$commentaire, $id]);

if ($success) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => "Erreur lors de la modification."]);
}
?>