<?php
require_once '../Model/BDD.php';
use Model\BDD;
$user = BDD::login($_POST['email'] ?? '', $_POST['mot_de_passe'] ?? '');
if ($user) {
    echo "Connexion réussie";
    session_start();
    $_SESSION['user'] = $user['email'];
    //header('Location: compte.php');
} else {
    echo "Échec de la connexion";
    //header('Location: ../../index.html');
}
?>