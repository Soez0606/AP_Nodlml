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
$etabsId = $etabs['id'];
$etabsNom = $etabs['nom'];

?>

<h1><?php var_dump($etabsId, $etabsNom) ?></h1>