<?php
/**
 * 
 * @file routes.php
 * @brief Fichier contenant les routes de notre application
 * 
 */
declare(strict_types=1);

use NoodleML\Core\{Router, Request, Response};
use NoodleML\Controllers\{HomeController, AdminController, PageController};

/**
 * 
 * Fonction de configuration des routes
 * 
 * @param Router $router Routeur sur lequel on enregistre les routes.
 * @return callable Callback vide (non utilisée mais permet de chaîner)
 * 
 */
return function (Router $router): callable {
    $home = new HomeController();
    $admin = new AdminController();
    $page = new PageController();

    $router->get('/', fn(Request $q, Response $s) => $home->index($q, $s));
    
    // Décommentez la route si dessous et commentez celle du dessus pour accéder à notre page index en cours de construction.
    //$router->get('/', fn(Request $q, Response $s) => $home->index_wait($q, $s));
    
    $router->get('/politique-confidentialite', fn(Request $q, Response $s) => $page->politique_confidentialité($q, $s));

    $router->get('/pourquoi-javascript-et-cpp', fn(Request $q, Response $s) => $page->pourquoi_javascript_et_cpp($q, $s));

    $router->get('/approche-pedagogique-et-didactique', fn(Request $q, Response $s) => $page->approche_pedagogique_et_didactique($q, $s));

    $router->get('/presentation-noodleml-js', fn(Request $q, Response $s) => $page->presentation_noodleml_js($q,$s));

    $router->get('/login', fn(Request $q, Response $s) => $admin->login($q, $s));

    $router->get('/admin', fn(Request $q, Response $s) => $admin->admin($q, $s));

    return fn() => null;
};