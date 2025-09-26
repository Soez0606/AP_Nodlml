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
 * @class Plot
 * @classdesc Classe permettant de dessiner un graphique représentant une fonction cible et une prédiction d'un réseau de neurones, avec options pour afficher des graduations, un titre, et une légende.
 */
class Plot {
  /**
   * Crée un graphique dans un canvas.
   * 
   * @param {CanvasRenderingContext2D} ctx - Contexte 2D du canvas.
   * @param {number} x - Position X d'origine.
   * @param {number} y - Position Y d'origine.
   * @param {number} size - Taille du carré (largeur = hauteur).
   * @param {function(number): number} trueFunction - Fonction cible y = f(x).
   * @param {string} [title=""] - Titre du graphique (facultatif).
   * @param {number} [gradations=10] - Nombre de graduations sur les axes (facultatif).
   * @param {Array} [legendItems=[]] - Liste des éléments de légende avec couleur et description.
   */
  constructor(ctx, x, y, size, trueFunction, title = "", gradations = 10, legendItems = []) {
    this.ctx = ctx;
    this.x = x;
    this.y = y;
    this.size = size;
    this.trueFunction = trueFunction;
    this.title = title;
    this.gradations = gradations;
    this.legendItems = legendItems.length > 0 ? legendItems : [
      { color: 'lightgreen', label: 'Prédiction correcte' },
      { color: 'red', label: 'Prédiction incorrecte' },
      { color: 'blue', label: 'Fonction modèle' }
    ];

    // Cache pour l'image
    this.cachedImage = null;
    this.lastTrueFunction = trueFunction.toString();  // Sauvegarde la fonction sous forme de chaîne pour détecter les changements
  }

  /**
   * Dessine la fonction vraie + la prédiction du réseau.
   * 
   * @param {function(number): number} predictFn - Fonction de prédiction.
   * @param {boolean} [forceRefresh=false] - Paramètre optionnel pour forcer le rafraîchissement du cache.
   */
  draw(predictFn, forceRefresh = false) {
    this.ctx.imageSmoothingEnabled = false;

    // Si forceRefresh est activé ou la fonction a changé, on réinitialise le cache
    if (forceRefresh || this.trueFunction.toString() !== this.lastTrueFunction) {
      this.cachedImage = null; // Réinitialise l'image mise en cache
      this.lastTrueFunction = this.trueFunction.toString(); // Met à jour la fonction cible
    }

    // Si l'image n'est pas encore en cache, on la génère
    if (!this.cachedImage) {
      this.generateImage(predictFn);  // Génère et met en cache l'image
    }

    // Affichage de l'image en cache
    this.ctx.save();
    this.ctx.putImageData(this.cachedImage, this.x, this.y);

    // Affichage du titre
    if (this.title) {
      this.ctx.fillStyle = "#ccc";
      this.ctx.font = "16px sans-serif";
      this.ctx.fillText(this.title, this.x + (this.size / 2) - (this.ctx.measureText(this.title).width / 2), this.y - 10);
    }

    // Affichage des graduations sur l'axe X et Y
    this.drawGraduations();

    // Affichage de la légende
    if (this.legendItems.length > 0) {
      this.drawLegend();
    }

    // Affichage d'un cadre autour de l'image
    this.ctx.strokeStyle = "#666";
    this.ctx.lineWidth = 1;
    this.ctx.strokeRect(this.x, this.y, this.size, this.size);

    this.ctx.restore();
  }

  /**
   * Génère et dessine un graphique représentant la fonction vraie et les prédictions du réseau sur un canvas.
   * La fonction vraie est tracée en bleu, et les erreurs sont affichées en rouge (erreur élevée) ou en vert (erreur faible).
   * Le graphique est mis en cache pour améliorer les performances de rendu lors des rafraîchissements.
   * 
   * @param {function(number): number} predictFn - La fonction de prédiction utilisée pour générer les courbes.
   * 
   * @description
   * La méthode crée un graphique avec des axes X et Y. Les points sont tracés pour la fonction vraie et pour la fonction
   * de prédiction. Les erreurs de prédiction sont colorées en rouge ou en vert selon leur magnitude.
   * La fonction de prédiction est évaluée pour plusieurs valeurs de X allant de -1 à 1 et est comparée à la fonction cible.
   * Un écart faible entre la fonction prédite et la fonction cible est coloré en vert, sinon en rouge. Les axes sont tracés
   * pour indiquer l'origine et les échelles.
   * 
   * La méthode utilise un décalage vertical `y_offset` et une échelle (`scale`) pour compresser la courbe verticalement
   * et la maintenir visible dans le graphique.
   * 
   * Le graphique est stocké en cache pour optimiser les performances lors du rendu, évitant ainsi de redessiner l'ensemble
   * du graphique à chaque itération.
   */
  generateImage(predictFn) {
    const ctx = this.ctx; // Contexte de rendu 2D du canvas
    const size = this.size; // Taille du graphique
    const image = ctx.createImageData(size, size); // Crée une image vide à remplir avec les données
    image.data.fill(11); // Remplir le fond de l'image avec une couleur sombre (code RGB: [11, 11, 11])
    // S'assurer que chaque pixel est totalement opaque
    for (let i = 3; i < image.data.length; i += 4) {
      image.data[i] = 255; // Canal alpha
    }

    const y_offset = 10; // Décalage vertical de la courbe pour la positionner plus bas sur le graphique
    const scale = size - 2 * y_offset; // L'échelle verticale permettant de compresser la courbe pour qu'elle soit visible

    // Calcul de la position des axes X et Y
    const x_axis_y = size - Math.floor(0 * size) - y_offset;
    const y_axis_x = Math.floor((0 + 1) / 2 * size);

    // Tracer les axes X et Y (axe horizontal et vertical)
    for (let i = 0; i < size; i++) {
      // Tracer l'axe X
      if (x_axis_y >= 0 && x_axis_y < size) {
        const idx = (x_axis_y * size + i) * 4;
        image.data.set([68, 68, 68, 255], idx); // Couleur grise pour les axes
      }
      // Tracer l'axe Y
      if (y_axis_x >= 0 && y_axis_x < size) {
        const idx = (i * size + y_axis_x) * 4;
        image.data.set([68, 68, 68, 255], idx); // Couleur grise pour les axes
      }
    }

    // Tracer les courbes pour chaque point sur l'axe X
    for (let i = 1; i < size - 1; i++) {
      // Calculer les coordonnées X de chaque point
      const x1 = -1 + 2 * i / size;
      const x2 = -1 + 2 * (i + 1) / size;

      // Calculer la valeur de la fonction vraie pour ces points
      const y1_true = this.trueFunction(x1);
      const y2_true = this.trueFunction(x2);

      // Calculer la prédiction pour ces points avec la fonction de prédiction
      const y1_pred = predictFn(x1);
      const y2_pred = predictFn(x2);

      // Appliquer l'échelle verticale et le décalage pour ajuster la position des points sur le graphique
      const py1_true = size - Math.floor((y1_true * scale)) - y_offset;
      const py2_true = size - Math.floor((y2_true * scale)) - y_offset;

      const py1_pred = size - Math.floor((y1_pred * scale)) - y_offset;
      const py2_pred = size - Math.floor((y2_pred * scale)) - y_offset;

      // Calculer l'erreur entre la prédiction et la fonction vraie
      const err1 = Math.abs(y1_pred - y1_true);
      const err2 = Math.abs(y2_pred - y2_true);
      const eps = 2.0 / size; // Seuil de tolérance pour définir une erreur faible

      // Déterminer la couleur des points en fonction de l'erreur (vert pour faible erreur, rouge pour erreur élevée)
      const col1 = err1 < eps ? [0, 255, 0] : [255, 0, 0];  // Vert ou rouge pour le premier point
      const col2 = err2 < eps ? [0, 255, 0] : [255, 0, 0];  // Vert ou rouge pour le deuxième point

      // Fonction pour dessiner un point avec une certaine couleur et épaisseur
      const drawPoint = (x, y, color) => {
        if (x >= 2 && x < size - 2 && y >= 2 && y < size - 2) {
          if (color[0] === 255 && color[1] === 0 && color[2] === 0) {
            // Si le point est rouge, dessiner un seul pixel
            const idx = (y * size + x) * 4;
            image.data.set([...color, 255], idx);
          } else {
            // Si le point est vert, dessiner un point plus épais
            for (let dy = -1; dy <= 1; dy++) {
              for (let dx = -1; dx <= 1; dx++) {
                const idx = ((y + dy) * size + (x + dx)) * 4;
                image.data.set([...color, 255], idx);
              }
            }
          }
        }
      };

      // Dessiner la courbe vraie en bleu
      const drawBlue = (x, y) => {
        if (x >= 0 && x < size && y >= 0 && y < size) {
          const idx = (y * size + x) * 4;
          image.data.set([102, 102, 255, 255], idx); // Couleur bleue pour la fonction vraie
        }
      };

      // Tracer les points de la fonction vraie et de la prédiction
      drawBlue(i, py1_true);
      drawBlue(i + 1, py2_true);
      drawPoint(i, py1_pred, col1);
      drawPoint(i + 1, py2_pred, col2);
    }

    // Sauvegarder l'image générée dans le cache pour améliorer les performances lors des rafraîchissements
    this.cachedImage = image;
  }

  /**
   * Dessine les graduations sur les axes X et Y.
   */
  drawGraduations() {
    const ctx = this.ctx;
    const size = this.size;
    const step = size / this.gradations;

    ctx.fillStyle = "#ccc";
    ctx.font = "10px sans-serif";

    // Axes X
    for (let i = 1; i < this.gradations; i++) {
      const x = i * step;
      const label = ((i / this.gradations) * 2 - 1).toFixed(1); // Valeur entre -1 et 1
      ctx.fillText(label, this.x + x, this.y + size - 1); // Affichage sous l'axe X
    }

    // Axes Y
    for (let i = 1; i < this.gradations; i++) {
      const y = i * step;
      const label = ((1 - i / this.gradations)).toFixed(1); // Valeur entre -1 et 1
      ctx.fillText(label, this.x - 20, this.y + y); // Affichage à gauche de l'axe Y
    }
  }

  /**
   * Dessine la légende des couleurs sous le graphique.
   */
  drawLegend() {
    const ctx = this.ctx;
    const xOffset = this.x + 10;
    let yOffset = this.y + 10;

    ctx.fillStyle = "#ccc";
    ctx.font = "12px sans-serif";

    // Légende
    for (const item of this.legendItems) {
      ctx.fillStyle = item.color;
      ctx.fillRect(xOffset, yOffset, 20, 10); // Carré coloré
      ctx.fillStyle = "#ccc";
      ctx.fillText(item.label, xOffset + 25, yOffset + 10);
      yOffset += 20; // Espace entre les éléments de la légende
    }
  }

  /**
   * Sauvegarde le graphique actuel en tant qu'image PNG.
   * Cette méthode génère une URL de données pour l'image et crée un lien de téléchargement.
   * 
   * @param {string} filename - Le nom du fichier PNG à télécharger.
   */
  saveAreaAsPNG(filename = 'graphique.png') {

    // Extraire la zone du canvas
    const imageData = this.ctx.getImageData(this.x, this.y, this.size, this.size);

    // Créer un nouveau canvas pour dessiner la zone extraite
    const tempCanvas = document.createElement('canvas');
    const tempCtx = tempCanvas.getContext('2d');
    tempCanvas.width = this.size;
    tempCanvas.height = this.size;

    // Dessiner l'image extraite dans le nouveau canvas
    tempCtx.putImageData(imageData, 0, 0);

    // Créer un lien pour télécharger l'image PNG
    const dataURL = tempCanvas.toDataURL('image/png');

    // Créer un élément <a> temporaire pour le téléchargement
    const a = document.createElement('a');
    a.href = dataURL;
    a.download = filename;

    // Ajouter l'élément à la page, déclencher le clic pour démarrer le téléchargement, puis le retirer
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
  }

}