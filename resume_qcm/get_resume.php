<?php
session_start();
header('Content-Type: application/json');
require_once '../base_donne/connexion.php';

if (!isset($_SESSION['id_student'])) {
    echo json_encode(['error' => 'Non connecté']);
    exit;
}
$titre = $_GET['titre'] ?? '';
$id_module = intval($_GET['id_module'] ?? 0);
$id_student = $_SESSION['id_student'];

$stmt = $pdo->prepare("SELECT titre, contenu FROM resume WHERE titre = ? AND id_module = ? AND id_student = ?");
$stmt->execute([$titre, $id_module, $id_student]);
$resume = $stmt->fetch(PDO::FETCH_ASSOC);

echo json_encode(['resume' => $resume]);