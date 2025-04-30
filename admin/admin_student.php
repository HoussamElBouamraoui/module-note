<?php
require_once '../script-php/session_handler.php';

if (!isset($_SESSION['username']) || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header("Location: ../index.php");
    exit();
}
require_once '../base_donne/connexion.php';
global $pdo;
$message = "";

// Ajout étudiant
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add'])) {
    $username = trim($_POST['username']);
    $password = isset($_POST['password']) ? trim($_POST['password']) : '';
    if (!empty($username) && !empty($password)) {
        $stmt = $pdo->prepare("SELECT * FROM student WHERE username = ?");
        $stmt->execute([$username]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($result) {
            $message = "❌ Ce nom d'utilisateur existe déjà.";
        } else {
            $id_admin = $_SESSION['user_id']; // récupère l'id de l'admin connecté
            $stmt = $pdo->prepare("INSERT INTO student (username, password, id_admin) VALUES (?, ?, ?)");
            if ($stmt->execute([$username, password_hash($password, PASSWORD_DEFAULT), $id_admin])) {
                $message = "✅ Étudiant ajouté avec succès.";
            } else {
                $message = "❌ Erreur lors de l'ajout.";
            }
        }
    } else {
        $message = "❌ Tous les champs sont obligatoires.";
    }
}

// Suppression
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM student WHERE id = ?");
    $stmt->execute([$id]);
    header("Location: admin_student.php");
    exit();
}

// Modification
if (isset($_POST['edit'])) {
    $id = $_POST['id'];
    $nouveauUsername = trim($_POST['nouveauUsername']);
    $nouveauPassword = trim($_POST['nouveauPassword']);
    if (!empty($nouveauUsername)) {
        if (!empty($nouveauPassword)) {
            $stmt = $pdo->prepare("UPDATE student SET username = ?, password = ? WHERE id = ?");
            $stmt->execute([$nouveauUsername, password_hash($nouveauPassword, PASSWORD_DEFAULT), $id]);
        } else {
            $stmt = $pdo->prepare("UPDATE student SET username = ? WHERE id = ?");
            $stmt->execute([$nouveauUsername, $id]);
        }
        $message = "✅ Étudiant modifié.";
        header("Location: admin_student.php");
        exit();
    } else {
        $message = "❌ Le nom d'utilisateur ne peut pas être vide.";
    }
}

// Récupération des étudiants
$stmt = $pdo->query("SELECT student.*, admin.id AS id_admin FROM student LEFT JOIN admin ON student.id_admin = admin.id");
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="styleadmin.css" rel="stylesheet" type="text/css">
    <title>Gestion des étudiants</title>
</head>
<body>
<div class="container">
    <header>
        <h1>Espace Administrateur</h1>
        <div class="admin-info">
            <span>Bonjour, <strong><?= htmlspecialchars($_SESSION['username']) ?></strong> </span>
            <a href="../script-php/logout.php" class="logout-link">Déconnexion</a>
        </div>
    </header>

    <?php if ($message): ?>
        <div class="alert-success"><?= $message ?></div>
    <?php endif; ?>

    <section class="add-student-section">
        <h2>➕ Ajouter un étudiant</h2>
        <form method="POST" class="add-student-form">
            <div class="form-group">
                <input type="text" name="username" class="form-control" placeholder="Nom d'utilisateur" required>
            </div>
            <div class="form-group">
                <input type="password" name="password" class="form-control" placeholder="Mot de passe" required>
            </div>
            <div class="form-group">
                <button type="submit" name="add" class="btn-custom">Ajouter</button>
            </div>
        </form>
    </section>

    <section class="student-list-section">
        <h2>📋 Liste des étudiants</h2>
        <table class="student-table">
            <thead>
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Admin créateur</th>
                <th class="actions-col">Actions</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($result as $row): ?>
                <tr>
                    <td><?= htmlspecialchars($row['id']) ?></td>
                    <td><?= htmlspecialchars($row['username']) ?></td>
                    <td><?= htmlspecialchars($row['id_admin'] ?? '—') ?></td>
                    <td>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="id" value="<?= $row['id'] ?>">
                            <input type="text" name="nouveauUsername" placeholder="Nouveau nom" value="<?= htmlspecialchars($row['username']) ?>" required class="form-control" style="width:120px;display:inline;">
                            <input type="password" name="nouveauPassword" placeholder="Nouveau mot de passe" class="form-control" style="width:120px;display:inline;">
                            <button type="submit" name="edit" class="btn-custom btn-action">Modifier</button>
                            <a href="admin_student.php?delete=<?= $row['id'] ?>" onclick="return confirm('Supprimer cet étudiant ?');" class="btn-custom btn-action btn-delete">Supprimer</a>
                        </form>

                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </section>
</div>
</body>
</html>