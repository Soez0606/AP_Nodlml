<?php
require_once '../Model/BDD.php';

use NoodleML\Models\BDD;

$db = new BDD();


$db->ajouterEtablissement($_POST['nom_etablissement']);
header('Location: ../../dashboard.php');

?>