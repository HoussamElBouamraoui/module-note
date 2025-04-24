<?php
session_start();

// Vérifier si l'admin est connecté
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}

// Connexion à la base
$conn = new mysqli("localhost", "root", "", "smart_notes");
if ($conn->connect_error) {
    die("Connexion échouée : " . $conn->connect_error);
}

// Ajout d’un étudiant
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add'])) {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = password_hash(trim($_POST['password']), PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO student (username, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $email, $password);
    $stmt->execute();
}

// Suppression
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM student WHERE id=$id");
}

// Récupération des étudiants
$result = $conn->query("SELECT * FROM student");
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Admin - Gérer les étudiants</title>
</head>
<body>
<h1>Espace Administrateur</h1>
<p>Bonjour, <?php echo $_SESSION['username']; ?> 👋</p>
<a href="logout.php">Déconnexion</a>

<h2>➕ Ajouter un étudiant</h2>
<form method="POST">
    <input type="text" name="username" placeholder="Nom d'utilisateur" required>
    <input type="email" name="email" placeholder="Email" required>
    <input type="password" name="password" placeholder="Mot de passe" required>
    <button type="submit" name="add">Ajouter</button>
</form>

<h2>📋 Liste des étudiants</h2>
<table border="1" cellpadding="10">
    <tr>
        <th>ID</th><th>Nom</th><th>Email</th><th>Actions</th>
    </tr>
    <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= $row['id'] ?></td>
            <td><?= $row['username'] ?></td>
            <td><?= $row['email'] ?></td>
            <td>
                <a href="edit_student.php?id=<?= $row['id'] ?>">Modifier</a> |
                <a href="admin_students.php?delete=<?= $row['id'] ?>" onclick="return confirm('Supprimer cet étudiant ?');">Supprimer</a>
            </td>
        </tr>
    <?php endwhile; ?>
</table>
</body>
</html>
