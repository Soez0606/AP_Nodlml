<?php
declare(strict_types=1);

namespace NoodleML\Core;

/**
 * @file Request.php
 * @brief Représente une requête HTTP entrante (données envoyées par le client).
 *
 * @details
 * Cette classe encapsule les informations principales d’une requête HTTP :
 * - la méthode (GET, POST, …)
 * - l’URI demandée
 * - les paramètres de query string ($_GET)
 * - le corps de la requête ($_POST)
 *
 * Elle offre aussi une méthode pratique `input()` pour récupérer
 * un paramètre sans se soucier de son origine (query ou body).
 */
final class Request
{
  /**
   * @param string $method Méthode HTTP (GET, POST, …)
   * @param string $uri URI de la requête (ex: "/analyze")
   * @param array $query Paramètres de query string (équivalent $_GET)
   * @param array $body Paramètres envoyés en POST (équivalent $_POST)
   */
  public function __construct(
    public readonly string $method,
    public readonly string $uri,
    public readonly array $query,
    public readonly array $body
  ) {
  }

  /**
   * @brief Construit une Request à partir des variables globales PHP.
   *
   * @return self Une instance de Request initialisée avec $_SERVER, $_GET, $_POST
   *
   * @details
   * - `$_SERVER['REQUEST_METHOD']` → méthode HTTP
   * - `$_SERVER['REQUEST_URI']`    → URI brute (on supprime la query string)
   * - `$_GET` et `$_POST`          → copiés dans $query et $body
   */
  public static function fromGlobals(): self
  {
    // Récupère l’URI sans la partie query string (?…)
    $uri = strtok($_SERVER['REQUEST_URI'] ?? '/', '?') ?: '/';

    return new self(
      $_SERVER['REQUEST_METHOD'] ?? 'GET',
      rtrim($uri, '/') ?: '/',
      $_GET ?? [],
      $_POST ?? []
    );
  }

  /**
   * @brief Récupère un paramètre envoyé par le client.
   *
   * @param string $key Nom du paramètre recherché
   * @return string|null Valeur trouvée ou null si absente
   *
   * @details
   * La recherche est faite en deux temps :
   * 1. Dans le corps de la requête (POST)
   * 2. Dans la query string (GET)
   */
  public function input(string $key): ?string
  {
    return $this->body[$key] ?? $this->query[$key] ?? null;
  }
}
