<?php
session_start();
require_once '../../base_donne/connexion.php'; // Chemin adapté !

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Non authentifié']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$name = trim($data['name'] ?? '');
$description = trim($data['description'] ?? '');

if ($name === '') {
    echo json_encode(['success' => false, 'message' => 'Nom du module obligatoire']);
    exit;
}

$id_student = $_SESSION['user_id'];

$stmt = $pdo->prepare("INSERT INTO modules (name, desciption, id_student) VALUES (?, ?, ?)");
$success = $stmt->execute([$name, $description, $id_student]);

if ($success) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Erreur SQL']);
}