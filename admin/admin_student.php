<?php
session_start();

// Vérifier si l'admin est connecté
if (!isset($_SESSION['username'])) {
    header("Location: ../index.php");
    exit();
}

require_once '../base_donne/connexion.php';

$message = "";

// Ajout étudiant
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add'])) {
    $username = trim($_POST['username']);

    if (!empty($username)) {
        // Vérifie si l'étudiant existe déjà
        $stmt = $pdo->prepare("SELECT * FROM student WHERE username = ?");
        $stmt->execute([$username]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            $message = "❌ Ce nom d'utilisateur existe déjà.";
        } else {
            $stmt = $pdo->prepare("INSERT INTO student (username) VALUES (?)");
            if ($stmt->execute([$username])) {
                $message = "✅ Étudiant ajouté avec succès.";
            } else {
                $message = "❌ Erreur lors de l'ajout.";
            }
        }
    } else {
        $message = "❌ Le nom d'utilisateur est obligatoire.";
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

// Récupération des étudiants
$stmt = $pdo->query("SELECT * FROM student");
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des étudiants</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            background: linear-gradient(to right, #8400ff, #8400ff);
            color: black;
            padding: 40px;
        }
        .container {
            background-color: white;
            border-radius: 10px;
            padding: 30px;
        }
        h1, h2 {
            color: #8400ff;
        }
        table {
            background-color: #fff;
            color: #181818;
            border-radius: 8px;
            overflow: hidden;
        }
        .table-dark th {
            background-color: #8400ff !important;
            color: #fff !important;
        }
        .btn-custom {
            background-color: #8400ff;
            color: #fff;
            border: none;
        }
        .btn-custom:hover {
            background-color: #6c3483;
            color: #fff;
        }
        a {
            color: #8400ff;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
            color: #fff;
        }
        .form-control {
            background-color: #222;
            color: #fff;
            border: 1px solid #8400ff;
        }
        .form-control::placeholder {
            color: #bbb;
        }
        .alert-success {
            background-color: #8400ff;
            color: #fff;
            border: none;
        }
    </style>
</head>
<body>

<div class="container">
    <h1 class="mb-4">Espace Administrateur</h1>
    <p>Bonjour, <strong><?php echo $_SESSION['username']; ?></strong> 👋</p>
    <p><a href="logout.php">Déconnexion</a></p>

    <?php if ($message): ?>
        <div class="alert alert-success"><?= $message ?></div>
    <?php endif; ?>

    <h2>➕ Ajouter un étudiant</h2>
    <form method="POST" class="row g-3 mb-5">
        <div class="col-md-6">
            <input type="text" name="username" class="form-control" placeholder="Nom d'utilisateur" required>
        </div>
        <div class="col-md-12">
            <button type="submit" name="add" class="btn btn-custom">Ajouter</button>
        </div>
    </form>

    <h2>📋 Liste des étudiants</h2>
    <table class="table table-bordered table-hover text-center">
        <thead class="table-dark">
        <tr>
            <th>ID</th>
            <th>Nom</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($result as $row): ?>
            <tr>
                <td><?= htmlspecialchars($row['id']) ?></td>
                <td><?= htmlspecialchars($row['username']) ?></td>
                <td>
                    <a href="admin_student.php?delete=<?= $row['id'] ?>" onclick="return confirm('Supprimer cet étudiant ?');">Supprimer</a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

</body>
</html>