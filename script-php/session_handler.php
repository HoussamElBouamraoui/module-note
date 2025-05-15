<?php
// Vérifiez si une session est déjà active
if (session_status() === PHP_SESSION_NONE) {
    // Définit la durée de vie des sessions à 30 minutes (1800 secondes)
    ini_set('session.gc_maxlifetime', 1800);

    // Connexion à la base de données
    require_once __DIR__ . '/../base_donne/connexion.php';

    // Fonction pour ouvrir une session
    function openSession($savePath, $sessionName) {
        return true;
    }

    // Fonction pour fermer une session
    function closeSession() {
        return true;
    }

    // Fonction pour lire une session
    function readSession($id) {
        global $pdo;
        $stmt = $pdo->prepare("SELECT session_data FROM sessions WHERE session_id = :id");
        $stmt->execute(['id' => $id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? $result['session_data'] : '';
    }

    // Fonction pour écrire une session
    function writeSession($id, $data) {
        global $pdo;
        $stmt = $pdo->prepare("REPLACE INTO sessions (session_id, session_data, last_access) VALUES (:id, :data, NOW())");
        return $stmt->execute(['id' => $id, 'data' => $data]);
    }

    // Fonction pour détruire une session
    function destroySession($id) {
        global $pdo;
        $stmt = $pdo->prepare("DELETE FROM sessions WHERE session_id = :id");
        return $stmt->execute(['id' => $id]);
    }

    // Fonction pour nettoyer les sessions expirées
    function gcSession($maxLifetime) {
        global $pdo;
        $stmt = $pdo->prepare("DELETE FROM sessions WHERE last_access < NOW() - INTERVAL :maxlifetime SECOND");
        return $stmt->execute(['maxlifetime' => $maxLifetime]);
    }

    // Définir les gestionnaires de session personnalisés
    session_set_save_handler(
        "openSession",
        "closeSession",
        "readSession",
        "writeSession",
        "destroySession",
        "gcSession"
    );

    // Démarrer la session
    session_start();
}
?>