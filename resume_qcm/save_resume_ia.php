<?php
session_start();
header('Content-Type: application/json');
require_once '../base_donne/connexion.php';

if (!isset($_SESSION['id_student'])) {
    echo json_encode(['error' => 'Utilisateur non connecté.']);
    exit;
}

$input = json_decode(file_get_contents("php://input"), true);
$titre = trim($input['titre'] ?? '');
$contenu = trim($input['contenu'] ?? '');
$id_module = intval($_GET['id_module'] ?? 0);
$id_student = $_SESSION['id_student'];

if (empty($contenu) || $id_module === 0) {
    echo json_encode(['error' => 'Champs manquants.']);
    exit;
}

// Si pas de titre, on génère "Résumé X"
if (empty($titre)) {
    $count = $pdo->prepare("SELECT COUNT(*) FROM resume WHERE id_module = ? AND id_student = ?");
    $count->execute([$id_module, $id_student]);
    $num = $count->fetchColumn();
    $titre = "Résumé " . ($num + 1);
}

try {
    $insert = $pdo->prepare("INSERT INTO resume (titre, contenu, id_module, id_student) VALUES (?, ?, ?, ?)");
    $insert->execute([$titre, $contenu, $id_module, $id_student]);
    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    echo json_encode(['error' => 'Erreur lors de l’enregistrement : ' . $e->getMessage()]);
}