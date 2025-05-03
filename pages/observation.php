<?php
session_start();
if (!isset($_SESSION['id_student'])) {
    header('Location: ../index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Observations</title>
    <link rel="stylesheet" href="../css/styleobservation.css">
</head>
<body>
<header>
    <h1 id="module-title">Module</h1>
</header>
<main>
    <section id="obs-container">
        <h2>Ajouter une observation</h2>
        <textarea id="observation-text" placeholder="Écris ton observation ici..."></textarea>
        <div class="obs-buttons">
            <button id="add-observation-btn">Ajouter</button>
            <button id="backtoindex-btn" onclick="window.location.href='../index.php'">Retourner</button>
        </div>
        <div id="obs-list"></div>
    </section>
</main>
<script>
    window.studentId = <?php echo json_encode($_SESSION['id_student']); ?>;
</script>
<script src="../js/script-observation.js"></script>
</body>
</html>