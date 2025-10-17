<?php
require_once '../Model/BDD.php';

use model\BDD;


BDD::ajouterEtablissement($_POST['nom_etablissement']);
header('Location: ../../dashboard.php');

?>