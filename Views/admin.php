<?php

require_once('../Models/BDD.php');

session_start();
use NoodleML\Models\BDD;

$db = new BDD();

if (!isset($_SESSION['user']) || !isset($_SESSION['role']) || $_SESSION['role'] === 'eleve') {
    header("Location: /login");
    exit();
}

$selectedEtab=$_GET['etab_num'] ?? null;
$selectedClasse=$_GET['classe_id'] ?? null;
$eleves = $db->getEleveByClasse($selectedClasse);
$userNom = $_SESSION['user'];
$userRole = $_SESSION['role'];
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link rel="stylesheet" href="content/css/styles-dashboard.css">
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
                    <?php foreach (array_keys($eleves) as $etab): ?>
                        <li class="<?php echo ($selectedEtab == $etab) ? 'active' : ''; ?>">
                            <a href="?etab_num=<?php echo $etab; ?>">
                                <?php echo htmlspecialchars($db->getEtablissementsNomByProf($etab)); ?>
                            </a>
                            <form action="NoodleML/controller/supprimer_etablissement.php" method="post" class="inline-form">
                                <input type="hidden" name="etab_num" value="<?php echo $etab; ?>">
                                <button type="submit" class="delete-btn" title="Supprimer">🗑️</button>
                            </form>
                        </li>
                    <?php endforeach; ?>
                </ul>
                <form action="NoodleML/controller/ajout_etablissement.php" method="post" class="add-form">
                    <input type="text" name="nom_etablissement" placeholder="Nom de l'établissement" required>
                    <button type="submit">Ajouter</button>
                </form>
            </div>
            <button style="background-color: aqua;"><a href="/">Retour</a></button>
        </aside>
        <!-- === Main (Droite) === -->
        <main class="main-content">
            <?php if ($selectedEtab): ?>
                <section class="classes-section">
                    <h2>Classes</h2>
                    <ul class="list">
                        <?php foreach (array_keys($eleves[$selectedEtab]) as $classe): ?>
                            <li class="<?php echo ($selectedClasse == $classe) ? 'active' : ''; ?>">
                                <a href="?etab_num=<?php echo $selectedEtab; ?>&classe_id=<?php echo $classe; ?>">
                                    <?php echo htmlspecialchars($db->getClassesNomByEtablissement($classe)); ?>
                                </a>
                                <form action="NoodleML/controller/supprimer_classe.php" method="post" class="inline-form">
                                    <input type="hidden" name="classe_id" value="<?php echo $classe; ?>">
                                    <button type="submit" class="delete-btn" title="Supprimer">🗑️</button>
                                </form>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                    <form action="NoodleML/controller/ajout_classe.php" method="post" class="add-form">
                        <input type="hidden" name="etab_num" value="<?php echo $selectedEtab; ?>">
                        <input type="hidden" name="email_prof" value="<?php echo $userNom; ?>">
                        <input type="number" name="chap_dispo" value="5" >
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
                                        <form action="NoodleML/controller/reset_password.php" method="post" class="inline-form">
                                            <input type="hidden" name="user_email" value="<?php echo $eleve['email']; ?>">
                                            <button type="submit" name="action" value="reset"
                                                title="Réinitialiser mot de passe">🔑</button>
                                        </form>
                                        <form action="NoodleML/controller/supprimer_utilisateur.php" method="post" class="inline-form">
                                            <input type="hidden" name="user_email" value="<?php echo $eleve['email']; ?>">
                                            <button type="submit" name="action" value="delete" title="Supprimer">🗑️</button>
                                        </form>
                                        <form action="NoodleML/controller/ajout_eleve.php" method="post" class="add-form">
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
