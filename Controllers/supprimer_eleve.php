<?php
require_once '../Model/BDD.php';

use NoodleML\Models\BDD;

$db = new BDD();

$db->supprimerEleve($_POST['user_id'] ?? null);
header('Location: ../../dashboard.php');

?>