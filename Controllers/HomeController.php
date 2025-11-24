<?php
declare(strict_types=1);

namespace NoodleML\Controllers;

use NoodleML\Core\{Request, Response, View};

/**
 * 
 * @file HomeController.php
 * @brief Contrôleur de notre page d'acceuil
 */
final class HomeController
{
    public function __construct() {}

    public function index(Request $q, Response $s)
    {
        $s->html(View::render('acceuil.php'));
    }

    public function index_wait(Request $q, Response $s)
    {
        $s->html(View::render('acceuil-wait.php'));
    }
}