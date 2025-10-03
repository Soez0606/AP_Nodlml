<?php
require_once '../Model/BDD.php';
use Model\BDD;
$user = BDD::login($_POST['email'] ?? '', $_POST['mot_de_passe'] ?? '');
if ($user) {
    session_start();
    $_SESSION['user'] = $user['login'];
    header('Location: compte.php');
} else {
    header('Location: index.php');
}
?>