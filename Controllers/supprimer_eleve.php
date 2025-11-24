<?php
require_once '../Model/BDD.php';

use model\BDD;

BDD::supprimer($_POST['user_id'] ?? null);
header('Location: ../../dashboard.php');

?>