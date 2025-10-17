<?php
require_once '../model/BDD.php';

use model\BDD;


$add = BDD::reinitialisationMotDePasse($_POST['user_id']);
header('Location: dashboard.php');

?>