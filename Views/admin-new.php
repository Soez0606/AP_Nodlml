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
                        
                    </ul>
                </div>
            </aside>
        </div>
    </body>
</html>