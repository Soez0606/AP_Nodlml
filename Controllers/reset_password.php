<?php
require_once '../Model/BDD.php';

use NoodleML\Models\BDD;

$db = new BDD();

$db->reinitialisationMdp($_POST['user_id']);
header('Location: /admin');

?>