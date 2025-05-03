<?php
session_start();
header('Content-Type: application/json');
require_once '../../base_donne/connexion.php';

$data = json_decode(file_get_contents("php://input"), true);
$commentaire = $data['commentaire'] ?? '';
$id_student = $data['id_student'] ?? null;
$id_module = $data['id_module'] ?? null;

if (!$id_student || !$id_module || $commentaire == '') {
    echo json_encode(['success' => false, 'message' => 'Paramètres manquants.']);
    exit;
}

$stmt = $pdo->prepare("INSERT INTO notes (id_student, id_module, commentaire) VALUES (?, ?, ?)");
$success = $stmt->execute([$id_student, $id_module, $commentaire]);

if ($success) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => "Erreur lors de l'ajout."]);
}
?>