<?php
declare(strict_types=1);

namespace NoodleML\Controllers;

use NoodleML\Core\{Request, Response, View};

/**
 * 
 * @file PageController.php
 * @brief Contrôleur de nos pages (hors-cours)
 * 
 */
final class PageController
{
    public function __construct() {}

    public function politique_confidentialité(Request $q, Response $s)
    {
        $s->html(View::render('politique-confidentialite.php'));
    }

    public function pourquoi_javascript_et_cpp(Request $q, Response $s)
    {
        $s->html(View::render('pourquoi-javascript-et-cpp.php'));
    }

    public function approche_pedagogique_et_didactique(Request $q, Response $s)
    {
        $s->html(View::render('approche-pedagogique-et-didactique.php'));
    }

    public function presentation_noodleml_js(Request $q, Response $s)
    {
        $s->html(View::render('presentation-noodleml-js.php'));
    }
}