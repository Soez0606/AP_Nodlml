<?php
require_once '../Model/BDD.php';

use model\BDD;


BDD::ajouterClasse($_POST['nom_classe'], $_POST['email_prof'], $_POST['etab_num'], $_POST['chap_dispo']?? 0);
header('Location: ../../dashboard.php');

?>