<?php
session_start();
require_once '../base_donne/connexion.php';

if (!isset($_SESSION['id_student'])) {
    header('Location: ../pages/login.html');
    exit;
}

$id_student = $_SESSION['id_student'];

// Récupérer les modules de l'étudiant
$modules = $pdo->prepare("SELECT id, name FROM modules WHERE id_student = ?");
$modules->execute([$id_student]);
$modules = $modules->fetchAll(PDO::FETCH_ASSOC);

// Module sélectionné
$module_id = isset($_GET['id_module']) ? intval($_GET['id_module']) : ($modules[0]['id'] ?? null);

// Récupérer les résumés existants du module
$resumes = [];
if ($module_id) {
    $req = $pdo->prepare("SELECT id, titre FROM resume WHERE id_module = ? AND id_student = ?");
    $req->execute([$module_id, $id_student]);
    $resumes = $req->fetchAll(PDO::FETCH_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Smart Notes – Résumés & QCM</title>
    <link rel="stylesheet" href="resume_qcm.css">
</head>
<body>
<button id="back-home" class="small-button" style="position:absolute;top:18px;right:18px;z-index:20;">🏠 Accueil</button></html>
<div class="page-container">
    <aside class="modules-list">
        <h3>Résumés</h3>
        <ul>
            <?php if (!empty($resumes)): ?>
                <?php foreach ($resumes as $r): ?>
                    <li class="resume-item" data-titre="<?= htmlspecialchars($r['titre']) ?>">
                        <?= htmlspecialchars($r['titre']) ?>
                    </li>
                <?php endforeach; ?>
            <?php else: ?>
                <li style="color: #666;">Aucun résumé disponible</li>
            <?php endif; ?>
        </ul>
    </aside>

    <div class="main-content">
        <div class="chat-container">
            <div class="chat-main" id="chat-main">
                <!-- plus de message initial -->
            </div>

            <form class="chat-input-bar" id="chat-form">
                <input type="text" id="ia-input" placeholder="Pose ta question à l’IA…" required>
                <button type="submit">Envoyer</button>
            </form>

            <div class="centered">
                <button id="save-btn" class="small-button">💾 Sauvegarder ce résumé IA</button>
            </div>
        </div>
    </div>
</div>
<script src="resume_qcm.js"></script>

</body>

</html>