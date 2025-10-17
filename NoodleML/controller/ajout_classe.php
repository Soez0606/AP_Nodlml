<?php
require_once '../model/BDD.php';

use model\BDD;


BDD::ajouterClasse($_POST['nom_classe'], $_POST['email_prof'], $_POST['etab_id'], $_POST['chap_dispo']?? 0);
header('Location: dashboard.php');

?>