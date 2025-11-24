<?php
declare(strict_types=1);

namespace NoodleML\Core;

/**
 * @file Response.php
 * @brief Représente la réponse HTTP envoyée au client (navigateur).
 *
 * @details
 * Cette classe encapsule les actions principales liées à une réponse :
 * - définir le code de statut HTTP (200, 404, 500…)
 * - envoyer du contenu HTML au navigateur
 *
 * Dans ce projet pédagogique, elle reste volontairement simple,
 * mais elle pourrait être enrichie (en-têtes, JSON, fichiers, etc.).
 */
final class Response
{
  /**
   * @brief Définit le code de statut HTTP de la réponse.
   *
   * @param int $code Code numérique (200 = OK, 404 = Not Found, 500 = Server Error…)
   *
   * @details
   * Utilise la fonction native `http_response_code()` de PHP.
   * À appeler avant d’envoyer du contenu.
   *
   * Exemple :
   * @code
   * $res->setStatus(404); // Not Found
   * @endcode
   */
  public function setStatus(int $code): void
  {
    http_response_code($code);
  }

  /**
   * @brief Envoie du contenu HTML au client.
   *
   * @param string $html Chaîne HTML complète à afficher dans le navigateur
   *
   * @details
   * - Ici, on se limite à un simple `echo`.
   * - Dans une vraie application, on pourrait gérer les en-têtes Content-Type,
   *   le buffering de sortie, ou encore l’envoi de JSON.
   *
   * Exemple :
   * @code
   * $res->html("<h1>Bonjour</h1>");
   * @endcode
   */
  public function html(string $html): void
  {
    echo $html;
  }
}
