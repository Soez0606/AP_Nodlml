<?php
require_once '../model/BDD.php';

use model\BDD;

$add = BDD::supprimer($_POST['user_id'] ?? null);
header('Location: dashboard.php');

?>