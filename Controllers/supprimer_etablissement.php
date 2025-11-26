<?php
require_once '../Model/BDD.php';

use NoodleML\Models\BDD;

$db = new BDD();


$db->supprimerEtablissement($_POST['etab_num']);
header('Location: /admin');

?>