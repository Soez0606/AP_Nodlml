<?php
require_once '../model/BDD.php';

use model\BDD;


$add = BDD::ajouterEtablissement($_POST['nom_etablissement']);
header('Location: dashboard.php');

?>