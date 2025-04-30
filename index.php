<?php

require_once 'script-php/sessioncookies.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title>Accueil</title>
</head>
<body>
<section id="navbar-container">
    <div id="acceuil">
        <a href="index.php">Accueil</a>
    </div>
    <div id="menu">
        <div id="login">
            <?php if ($isLoggedIn): ?>
                <a href="script-php/logout.php">Sign out</a>
            <?php else: ?>
                <a href="pages/login.html">Login</a>
            <?php endif; ?>
        </div>

        <div id="mode">
            <a href="index.php">Mode</a>
        </div>
    </div>
</section>

<br><br><br>

<section>
    <div id="container">
        <div id="container1">

            <h1>
                <?php if ($isLoggedIn): ?>
                    Bienvenue <?php echo htmlspecialchars($_SESSION['username']); ?> !
                <?php else: ?>
                    Bienvenue 
                <?php endif; ?>
            </h1>
            <p>Nous sommes ravis de vous accueillir ici. Explorez nos fonctionnalités et profitez de votre visite.</p>
        </div>

        <?php if ($isLoggedIn): ?>
            <button id="openModalBtn" class="btn-style">Ajouter un module</button>
        <?php else: ?>
            <p style="color: red;">⚠️ Connecte-toi pour ajouter un module</p>
        <?php endif; ?>
    </div>
</section>

<!-- Modale -->
<?php if ($isLoggedIn): ?>
    <div id="modal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Ajouter un module</h2>
            <label>Nom du module</label>
            <input type="text" id="module-name" placeholder="SE, Réseaux, etc.">
            <label>Description </label>
            <textarea id="module-description" placeholder="Entrez une description "></textarea>
            <div class="modal-buttons">
                <button id="cancelBtn">Annuler</button>
                <button id="addBtn">Ajouter</button>
            </div>
        </div>
    </div>
<?php endif; ?>

<!-- Liste des modules -->
<div id="modules-list"></div>

<script>
    const isLoggedIn = <?= json_encode($isLoggedIn) ?>;
</script>
<script src="js/script.js"></script>
</body>
</html>
