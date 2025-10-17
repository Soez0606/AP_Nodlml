<?php
require_once '../model/BDD.php';

use model\BDD;


$add = BDD::ajouterClasse($_POST['nom_classe'], $_POST['email_prof'], $_POST['etab_id'], $_POST['chap_dispo']);
header('Location: dashboard.php');

?>