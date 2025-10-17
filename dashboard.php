<?php

require_once 'NoodleML/Model/BDD.php';

session_start();
use Model\BDD;

if (!isset($_SESSION['user']) || !isset($_SESSION['role'])) {
    header("Location: login.php");
    exit();
}

$eleves = BDD::getEleve($_SESSION['user']);
$userNom = $_SESSION['user'];
$userRole = $_SESSION['role'];

?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link rel="stylesheet" href="css/styles-dashboard.css">
</head>

<body>
    <div class="container">
        <!-- === Sidebar (Gauche) === -->
        <aside class="sidebar">
            <div class="user-info">
                <h2><?php echo htmlspecialchars($userNom); ?></h2>
                <p class="role"><?php echo htmlspecialchars($userRole); ?></p>
            </div>
            <div class="section">
                <h3>Établissements</h3>
                <ul class="list">
                    <?php foreach ($etablissements as $etab): ?>
                        <li class="<?php echo ($selectedEtab == $etab['num']) ? 'active' : ''; ?>">
                            <a href="?etab_num=<?php echo $etab['num']; ?>">
                                <?php echo htmlspecialchars($etab['nom']); ?>
                            </a>
                            <form action="supprimer_etablissement.php" method="post" class="inline-form">
                                <input type="hidden" name="etab_num" value="<?php echo $etab['num']; ?>">
                                <button type="submit" class="delete-btn" title="Supprimer">🗑️</button>
                            </form>
                        </li>
                    <?php endforeach; ?>
                </ul>
                <form action="ajout_etablissement.php" method="post" class="add-form">
                    <input type="text" name="nom_etablissement" placeholder="Nom de l'établissement" required>
                    <button type="submit">Ajouter</button>
                </form>
            </div>
        </aside>
        <!-- === Main (Droite) === -->
        <main class="main-content">
            <?php if ($selectedEtab): ?>
                <section class="classes-section">
                    <h2>Classes</h2>
                    <ul class="list">
                        <?php foreach ($classes as $classe): ?>
                            <li class="<?php echo ($selectedClasse == $classe['id']) ? 'active' : ''; ?>">
                                <a href="?etab_num=<?php echo $selectedEtab; ?>&classe_id=<?php echo $classe['id']; ?>">
                                    <?php echo htmlspecialchars($classe['nom']); ?>
                                </a>
                                <form action="supprimer_classe.php" method="post" class="inline-form">
                                    <input type="hidden" name="classe_id" value="<?php echo $classe['id']; ?>">
                                    <button type="submit" class="delete-btn" title="Supprimer">🗑️</button>
                                </form>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                    <form action="ajout_classe.php" method="post" class="add-form">
                        <input type="hidden" name="etablissement_num" value="<?php echo $selectedEtab; ?>">
                        <input type="text" name="nom_classe" placeholder="Nom de la classe" required>
                        <button type="submit">Ajouter</button>
                    </form>
                </section>
                <?php if ($selectedClasse): ?>
                    <section class="eleves-section">
                        <h3>Élèves</h3>
                        <ul class="list">
                            <?php foreach ($eleves as $eleve): ?>
                                <li>
                                    <span><?php echo htmlspecialchars($eleve['nom']); ?>
                                        (<?php echo htmlspecialchars($eleve['email']); ?>)</span>
                                    <div class="actions">
                                        <form action="reset_password.php" method="post" class="inline-form">
                                            <input type="hidden" name="user_email" value="<?php echo $eleve['email']; ?>">
                                            <button type="submit" name="action" value="reset"
                                                title="Réinitialiser mot de passe">🔑</button>
                                        </form>
                                        <form action="supprimer_utilisateur.php" method="post" class="inline-form">
                                            <input type="hidden" name="user_email" value="<?php echo $eleve['email']; ?>">
                                            <button type="submit" name="action" value="delete" title="Supprimer">🗑️</button>
                                        </form>
                                        <form action="ajout_eleve.php" method="post" class="add-form">
                                            <input type="hidden" name="classe_id" value="<?php echo $selectedClasse; ?>">
                                            <input type="hidden" name="role" value="eleve">
                                            <input type="email" name="email" placeholder="Email de l'élève" required>
                                            <button type="submit">Ajouter Élève</button>
                                        </form>

                                    </div>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </section>
                <?php endif; ?>
            <?php else: ?>
                <p class="placeholder">Sélectionnez un établissement pour voir les classes.</p>
            <?php endif; ?>
        </main>
    </div>
</body>

</html>