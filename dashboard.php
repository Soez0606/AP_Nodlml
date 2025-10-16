<?php
require_once 'NoodleML/Model/BDD.php';
use Model\BDD;
session_start();

if (!isset($_SESSION['email']) || !isset($_SESSION['role'])) {
    header("Location: login.php");
    exit();
}


$userRole = $_SESSION['role'];
$userEmail = $_SESSION['email'];
$eleves = BDD::getEleve($userEmail);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link rel="stylesheet" href="css/styles-dashboard.css">
</head>
<body>
    <h1>Bienvenue sur ton dashboard, <?php echo htmlspecialchars($_SESSION['nom']); ?> !</h1>

    <?php if ($userRole === 'Admin'): ?>
        <!-- Section Admin -->
        <div class="admin-section">
            <h2>Gestion des utilisateurs</h2>
            <!-- Ajout d'utilisateur -->
            <form action="ajout_utilisateur.php" method="post">
                <input type="email" name="email" placeholder="Adresse mail" required>
                <select name="role" required>
                    <option value="Prof">Professeur</option>
                    <option value="Eleve">Élève</option>
                </select>
                <button type="submit">Ajouter</button>
            </form>

            <!-- Réinitialisation de mot de passe et suppression -->
            <h3>Réinitialisation/Suppression</h3>
            <form action="reset_password.php" method="post">
                <input type="email" name="email" placeholder="Adresse mail" required>
                <button type="submit" name="action" value="reset">Réinitialiser</button>
                <button type="submit" name="action" value="delete">Supprimer</button>
            </form>

            <!-- Création de classe -->
            <h3>Création de classe</h3>
            <form action="creer_classe.php" method="post">
                <input type="text" name="nom_classe" placeholder="Nom de la classe" required>
                <button type="submit">Créer</button>
            </form>

            <!-- Accès aux chapitres par classe -->
            <h3>Accès aux chapitres</h3>
            <form action="chapitres_classe.php" method="post">
                <select name="classe_id" required>
                    <?php
                    $stmt = $db->query("SELECT id, nom FROM classes");
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        echo "<option value='{$row['id']}'>{$row['nom']}</option>";
                    }
                    ?>
                </select>
                <button type="submit">Voir les chapitres</button>
            </form>
        </div>

    <?php elseif ($userRole === 'Prof'): ?>
        <!-- Section Prof -->
        <div class="prof-section">
            <h2>Gestion des élèves et classes</h2>
            <!-- Ajout d'élève -->
            <form action="ajout_eleve.php" method="post">
                <input type="email" name="email" placeholder="Adresse mail de l'élève" required>
                <button type="submit">Ajouter élève</button>
            </form>

            <!-- Création d'établissement et de classe -->
            <h3>Création d'établissement et de classe</h3>
            <form action="creer_etablissement_classe.php" method="post">
                <input type="text" name="nom_etablissement" placeholder="Nom de l'établissement" required>
                <input type="text" name="nom_classe" placeholder="Nom de la classe" required>
                <button type="submit">Créer</button>
            </form>

            <!-- Accès aux chapitres par classe -->
            <h3>Accès aux chapitres</h3>
            <form action="chapitres_classe.php" method="post">
                <select name="classe_id" required>
                    <?php
                    $stmt = $db->query("SELECT id, nom FROM classes WHERE prof_id = $userId");
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        echo "<option value='{$row['id']}'>{$row['nom']}</option>";
                    }
                    ?>
                </select>
                <button type="submit">Voir les chapitres</button>
            </form>
        </div>
    <?php endif; ?>
</body>
</html>