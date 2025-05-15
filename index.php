<?php
require_once 'script-php/sessioncookies.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title data-i18n="home_title"></title>
</head>
<script src="js/lang.js"></script>
<body>
<section id="navbar-container">
    <div id="acceuil">

            <img src="image/logo.png" alt="Logo" style="height: 40px;">

    </div>
    <div id="menu">
        <div id="login">
            <?php if ($isLoggedIn): ?>
                <a href="script-php/logout.php" data-i18n="sign_out"></a>
            <?php else: ?>
                <a href="pages/login.html" data-i18n="login"></a>
            <?php endif; ?>
        </div>
        <div id="mode">
            <a href="#" onclick="toggleAbout()" data-i18n="about_me"></a>
        </div>
        <div id="langue">
            <select id="lang-select">
                <option value="fr">Français</option>
                <option value="en">English</option>
                <option value="ar">العربية</option>
                <option value="es">Español</option>
                <option value="de">Deutsch</option>
            </select>
        </div>
    </div>
</section>
<br><br><br>
<section>
    <section>
        <div id="container">
            <div id="container1">
                <h1 data-i18n="<?= $isLoggedIn ? 'welcome_user' : 'welcome_guest' ?>"
                    <?php if ($isLoggedIn): ?>data-i18n-vars='{"username": "<?= htmlspecialchars($_SESSION['username']) ?>"}'<?php endif; ?>>
                </h1>
                <p data-i18n="explore"></p>
                <?php if ($isLoggedIn): ?>
                    <button id="openModalBtn" class="btn-style" data-i18n="add_module"></button>
                <?php else: ?>
                    <p style="color: red;" data-i18n="must_login_to_add"></p>
                <?php endif; ?>
            </div>
        </div>
    </section>
</section>
<?php if ($isLoggedIn): ?>
    <div id="modal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2 data-i18n="add_module"></h2>
            <label data-i18n="module_name_label"></label>
            <input type="text" id="module-name" data-i18n-ph="module_placeholder">
            <label data-i18n="module_description_label"></label>
            <textarea id="module-description" data-i18n-ph="description_placeholder"></textarea>
            <div class="modal-buttons">
                <button id="cancelBtn" data-i18n="cancel"></button>
                <button id="addBtn" data-i18n="add"></button>
            </div>
        </div>
    </div>
<?php endif; ?>
<div id="modules-list"></div>
<script>
    const isLoggedIn = <?= json_encode($isLoggedIn) ?>;
</script>
<script src="js/script.js"></script>
<div id="aboutMe" style="margin-top: 190px;">
    <div class="about-container">
        <div class="about-text">
            <h2 data-i18n="about_title"></h2>
            <p><strong data-i18n="fullname_label"></strong> <span data-i18n="fullname_value"></span></p>
            <p><strong data-i18n="studies_label"></strong> <span data-i18n="studies_value"></span></p>
            <p><strong data-i18n="passions_label"></strong> <span data-i18n="passions_value"></span></p>
            <p><strong data-i18n="project_label"></strong> <span data-i18n="project_value"></span></p>
        </div>
        <div class="about-image">
            <img src="image/me.png" alt="Houssam">
        </div>
    </div>
    <div class="about-links">
        <a href="https://www.linkedin.com/in/el-bouamraoui-houssam-0569a1326" target="_blank" title="LinkedIn">
            <img src="image/linkedin.png" alt="LinkedIn">
        </a>
        <a href="https://www.github.com/HoussamElBouamraoui" target="_blank" title="GitHub">
            <img src="image/github.png" alt="GitHub">
        </a>
    </div>
</div>
</body>
</html>