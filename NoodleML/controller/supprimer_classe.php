<?php
require_once '../Model/BDD.php';

use model\BDD;


BDD::supprimerClasse($_POST['classe_id']);
header('Location: ../../dashboard.php');

?>