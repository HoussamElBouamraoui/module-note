<?php
// Connexion directe à la base de données
$conn = new mysqli("localhost", "root", "", "smart_notes");

if ($conn->connect_error) {
    die("Erreur de connexion : " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (!empty($username) && !empty($password)) {
        // Vérifie si l'étudiant existe déjà
        $stmt = $conn->prepare("SELECT * FROM student WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            echo "❌ Ce nom d'utilisateur existe déjà.";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO student (username, password) VALUES (?, ?)");
            $stmt->bind_param("ss", $username, $hashed_password);

            if ($stmt->execute()) {
                echo "✅ Inscription réussie ! <a href='../index.html'>Se connecter</a>";
            } else {
                echo "❌ Erreur SQL : " . $stmt->error;
            }
        }
    } else {
        echo "❌ Tous les champs sont obligatoires.";
    }
}

$conn->close();

