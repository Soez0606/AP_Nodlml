<?php
require_once('../Models/BDD.php');
use NoodleML\Models\BDD;

$db = new BDD;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test</title>
</head>
<body>
    <h1 style="margin-left: auto, margin-right: auto, margin-top: 35vh;"><?php var_dump($test) ?></h1>
</body>
</html>