<?php
require_once '../model/BDD.php';

use model\BDD;


$add = BDD::supprimerEtablissement($_POST['etab_id']);
header('Location: dashboard.php');

?>