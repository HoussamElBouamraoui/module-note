<?php
session_start();
if (!isset($_SESSION['id_student'])) {
    header('Location: ../index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title data-i18n="observations_title"></title>
    <link rel="stylesheet" href="../css/styleobservation.css">
</head>
<script src="../js/lang.js"></script>
<body>
<header>
    <h1>
        <span data-i18n="observations_title"></span> - <span id="module-title"></span>
    </h1>
</header>
<main>
    <section id="obs-container">
        <h2 data-i18n="add_observation"></h2>
        <textarea id="observation-text" data-i18n-ph="observation_placeholder"></textarea>
        <div class="obs-buttons">
            <button id="add-observation-btn" data-i18n="add"></button>
            <button id="backtoindex-btn" onclick="window.location.href='../index.php'" data-i18n="back"></button>
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