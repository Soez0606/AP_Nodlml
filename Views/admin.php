<?php
session_start();

require_once __DIR__ . '/../Models/BDD.php';
use NoodleML\Models\BDD;

$db = new BDD();

// Protection d'accès : seul un utilisateur connecté et non "eleve" peut accéder
if (!isset($_SESSION['user']) || !isset($_SESSION['role']) || $_SESSION['role'] === 'eleve') {
    header('Location: /login');
    exit();
}

// Récup GET
$selectedEtab = isset($_GET['etab_num']) ? intval($_GET['etab_num']) : null;
$selectedClasse = isset($_GET['classe_id']) ? intval($_GET['classe_id']) : null;

$userNom = $_SESSION['user'];   // ici on suppose que c'est l'email du prof
$userRole = $_SESSION['role'];

$etabs = $db->getEtablissementsNomByProf($userNom);

// Si établissement sélectionné : récupérer les classes
$classes = $selectedEtab ? $db->getClassesByEtablissement($selectedEtab) : [];

// Si classe sélectionnée : récupérer les élèves
$eleves = $selectedClasse ? $db->getEleveByClasse($selectedClasse) : [];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Dashboard – NoodleML</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="/content/resources/NoodleML.png" type="image/png">
    <link rel="stylesheet" href="/content/css/styles-dashboard.css">
</head>
<body>
<div class="container">
    <aside class="sidebar">
        <div class="user-info">
            <h2><?= htmlspecialchars($userNom); ?></h2>
            <p class="role"><?= htmlspecialchars($userRole); ?></p>
        </div>

        <div class="section">
            <h3>Établissements</h3>
            <ul class="list">
                <?php if (!empty($etabs)): ?>
                    <?php foreach ($etabs as $etab): ?>
                        <li class="<?= ($selectedEtab == $etab['id']) ? 'active' : ''; ?>">
                            <a href="?etab_num=<?= intval($etab['id']); ?>">
                                <?= htmlspecialchars($etab['nom']); ?>
                            </a>

                            <form action="../Controllers/supprimer_etablissement.php" method="post" class="inline-form" onsubmit="return confirm('Supprimer cet établissement ?');">
                                <input type="hidden" name="etab_num" value="<?= intval($etab['id']); ?>">
                                <button type="submit" class="delete-btn" title="Supprimer">🗑️</button>
                            </form>
                        </li>
                    <?php endforeach; ?>
                <?php else: ?>
                    <li>Aucun établissement trouvé.</li>
                <?php endif; ?>
            </ul>

            <form action="../Controllers/ajout_etablissement.php" method="post" class="add-form">
                <input type="text" name="nom_etablissement" placeholder="Nom de l'établissement" required>
                <button type="submit">Ajouter</button>
            </form>
        </div>

        <div style="margin-top: 1rem;">
            <a href="/" class="btn">Retour</a>
        </div>
    </aside>

    <main class="main-content">
        <?php if ($selectedEtab): ?>
            <section class="classes-section">
                <h2>Classes (Établissement #<?= htmlspecialchars($selectedEtab); ?>)</h2>

                <?php if (!empty($classes)): ?>
                    <ul class="list">
                        <?php foreach ($classes as $classe): ?>
                            <li class="<?= ($selectedClasse == $classe['id']) ? 'active' : ''; ?>">
                                <a href="?etab_num=<?= intval($selectedEtab); ?>&classe_id=<?= intval($classe['id']); ?>">
                                    <?= htmlspecialchars($classe['nom']); ?>
                                </a>

                                <form action="../Controllers/supprimer_classe.php" method="post" class="inline-form" onsubmit="return confirm('Supprimer cette classe ?');">
                                    <input type="hidden" name="classe_id" value="<?= intval($classe['id']); ?>">
                                    <button type="submit" class="delete-btn" title="Supprimer">🗑️</button>
                                </form>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p>Aucune classe pour cet établissement.</p>
                <?php endif; ?>

                <form action="../Controllers/ajout_classe.php" method="post" class="add-form">
                    <input type="hidden" name="etab_num" value="<?= intval($selectedEtab); ?>">
                    <input type="hidden" name="email_prof" value="<?= htmlspecialchars($userNom); ?>">
                    <input type="number" name="chap_dispo" value="5" min="0">
                    <input type="text" name="nom_classe" placeholder="Nom de la classe" required>
                    <button type="submit">Ajouter</button>
                </form>
            </section>

            <?php if ($selectedClasse): ?>
                <section class="eleves-section">
                    <h3>Élèves (Classe #<?= htmlspecialchars($selectedClasse); ?>)</h3>

                    <?php if (!empty($eleves)): ?>
                        <ul class="list">
                            <?php foreach ($eleves as $eleve): ?>
                                <li>
                                    <span><?= htmlspecialchars($eleve['nom'] . ' ' . $eleve['prenom']); ?> — <?= htmlspecialchars($eleve['email']); ?></span>

                                    <div class="actions">
                                        <form action="../Controllers/reset_password.php" method="post" class="inline-form" onsubmit="return confirm('Réinitialiser le mot de passe de <?= htmlspecialchars($eleve['email']); ?> ?');">
                                            <input type="hidden" name="user_email" value="<?= htmlspecialchars($eleve['email']); ?>">
                                            <button type="submit" title="Réinitialiser mot de passe">🔑</button>
                                        </form>

                                        <form action="../Controllers/supprimer_utilisateur.php" method="post" class="inline-form" onsubmit="return confirm('Supprimer l\'élève <?= htmlspecialchars($eleve['email']); ?> ?');">
                                            <input type="hidden" name="user_email" value="<?= htmlspecialchars($eleve['email']); ?>">
                                            <button type="submit" title="Supprimer">🗑️</button>
                                        </form>
                                    </div>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        <p>Aucun élève dans cette classe.</p>
                    <?php endif; ?>

                    <form action="../Controllers/ajout_eleve.php" method="post" class="add-form">
                        <input type="hidden" name="classe_id" value="<?= intval($selectedClasse); ?>">
                        <input type="hidden" name="role" value="eleve">
                        <input type="email" name="email" placeholder="Email de l'élève" required>
                        <input type="text" name="nom" placeholder="Nom (optionnel)">
                        <input type="text" name="prenom" placeholder="Prénom (optionnel)">
                        <button type="submit">Ajouter Élève</button>
                    </form>
                </section>
            <?php endif; ?>

        <?php else: ?>
            <p class="placeholder">Sélectionnez un établissement pour voir les classes.</p>
        <?php endif; ?>
    </main>
</div>
</body>
</html>