<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';
require_once('../Models/BDD.php');


use NoodleML\Core\{Router, Request, Response};
use NoodleML\Models\BDD;

$db = new BDD();

$log = $db->login('joeylewis04032006@gmail.com', 'eleve');
// $router = new Router();

// $req = Request::fromGlobals();

// $res = new Response();

// (require __DIR__ . '/../config/routes.php')($router);

// $router->dispatch($req,$res);