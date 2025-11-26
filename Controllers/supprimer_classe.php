<?php
require_once '../Model/BDD.php';

use NoodleML\Models\BDD;

$db = new BDD();


$db->supprimerClasse($_POST['classe_id']);
header('Location: /admin');

?>