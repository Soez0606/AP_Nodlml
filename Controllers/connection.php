<?php
require_once '../Models/BDD.php';

use NoodleML\Models\BDD;

$db = new BDD();

$user = $db->login($_POST['email'] ?? '', $_POST['password'] ?? '');
if ($user) {
    session_start();
    $_SESSION['user'] = $user['email'];
    $_SESSION['role'] = $user['role'];
    header('Location: ../../dashboard.php');
} else {
    header('Location: ../../index.php');
}
?>