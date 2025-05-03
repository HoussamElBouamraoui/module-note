<?php
global $pdo;

session_start();
require_once '../base_donne/connexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (!empty($username) && !empty($password)) {
        // Vérification admin (mot de passe non chiffré)
        $stmt = $pdo->prepare("SELECT * FROM admin WHERE username = ?");
        $stmt->execute([$username]);
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($admin && $password === $admin['password']) {
            $_SESSION['user_id'] = $admin['id'];
            $_SESSION['username'] = $admin['username'];
            // Pas besoin de $_SESSION['id_student'] pour l'admin

            if (isset($_POST['remember'])) {
                setcookie("admin_id", $admin['id'], [
                    'expires' => time() + (30 * 24 * 60 * 60),
                    'path' => '/',
                    'secure' => isset($_SERVER['HTTPS']),
                    'httponly' => true,
                    'samesite' => 'Lax'
                ]);
            }
            header("Location: ../admin/admin_student.php");
            exit();
        }

        // Vérification étudiant (mot de passe chiffré)
        $stmt = $pdo->prepare("SELECT * FROM student WHERE username = ?");
        $stmt->execute([$username]);
        $student = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($student && password_verify($password, $student['password'])) {
            $_SESSION['user_id'] = $student['id'];
            $_SESSION['username'] = $student['username'];
            $_SESSION['id_student'] = $student['id']; // AJOUT OBLIGATOIRE

            setcookie("user_id", $student['id'], [
                'expires' => time() + (30 * 24 * 60 * 60),
                'path' => '/',
                'secure' => isset($_SERVER['HTTPS']),
                'httponly' => true,
                'samesite' => 'Lax'
            ]);

            header("Location: ../index.php");
            exit();
        }

        header("Location: ../pages/login.html?error=1");
        exit();
    } else {
        header("Location: ../pages/login.html?error=2");
        exit();
    }
}
?>