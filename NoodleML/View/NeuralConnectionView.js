/**********************************************************
 *      _   _                 _ _     ___  ___ _
 *     | \ | |               | | |    |  \/  || |
 *     |  \| | ___   ___   __| | | ___| .  . || |
 *     | . ` |/ _ \ / _ \ / _` | |/ _ \ |\/| || |
 *     | |\  | (_) | (_) | (_| | |  __/ |  | || |____
 *     \_| \_/\___/ \___/ \__,_|_|\___\_|  |_/\_____/
 *
 *      🍜 Think with noodles. Code AI with neurons.
 *       -------------------------------------------
 *            Minimal Neural Network Framework
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <https://www.gnu.org/licenses/>.
 * 
 * © 2025 Sébastien MARCHAND
 **********************************************************/

/**
 * @class NeuralConnectionView
 * @classdesc Représente visuellement une connexion synaptique entre deux neurones.
 */
class NeuralConnectionView {
  /**
   * Crée une connexion entre deux points (représentant deux neurones).
   * 
   * @param {CanvasRenderingContext2D} ctx - Contexte de rendu 2D du canvas.
   * @param {{x: number, y: number}} from - Coordonnées du point de départ (neurone source).
   * @param {{x: number, y: number}} to - Coordonnées du point d’arrivée (neurone cible).
   * @param {function(): number} getWeightFn - Fonction retournant dynamiquement le poids actuel.
   * @param {label} label - titre de la connexion (facultatif).
   * @param {textAlign} textAlign - Alignement du titre (gauche ou droite) (facultatif).
   * @param {colorMode} colorMode - Mode d'affichage de la connexion (facultatif)
   */
  constructor(ctx, from, to, getWeightFn, title = "", label = "", textAlign = "left", colorMode = "dark") {
    this.ctx = ctx;
    this.from = from;
    this.to = to;
    this.getWeight = getWeightFn; // getWeightFn peut être undefined si pas de poids
    this.isWeighted = typeof getWeightFn === "function"; // Vérifie si la fonction de poids est définie
    this.title = title; // Titre de la connexion
    this.label = label; // Label (ex: poids)
    this.textAlign = textAlign; // Alignement du titre (gauche ou droite)
    this.displayMode = "arrow_with_label"; // Mode d'affichage par défaut
    this.colorMode = colorMode; // Mode d'affichage de la connexion
  }

  /**
   * Dessine la connexion (ligne et flèche).
   * Cette méthode dessine la ligne entre les deux neurones et une flèche à l'extrémité.
   */
  drawConnection(coloured = false) {

    this.ctx.save();

    const from = this.from;
    const to = this.to;

    var color = "#ccc"; // Couleur par défaut si pas de poids
    let thickness = 1; // Épaisseur par défaut si pas de poids

    if (this.colorMode === "dark") {
      color = "#ccc"; // Couleur par défaut pour le mode sombre
    } else if (this.colorMode === "light") {
      color = "#000"; // Couleur par défaut pour le mode clair
    }

    if (coloured) {
      let weight = 0;
      if (this.isWeighted) {
        weight = this.getWeight();

        // Poids maximum pour la normalisation
        const maxWeight = 1;

        // Transparence basée sur le poids [0.1 à maxWeight]
        let alpha = Math.max(0.1, Math.min(maxWeight, Math.abs(weight)));

        // Normalisation de la transparence par rapport au poids maximum
        alpha = Math.abs(weight) / maxWeight;

        if (weight >= 0) {
          color = `rgba(0, 255, 0, ${alpha})`; // Vert avec transparence
        } else {
          color = `rgba(255, 0, 0, ${alpha})`; // Rouge avec transparence
        }
      }
    }

    // Ligne principale
    this.ctx.strokeStyle = color;
    this.ctx.lineWidth = thickness;

    this.ctx.beginPath();
    this.ctx.moveTo(from.x, from.y);
    this.ctx.lineTo(to.x, to.y);
    this.ctx.stroke();

    // Flèche de direction
    const angle = Math.atan2(to.y - from.y, to.x - from.x);
    const arrowLength = 20;
    const ax = to.x - Math.cos(angle) * arrowLength;
    const ay = to.y - Math.sin(angle) * arrowLength;

    this.ctx.beginPath();
    this.ctx.moveTo(ax, ay);
    this.ctx.lineTo(
      ax - arrowLength * 0.6 * Math.cos(angle - 0.4),
      ay - arrowLength * 0.6 * Math.sin(angle - 0.4)
    );
    this.ctx.lineTo(
      ax - arrowLength * 0.6 * Math.cos(angle + 0.4),
      ay - arrowLength * 0.6 * Math.sin(angle + 0.4)
    );
    this.ctx.closePath();
    this.ctx.fillStyle = color;
    this.ctx.fill();

    if (this.displayMode === "arrow_with_circle_at_left" || this.displayMode === "arrow_with_circle_at_left_and_label") {

      if (this.colorMode === "dark") {
        this.ctx.fillStyle = "#fff"; // Couleur par défaut pour le mode sombre
      } else if (this.colorMode === "light") {
        this.ctx.fillStyle = "#000"; // Couleur par défaut pour le mode clair
      }

      this.ctx.beginPath();
      this.ctx.arc(from.x - 4, from.y, 4, 0, 2 * Math.PI);
      this.ctx.fill();
    }

    if (this.displayMode !== "arrow_with_circle_at_left_and_label") {
      if (this.colorMode === "dark") {
        this.ctx.fillStyle = "#fff"; // Couleur par défaut pour le mode sombre
      } else if (this.colorMode === "light") {
        this.ctx.fillStyle = "#000"; // Couleur par défaut pour le mode clair
      }

      this.ctx.beginPath();
      this.ctx.arc(from.x, from.y, 4, 0, 2 * Math.PI);
      this.ctx.fill();
    }

    if (this.displayMode === "arrow_with_circle_at_right" || this.displayMode === "arrow_with_circle_at_right_and_label") {

      if (this.colorMode === "dark") {
        this.ctx.fillStyle = "#fff"; // Couleur par défaut pour le mode sombre
      } else if (this.colorMode === "light") {
        this.ctx.fillStyle = "#000"; // Couleur par défaut pour le mode clair
      }

      this.ctx.beginPath();
      this.ctx.arc(to.x, to.y, 4, 0, 2 * Math.PI);
      this.ctx.fill();
    }

    if (this.displayMode !== "arrow_with_label") {
      if (this.textAlign === "right") {
        this.ctx.fillText(this.title, to.x + 10, to.y + 4);
      }
      else if (this.textAlign === "left") {
        const metrics = this.ctx.measureText(this.title);
        this.ctx.fillText(this.title, from.x - metrics.width - 10, from.y + 4);
      }
    }

    this.ctx.restore();
  }

  /**
   * Affiche le poids centré à une fraction de la connexion.
   * Le poids est affiché à la position spécifiée par `fraction` de la distance entre les points `from` et `to`.
   * 
   * @param {number} fraction - Fraction de la connexion où le poids sera affiché (ex: 1/3 pour le tiers).
   * Par défaut, cette valeur est 1/4 (un quart de la connexion).
   */
  drawWeight(fraction = 1 / 4) {

    if (!this.isWeighted) return;

    const from = this.from;
    const to = this.to;
    const weight = this.getWeight();

    // Calcul des coordonnées du texte (à la position fractionnée de la connexion)
    const mx = from.x + (to.x - from.x) * fraction + 5;
    const my = from.y + (to.y - from.y) * fraction;

    // Définir la couleur et la police du texte
    this.ctx.font = "10px sans-serif";

    let text = "w=" + weight.toFixed(2);

    // Mesurer la largeur du texte pour centrer le texte parfaitement
    const metrics = this.ctx.measureText(text);
    const textWidth = metrics.width;
    const textHeight = 10; // Taille approximative du texte

    // Dessiner le fond du texte
    this.ctx.fillStyle = this.colorMode === "dark" ? "rgba(0, 0, 0, 0.5)" : "rgba(255, 255, 255, 0.5)"; // Fond noir ou blanc selon le mode
    this.ctx.fillRect(mx - textWidth / 2 - 2, my - textHeight / 2, textWidth + 4, textHeight + 2);

    // Affichage du poids (texte) centré
    this.ctx.fillStyle = this.colorMode === "dark" ? "#fff" : "#000"; // Fond noir ou blanc selon le mode
    this.ctx.fillText(text, mx - textWidth / 2, my + 4);
  }

  drawLabel(fraction = 1 / 4) {

    if (!this.isWeighted) return;

    const from = this.from;
    const to = this.to;

    // Calcul des coordonnées du texte (à la position fractionnée de la connexion)
    const mx = from.x + (to.x - from.x) * fraction + 4;
    const my = from.y + (to.y - from.y) * fraction;

    // Définir la couleur et la police du texte
    this.ctx.font = "10px sans-serif";

    let text = this.label;

    // Mesurer la largeur du texte pour centrer le texte parfaitement
    const metrics = this.ctx.measureText(text);
    const textWidth = metrics.width;
    const textHeight = 10; // Taille approximative du texte

    // Fond noir semi-transparent derrière le texte
    if (this.colorMode === "dark") {
      this.ctx.fillStyle = "rgba(0, 0, 0, 0.5)"; // Fond noir avec 50% de transparence
    } else if (this.colorMode === "light") {
      this.ctx.fillStyle = "rgba(255, 255, 255, 0.5)"; // Fond blanc avec 50% de transparence
    }

    this.ctx.fillRect(mx - textWidth / 2 - 5, my - textHeight / 2, textWidth + 10, textHeight + 5);

    this.drawSubscriptLabel("w", this.label, "", mx - textWidth / 2, my + 4);
  }

  /**
   * Dessine un texte avec un indice simulé suivi d'une valeur, ex : bA1 = 0.56
   * @param {string} label - Partie principale du texte (ex: "b")
   * @param {string} subscript - Partie en indice visuel (ex: "A1")
   * @param {string|number} value - Valeur à afficher (ex: "0.56")
   * @param {number} x - Position X de départ
   * @param {number} y - Position Y de base
   */
  drawSubscriptLabel(label, subscript, value, x, y) {
    this.ctx.fillStyle = "#fff";

     if (this.colorMode === "dark") {
      this.ctx.fillStyle = "#fff";
    } else if (this.colorMode === "light") {
      this.ctx.fillStyle = "#000";
    }
    
    this.ctx.textAlign = "left";

    // Dessin du label principal (ex: "b")
    this.ctx.font = "11px sans-serif";
    this.ctx.fillText(label, x, y);

    // Dessin du subscript en plus petit, légèrement décalé
    this.ctx.font = "9px sans-serif";
    this.ctx.fillText(subscript, x + 7, y + 3);

    // Dessin de la valeur après le subscript
    if (value !== undefined && value !== null && value !== "") {
      {
        this.ctx.font = "11px sans-serif";
        const subWidth = this.ctx.measureText(subscript).width;
        this.ctx.fillText(` = ${value}`, x + subWidth, y);
      }

    }
  }

  /**
   * @brief Définit le mode d'affichage de la connexion.
   * * @param {string} displayMode - Le mode d'affichage à appliquer. Doit être l'un des suivants :
   * * - `"arrow_with_label"` : Liaison entre neurones avec étiquette
   * * - `"arrow_with_circle_at_left"` : Entrée avec cercle à gauche
   * * - `"arrow_with_circle_at_left_and_label"` : Entrée avec cercle à gauche et étiquette
   * * - `"arrow_with_circle_at_right"` : Sortie avec cercle à droite
   * * - `"arrow_with_circle_at_right_and_label"` : Sortie avec cercle à droite et étiquette  
   */
  setDisplayMode(displayMode) {
    const allowedModes =
      [
        "arrow_with_label", // Liaison entre neurones
        "arrow_with_circle_at_left", // Entrée
        "arrow_with_circle_at_left_and_label", // Entrée
        "arrow_with_circle_at_right", // Sortie
        "arrow_with_circle_at_right_and_label" // Sortie
      ];

    if (!allowedModes.includes(displayMode)) {
      console.warn(`[NeuralConnectionView] Mode d'affichage "${displayMode}" non valide. Modes autorisés : ${allowedModes.join(", ")}`);
      this.displayMode = "arrow_with_label";
      return;
    }

    this.displayMode = displayMode;
  }

  /**
   * @brief Définit le mode de couleur pour l'affichage de la connexion.
   * @param {string} colorMode - Le mode de couleur à appliquer. Doit être `"light"` ou `"dark"`.
   *
   * @warning Si le mode est invalide, la méthode bascule automatiquement sur `"dark"` et affiche un `console.warn`.
   */
  setColorMode(colorMode) {
    const allowedModes = ["light", "dark"];

    if (!allowedModes.includes(colorMode)) {
      console.warn(`[NeuralConnectionView] Mode de couleur "${colorMode}" non valide. Modes autorisés : ${allowedModes.join(", ")}`);
      this.colorMode = "dark";
      return;
    }

    this.colorMode = colorMode;
  }
}
