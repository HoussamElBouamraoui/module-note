<?php
header('Content-Type: application/json');
require_once '../../base_donne/connexion.php';

$id_module = $_GET['id_module'] ?? null;
if (!$id_module) {
    echo json_encode(['success' => false, 'message' => 'id_module manquant.']);
    exit;
}

$stmt = $pdo->prepare("SELECT id, commentaire FROM notes WHERE id_module = ? ORDER BY id DESC");
$stmt->execute([$id_module]);
$notes = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode(['success' => true, 'notes' => $notes]);
?>