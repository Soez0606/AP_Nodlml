<?php
declare(strict_types=1);

namespace NoodleML\Controllers;

use NoodleML\Core\{Request, Response, View};

/**
 * 
 * @file HomeController.php
 * @brief Contrôleur de notre page d'acceuil
 */
final class AdminController
{
    public function __construct() {}

    public function login(Request $q, Response $s)
    {
        $s->html(View::render('login.php'));
    }

    public function admin(Request $q, Response $s)
    {
        $s->html(View::render('admin.php'));
    }

    public function test(Request $q, Response $s)
    {
        $s->html(View::render('admin.php'));
    }
}