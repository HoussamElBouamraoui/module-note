<?php
require_once 'session_handler.php';
require_once __DIR__ . '/../base_donne/connexion.php';
global $pdo;

$isLoggedIn = false;

function secureRedirect($url) {
    $currentUri = $_SERVER['REQUEST_URI'];
    $targetUri  = parse_url($url, PHP_URL_PATH);

    if ($currentUri === $targetUri || $currentUri === $targetUri . "/") return;

    if (!headers_sent()) {
        header("Location: $url");
        exit();
    }
}

// SESSION ADMIN
if (isset($_SESSION['username']) && isset($_SESSION['user_id']) && isset($_SESSION['is_admin']) && $_SESSION['is_admin']) {
    $isLoggedIn = true;
    if (strpos($_SERVER['REQUEST_URI'], '/admin/admin_student.php') === false) {
        secureRedirect('http://localhost/projetdevweb/admin/admin_student.php');
    }
}
// COOKIE ADMIN
elseif (!empty($_COOKIE['admin_id'])) {
    $stmt = $pdo->prepare("SELECT * FROM admin WHERE id = :id");
    $stmt->execute(['id' => intval($_COOKIE['admin_id'])]);
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($admin) {
        $_SESSION['user_id'] = $admin['id'];
        $_SESSION['username'] = $admin['username'];
        $_SESSION['is_admin'] = true;
        $isLoggedIn = true;
        if (strpos($_SERVER['REQUEST_URI'], '/admin/admin_student.php') === false) {
            secureRedirect('http://localhost/projetdevweb/admin/admin_student.php');
        }
    }
}

// SESSION/COOKIE ETUDIANT
if (!$isLoggedIn && !empty($_COOKIE['user_id'])) {
    $stmt = $pdo->prepare("SELECT * FROM student WHERE id = :id");
    $stmt->execute(['id' => intval($_COOKIE['user_id'])]);
    $student = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($student) {
        $_SESSION['user_id'] = $student['id'];
        $_SESSION['username'] = $student['username'];
        $_SESSION['is_admin'] = false;
        $_SESSION['id_student'] = $student['id'];
        $isLoggedIn = true;

        // Redirige uniquement si sur index ou login
        $uri = $_SERVER['REQUEST_URI'];
        if (strpos($uri, '/index.php') !== false || strpos($uri, '/login.php') !== false) {
            secureRedirect('http://localhost/projetdevweb/index.php');
        }
        // Sinon, PAS de redirection !
    }
}
?>