<?php
require_once '../model/BDD.php';

use model\BDD;

$add = BDD::supprimer($_POST['id'] ?? null);
header('Location: page_admin.php');

?>