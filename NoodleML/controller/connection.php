<?php
require_once '../Model/BDD.php';
use Model\BDD;
$user = BDD::login($_POST['email'] ?? '', $_POST['password'] ?? '');
if ($user) {
    session_start();
    $_SESSION['user'] = $user['email'];
    header('Location: ../../index.php');
} else {
    header('Location: ../../index.php');
}
?>