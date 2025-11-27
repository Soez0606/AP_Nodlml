<?php
session_start();

require_once '../Models/BDD.php';

use NoodleML\Models\BDD;

$db = new BDD();

if (!isset($_SESSION['user']) || !isset($_SESSION['role']) || $_SESSION['role'] === 'eleve') {
    header('Location: /login');
    exit();
}

$selectedEtab = $_GET['etab_num'] ?? null;
$selectedClasse=$_GET['classe_id'] ?? null;
$eleves = $db->getEleveByClasse($selectedClasse);
$userNom = $_SESSION['user'];
$userRole = $_SESSION['role'];
$etabs = $db->getEtablissementsNomByProf($userNom);

?>

<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <title>Dashboard – NoodleML</title>

        <!-- Meta SEO -->
        <meta name="description"
            content="Découvrez l’approche pédagogique et didactique de NoodleML, en lien avec le BTS SIO SLAM. Un framework IA clair, visuel et accessible.">
        <meta name="keywords"
            content="pédagogie, didactique, BTS SIO, SLAM, IA, intelligence artificielle, enseignement, accessibilité, voix synthèse, NoodleML, framework, éducation">
        <meta name="author" content="Sébastien Marchand">

        <!-- Responsive -->
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <!-- Open Graph pour réseaux sociaux -->
        <meta property="og:title" content="NoodleML - Approche pédagogique et didactique">
        <meta property="og:description"
            content="Découvrez l’approche pédagogique et didactique de NoodleML, en lien avec le BTS SIO SLAM. Un framework IA clair, visuel et accessible.">
        <meta property="og:image" content="https://noodleml.com/resources/classe-ia.jpg">
        <meta property="og:url" content="https://noodleml.com/approche-pedagogique-et-didactique.html">
        <meta property="og:type" content="website">

        <!-- Twitter Card (facultatif) -->
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:title" content="NoodleML - Approche pédagogique et didactique">
        <meta name="twitter:description"
            content="Découvrez l’approche pédagogique et didactique de NoodleML, en lien avec le BTS SIO SLAM. Un framework IA clair, visuel et accessible.">
        <meta name="twitter:image" content="https://noodleml.com/resources/classe-ia.jpg">

        <!-- Feuille de style -->
        <!-- <link rel="stylesheet" href="content/css/style-noodleml.css"> -->
        <link rel="icon" href="content/resources/NoodleML.png" type="image/png">
        <link rel="stylesheet" href="content/css/styles-dashboard.css">
    </head>

    <body>
        <div class="container">
            <aside class="sidebar">
                <div class="user-info">
                    <h2><?php echo htmlspecialchars($userNom); ?></h2>
                    <p class="role"><?php echo htmlspecialchars($userRole); ?></p>
                </div>
                <div class="section">
                    <h3>Établissements</h3>
                    <ul class="list">
                        <?php foreach ($etabs as $etab): ?>
                            <li class="<?php echo ($selectedEtab == $etab['id']) ? 'active' : null; ?>">
                                <a href="?etab_num<?php echo $etab['id']; ?>">
                                    <?php echo htmlspecialchars($etab['nom']); ?>
                                </a>
                                <form action="../Controllers/supprimer_etablissement.php" method="post" class="inline-form">
                                    <input type="hidden" name="etab_num" value="<?php echo $etab; ?>">
                                    <button type="submit" class="delete-btn" title="Supprimer">🗑️</button>
                                </form>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                    <form action="../Controllers/ajout_etablissement.php" method="post" class="add-form">
                        <input type="text" name="nom_etablissement" placeholder="Nom de l'établissement" required>
                        <button type="submit">Ajouter</button>
                    </form>
                </div>
                 <button style="background-color: aqua;"><a href="/">Retour</a></button>
            </aside>
            <main class="main-content">
                <?php if ($selectedEtab): ?>
                    <section class="classes-section">
                        <h2>Classes</h2>
                        <ul class="list">
                            <?php foreach (array_values($eleves['classe_id']) as $classe): ?>
                                <li class="<?php echo ($selectedClasse == $classe) ? 'active' : ''; ?>">
                                    <a href="?etab_num <?php echo $selectedEtab; ?>&classe_id=<?php echo $classe; ?>">
                                        <?php echo htmlspecialchars($db->getClassesNomByEtablissement($selectedEtab)); ?>
                                    </a>
                                    <form action="../Controllers/supprimer_classe" method="post" class="inline-form">
                                        <input type="hidden" name="classe_id" value="<?php echo $classe; ?>">
                                        <button type="submit" class="delete-btn" title="Supprimer">🗑️</button>
                                    </form>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                        <form action="../Controllers/ajout_classe" method="post" class="add-form">
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