<?php
session_start();
header('Content-Type: application/json');
require_once '../base_donne/connexion.php';

if (!isset($_SESSION['id_student'])) {
    echo json_encode(['error' => 'Non connecté']);
    exit;
}

$input = json_decode(file_get_contents("php://input"), true);
$question = trim($input['question'] ?? '');
$id_module = intval($input['id_module'] ?? 0);

if (!$question || !$id_module) {
    echo json_encode(['error' => 'Paramètres manquants']);
    exit;
}

// Clé API OpenRouter (à sécuriser)
$api_key = "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";

$id_student = $_SESSION['id_student'];
$stmt = $pdo->prepare("SELECT commentaire FROM notes WHERE id_student = ? AND id_module = ? ORDER BY id ASC");
$stmt->execute([$id_student, $id_module]);
$notes = $stmt->fetchAll(PDO::FETCH_COLUMN);
$notes_text = $notes ? implode("\n", $notes) : "Aucune note disponible.";

$full_prompt = "Voici toutes les notes du module :\n" . $notes_text . "\n\n" . $question;

$data = [
    "model" => "openai/gpt-3.5-turbo",
    "messages" => [
        ["role" => "user", "content" => $full_prompt]
    ]
];

$ch = curl_init("https://openrouter.ai/api/v1/chat/completions");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Content-Type: application/json",
    "Authorization: Bearer $api_key"
]);

$response = curl_exec($ch);
curl_close($ch);

$json = json_decode($response, true);
if (isset($json['choices'][0]['message']['content'])) {
    echo json_encode(['reponse' => $json['choices'][0]['message']['content']]);
} else {
    echo json_encode(['error' => 'Réponse IA non valide']);
}