<?php
require_once '../Model/BDD.php';

use model\BDD;


BDD::supprimerEtablissement($_POST['etab_num']);
header('Location: ../../dashboard.php');

?>