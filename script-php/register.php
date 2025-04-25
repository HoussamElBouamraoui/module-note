<?php
require_once '../base_donne/connexion.php'; // Inclusion de la connexion PDO

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (!empty($username) && !empty($password)) {
        // Vérifie si l'étudiant existe déjà
        $stmt = $pdo->prepare("SELECT * FROM student WHERE username = ?");
        $stmt->execute([$username]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            echo "❌ Ce nom d'utilisateur existe déjà.";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO student (username, password) VALUES (?, ?)");
            if ($stmt->execute([$username, $hashed_password])) {
                header("Location: ../pages/login.html?success");
                exit();
            } else {
                echo "❌ Erreur SQL : " . implode(" ", $stmt->errorInfo());
            }
        }
    } else {
        echo "❌ Tous les champs sont obligatoires.";
    }
}