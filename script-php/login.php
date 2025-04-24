<?php
session_start();

// Connexion à la base de données
$conn = new mysqli("localhost", "root", "", "smart_notes");

if ($conn->connect_error) {
    die("Erreur de connexion : " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (!empty($username) && !empty($password)) {
        // Vérifier dans la table admin
        $stmt = $conn->prepare("SELECT * FROM admin WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $resultAdmin = $stmt->get_result();

        if ($resultAdmin->num_rows === 1) {
            $admin = $resultAdmin->fetch_assoc();
            if ($password === $admin['password']) {

                $_SESSION['user_id'] = $admin['id'];
                $_SESSION['username'] = $admin['username'];
                $_SESSION['role'] = 'admin';
                header("Location: ../index.php"); // ← rediriger vers index.html
                exit();
            } else {
                echo "❌ Mot de passe incorrect (admin).";
                exit();
            }
        }

        // Sinon vérifier dans student
        $stmt = $conn->prepare("SELECT * FROM student WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $resultStudent = $stmt->get_result();

        if ($resultStudent->num_rows === 1) {
            $student = $resultStudent->fetch_assoc();
            if (password_verify($password, $student['password'])) {
                $_SESSION['user_id'] = $student['id'];
                $_SESSION['username'] = $student['username'];
                $_SESSION['role'] = 'student';
                header("Location: ../index.php"); // ← rediriger aussi vers index.html
                exit();
            } else {
                echo "❌ Mot de passe incorrect (étudiant).";
                exit();
            }
        }

        echo "❌ Aucun compte trouvé.";
    } else {
        echo "❌ Tous les champs sont obligatoires.";
    }
}

$conn->close();

