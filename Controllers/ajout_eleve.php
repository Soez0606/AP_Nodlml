<?php
require_once '../Model/BDD.php';

use NoodleML\Models\BDD;

$db = new BDD();


$add = $db->addEleve($_POST['email'], $_POST['nom']?? null, $_POST['prenom']?? null, $_POST['classe_id']?? null,$_POST['role']);
header('Location: ../../dashboard.php');

?>