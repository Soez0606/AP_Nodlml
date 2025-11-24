<?php
declare(strict_types=1);

namespace NoodleML\Core;

/**
 * @file Router.php
 * @brief Routeur HTTP : associe des chemins d’URL à des gestionnaires (contrôleurs).
 *
 * @details
 * - Le routeur est une pièce centrale du modèle MVC côté serveur.
 * - Il mémorise pour chaque méthode HTTP (GET/POST) les associations
 *   (chemin → fonction à exécuter).
 * - Lorsqu’une requête arrive, il cherche si une route correspond :
 *   - Si oui : il exécute le contrôleur associé.
 *   - Si non : il renvoie une réponse 404.
 *
 * Exemple :
 * @code
 * $router->get('/', fn($req, $res) => $home->index($req, $res));
 * $router->post('/analyze', fn($req, $res) => $ana->analyze($req, $res));
 * @endcode
 *
 * @see Request
 * @see Response
 */
final class Router
{
  /**
   * @var array<string,array<string,callable>>
   * Tableau des routes organisées par méthode HTTP.
   * Exemple :
   * [
   *   'GET'  => ['/' => callable],
   *   'POST' => ['/analyze' => callable]
   * ]
   */
  private array $routes = ['GET' => [], 'POST' => []];

  /**
   * @brief Enregistre une route GET.
   *
   * @param string $path Chemin de l’URL (ex: "/")
   * @param callable $handler Fonction à exécuter (ex: contrôleur)
   */
  public function get(string $path, callable $handler): void
  {
    $this->routes['GET'][$path] = $handler;
  }

  /**
   * @brief Enregistre une route POST.
   *
   * @param string $path Chemin de l’URL (ex: "/analyze")
   * @param callable $handler Fonction à exécuter
   */
  public function post(string $path, callable $handler): void
  {
    $this->routes['POST'][$path] = $handler;
  }

  /**
   * @brief Recherche et exécute la route correspondant à la requête.
   *
   * @param Request $req Requête entrante (méthode + URI)
   * @param Response $res Réponse HTTP à construire
   *
   * @details
   * - Si une route correspond → appelle son gestionnaire.
   * - Sinon → renvoie une erreur 404 avec un message minimal.
   */
  public function dispatch(Request $req, Response $res): void
  {
    $handler = $this->routes[$req->method][$req->uri] ?? null;

    if (!$handler) {
      // Aucune route correspondante → 404
      $res->setStatus(404);
      $res->html(html: '<h1>404</h1>');
      return;
    }

    // Exécute le gestionnaire associé (souvent un contrôleur)
    $handler($req, $res);
  }
}
