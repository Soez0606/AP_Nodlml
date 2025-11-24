<?php
require_once '../Model/BDD.php';

use model\BDD;


BDD::reinitialisationMotDePasse($_POST['user_id']);
header('Location: ../../dashboard.php');

?>