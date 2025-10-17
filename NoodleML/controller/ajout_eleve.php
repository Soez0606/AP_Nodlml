<?php
require_once '../model/BDD.php';

use model\BDD;


$add = BDD::ajouter($_POST['email'], $_POST['nom'], $_POST['prenom'], $_POST['classe_id'],$_POST['role']);
header('Location: dashboard.php');

?>