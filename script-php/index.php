<?php
session_start();
$isLoggedIn = isset($_SESSION['username']);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Page d'accueil</title>
</head>
<body>

<nav>
    <ul>
        <li><a href="index.php">Accueil</a></li>

        <?php if ($isLoggedIn): ?>
            <li><a href="script-php/logout.php">Sign out</a></li>
            <li>Bonjour, <strong><?php echo $_SESSION['username']; ?></strong></li>
        <?php else: ?>
            <li><a href="pages/login.html">Login</a></li>
        <?php endif; ?>
    </ul>
</nav>

<h1>Bienvenue sur le site</h1>

</body>
</html>
