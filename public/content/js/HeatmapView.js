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
 * @class Classe permettant de visualiser l'activation d'un neurone sous forme de heatmap.
 * @classdesc La classe gère le calcul et l'affichage de la heatmap pour un neurone donné, en 
 * utilisant les activations du neurone pour générer une image colorée représentant ses activations.
 */
class HeatmapView {
  /**
   * Constructeur pour la classe HeatmapView.
   * Ce constructeur initialise l'objet `HeatmapView` en assignant les paramètres fournis
   * et en effectuant les vérifications nécessaires pour s'assurer que le neurone est valide.
   * 
   * @param {CanvasRenderingContext2D} ctx - Contexte 2D du canvas où la heatmap sera dessinée.
   * @param {number} x - Position X de la heatmap sur le canvas.
   * @param {number} y - Position Y de la heatmap sur le canvas.
   * @param {number} size - Taille de la heatmap (largeur et hauteur de l'image).
   * @param {Neuron} neuron - Instance du neurone utilisé pour générer la heatmap.
   * @param {boolean} [displayResultHeatmapIfPossible=false] - Indique si la heatmap doit être 
   * affichée comme le résultat d'une prédiction du neurone. -Représentation possible uniquement si le neurone à au 
   * maximum deux entrées.-
   * 
   * @description
   * 1. Le constructeur initialise les paramètres de base : le contexte de dessin (`ctx`), la position `(x, y)` et
   *    la taille de la heatmap, ainsi que l'objet `neuron` associé.
   * 2. Il vérifie que le neurone est valide (non nul) et possède la méthode `predict`. Si le neurone est invalide
   *    ou ne possède pas la méthode `predict`, un avertissement est affiché et le constructeur s'arrête.
   * 3. Il initialise les variables de suivi des poids et du biais du neurone, en sauvegardant les poids et le biais
   *    actuels du neurone dans `lastWeight` et `lastBias`.
   * 4. Un attribut `needsDisplay` est initialisé à `true` pour indiquer que la heatmap doit être affichée par défaut.
   * 5. Le constructeur initialise un attribut `cachedImage` à `null` pour stocker l'image générée de la heatmap en cache.
   * 
   * @returns {void}
   */
  constructor(ctx, x, y, size, neuron, displayResultHeatmapIfPossible = false, colorMode = "dark") {
    // Initialisation des paramètres de la heatmap
    this.ctx = ctx;           // Contexte 2D du canvas
    this.x = x;               // Position X de la heatmap
    this.y = y;               // Position Y de la heatmap
    this.size = size;         // Taille de la heatmap
    this.neuron = neuron;     // Neurone utilisé pour générer la heatmap
    this.displayResultHeatmapIfPossible = displayResultHeatmapIfPossible; // Indicateur pour afficher la heatmap comme résultat

    // Initialisation de l'état de la heatmap
    this.needsDisplay = true; // La heatmap doit être affichée par défaut
    this.lastWeight = this.neuron.getWeights(); // Sauvegarde des poids du neurone
    this.lastBias = this.neuron.getBias();     // Sauvegarde du biais du neurone

    // Vérification que le neurone est valide et possède la méthode predict
    if (!this.neuron) {
      console.warn("[HeatmapView] Neurone invalide :", this.neuron);
      return; // Arrêter si le neurone est invalide
    } else if (typeof this.neuron.predict !== "function") {
      console.warn("[HeatmapView] Méthode predict absente :", this.neuron);
      return; // Arrêter si la méthode predict est absente
    }

    const allowedModes = ["light", "dark"];
    if (!allowedModes.includes(colorMode)) {
      console.warn(`[HeatmapView] Mode de couleur "${colorMode}" non valide. Modes autorisés : ${allowedModes.join(", ")}`);
      colorMode = "dark"; // Valeur par défaut
    }

    this.colorMode = colorMode;

    // Attribut pour stocker l'image de la heatmap en cache
    this.cachedImage = null; // Initialisation à null, l'image sera générée plus tard
  }


  /**
   * Met à jour et affiche la heatmap avec une étiquette (si fournie).
   * Cette méthode appelle d'abord `update()` pour vérifier si des modifications doivent être apportées
   * à l'image de la heatmap (par exemple, si les poids ou le biais ont changé). Ensuite, elle appelle la méthode
   * `render()` pour afficher l'image de la heatmap et l'étiquette associée.
   * 
   * @param {string} [label=""] - L'étiquette à afficher sous la heatmap. Si aucune étiquette n'est fournie,
   *                              rien n'est affiché.
   * 
   * @description
   * 1. La méthode commence par appeler `update()` pour vérifier si des changements ont eu lieu dans les poids
   *    ou le biais du neurone, et si oui, elle recalculera la heatmap.
   * 2. Ensuite, elle appelle `render(label)` pour afficher l'image de la heatmap générée et, si une étiquette
   *    est fournie, l'afficher sous la heatmap.
   * 3. Cette méthode permet de centraliser la mise à jour et l'affichage de la heatmap, simplifiant ainsi le processus
   *    de rendu.
   * 
   * @returns {void}
   */
  draw(label = "", displayValue = true) {

    // Appel de la méthode update pour vérifier si des changements ont eu lieu
    this.update(displayValue);

    // Appel de la méthode render pour afficher la heatmap et l'étiquette
    this.render(label);
  }

  /**
   * Met à jour la heatmap en recalculant l'image si nécessaire.
   * Cette méthode vérifie si le neurone est valide, si les poids ou le biais ont changé,
   * et si oui, elle régénère l'image de la heatmap.
   * 
   * La méthode fonctionne comme suit :
   * - Si le neurone est valide et possède une méthode `predict`, elle compare les poids et le biais actuels
   *   avec les précédents pour déterminer s'il y a eu des changements.
   * - Si des changements sont détectés, la méthode marque la heatmap comme devant être réaffichée.
   * - Si des modifications sont détectées (poids ou biais), l'image de la heatmap est régénérée en appelant `generateHeatmap()`.
   * 
   * @description
   * 1. Vérifie la validité du neurone et la présence de la méthode `predict` pour éviter des erreurs lors des calculs.
   * 2. Compare les poids actuels du neurone avec les poids précédemment stockés dans `lastWeight` pour détecter des changements.
   * 3. Compare le biais actuel du neurone avec le biais précédemment stocké dans `lastBias` pour détecter un changement.
   * 4. Si un changement est détecté, le drapeau `needsDisplay` est défini à `true`, signalant qu'une mise à jour de la heatmap est nécessaire.
   * 5. Si `needsDisplay` est `true`, la méthode appelle `generateHeatmap()` pour recalculer l'image de la heatmap et la mettre en cache.
   * 
   * @returns {void}
   */
  update(displayValue = true) {
    // Vérifie si le neurone est valide et possède la méthode predict
    if (!this.neuron || typeof this.neuron.predict !== "function") {
      console.warn("[HeatmapView] Neurone invalide ou méthode predict absente :", this.neuron);
      return;
    }

    if (displayValue) {
      // Vérifie si les poids ou le biais ont changé
      for (let i = 0; i < this.neuron.getWeights().length; i++) {
        if (this.neuron.getWeights()[i] !== this.lastWeight[i]) {
          this.needsDisplay = true;
          break; // Dès qu'un changement de poids est détecté, on marque le besoin d'affichage
        }
      }

      // Vérifie si le biais a changé
      if (this.neuron.getBias() !== this.lastBias) {
        this.needsDisplay = true;
      }
    }

    // Si la heatmap doit être mise à jour, on la génère
    if (this.needsDisplay) {

      // Recalcule l'image de la heatmap et mise en cache
      if (displayValue) {
        // Heatmap réel de la fonction d'activation
        this.generateHeatmap(this.neuron);
      }
      else {

        // On utilise un neuron fantoche pour produire une heatmap d'activation fixe
        const neuron = new Neuron();
        neuron.bias = 0; // Biais nul pour une heatmap fixe
        neuron.activation = this.neuron.activation; // Fonction d'activation du neurone d'origine

        this.generateHeatmap(neuron);
      }
    }

    this.needsDisplay = false; // Réinitialise le drapeau d'affichage après la mise à jour
  }

  /**
   * Affiche la heatmap stockée en cache ainsi que, éventuellement, une étiquette sous la heatmap.
   * Cette méthode utilise l'image précalculée dans `cachedImage` et la dessine sur le canvas. Si une étiquette
   * est fournie en paramètre, elle est affichée sous la heatmap.
   * 
   * @param {string} [label=""] - L'étiquette à afficher sous la heatmap. Si aucune étiquette n'est fournie,
   *                              rien n'est affiché.
   * 
   * @description
   * 1. La méthode commence par vérifier si une image est disponible dans `cachedImage`. Si aucune image n'est
   *    présente en cache, un avertissement est affiché et la méthode s'arrête sans afficher quoi que ce soit.
   * 2. L'image de la heatmap stockée dans `cachedImage` est ensuite affichée sur le canvas en utilisant la méthode
   *    `putImageData()`, qui place l'image aux coordonnées `(this.x, this.y)` sur le canvas.
   * 3. Si une étiquette est fournie en paramètre, elle est affichée juste en dessous de la heatmap. L'étiquette est
   *    dessinée avec une couleur de texte grise (`#ccc`) et une taille de police de `12px sans-serif`.
   * 4. Le contexte du canvas est restauré après l'affichage pour réinitialiser toutes les propriétés de style modifiées.
   * 
   * @returns {void}
   */
  render(label = "") {
    if (!this.cachedImage) {
      console.warn("[HeatmapView] Aucune image en cache. Utilisez update() pour la recalculer.");
      return; // Si l'image n'est pas disponible, afficher un avertissement et ne rien faire.
    }

    this.ctx.save(); // Sauvegarder l'état actuel du contexte du canvas

    // Affichage de l'image de la heatmap stockée en cache
    this.ctx.putImageData(this.cachedImage, this.x, this.y);

    // Affichage de l'étiquette sous la heatmap (si une étiquette est fournie)
    if (label) {

      if (this.colorMode === "dark") {
        this.ctx.fillStyle = "#ccc";  // Couleur du texte (gris) pour le mode sombre
      }
      else if (this.colorMode === "light") {
        this.ctx.fillStyle = "#000";  // Couleur du texte (noir) pour le mode clair
      }

      this.ctx.font = "11px sans-serif";  // Taille et style de la police
      this.ctx.fillText(label, this.x, this.y - 4);  // Dessiner l'étiquette sous la heatmap
    }

    this.ctx.restore(); // Restaurer l'état initial du contexte du canvas
  }


  /**
   * Génère une heatmap représentant l'activation d'un neurone sur une grille de taille `size`.
   * Cette méthode pré-calcul les activations pour chaque pixel de la heatmap, puis remplit l'image en fonction
   * des activations, et enfin, dessine la courbe d'activation par-dessus si nécessaire.
   * 
   * Le calcul des activations est effectué à partir de l'entrée normalisée (entre 0 et 1) pour chaque pixel
   * de l'image, et la couleur des pixels est déterminée en fonction de l'activation (vert pour actif, rouge pour inactif).
   * 
   * La méthode utilise également un tableau 1D pour stocker les activations pré-calculées, ce qui permet
   * d'optimiser le processus de dessin en réduisant les recalculs des activations pendant le rendu de l'image.
   * 
   * @description
   * 1. Crée un tableau `activationArray` de taille `size * size` pour stocker les activations précalculées des neurones
   *    pour chaque pixel de la heatmap.
   * 2. Calcule l'activation de chaque neurone pour chaque position `(x, y)` de la grille.
   * 3. Remplit l'image de la heatmap en utilisant les activations précalculées et génère les couleurs des pixels (vert/rouge).
   * 4. Dessine une courbe d'activation en utilisant les activations précalculées pour visualiser l'activation du neurone.
   * 5. Sauvegarde l'image générée dans le cache pour une utilisation future et marque l'image comme nécessitant un affichage.
   * 
   * @returns {void}
   */
  generateHeatmap(neuron) {

    // Créer un nouvel objet ImageData pour stocker les données de l'image de la heatmap
    const image = this.ctx.createImageData(this.size, this.size);

    let nInputs = neuron.weights.length;
    if (this.displayResultHeatmapIfPossible && nInputs == 1) {
      // Mode d'affichage uniquement valide pour 1 entrée
      for (let j = 0; j < this.size; j++) {
        for (let i = 0; i < this.size; i++) {
          const x = i / this.size;
          const y = 1 - j / this.size;

          const out = neuron.predict([x]);
          const act = Math.max(0, Math.min(1, out)); // Clamp entre 0 et 1

          const r = Math.floor(255 * (1 - act));
          const g = Math.floor(255 * act);
          const idx = (j * this.size + i) * 4;

          image.data[idx] = r;
          image.data[idx + 1] = g;
          image.data[idx + 2] = 0;
          image.data[idx + 3] = 255;
        }
      }
    }
    else if (this.displayResultHeatmapIfPossible && nInputs == 2) {
      for (let j = 0; j < this.size; j++) {
        for (let i = 0; i < this.size; i++) {
          const x = i / this.size;
          const y = 1 - j / this.size;

          const out = neuron.predict([x, y]);
          const act = Math.max(0, Math.min(1, out)); // Clamp entre 0 et 1

          const r = Math.floor(255 * (1 - act));
          const g = Math.floor(255 * act);
          const idx = (j * this.size + i) * 4;

          image.data[idx] = r;
          image.data[idx + 1] = g;
          image.data[idx + 2] = 0;
          image.data[idx + 3] = 255;
        }
      }
    }
    else {
      // Créer un tableau pour stocker les activations précalculées pour chaque pixel (x, y)
      const activationArray = new Array(this.size * this.size);

      // Précalculer les activations pour chaque pixel (x, y)
      // Chaque itération calcule l'activation du neurone en fonction de sa position dans la grille
      for (let j = 0; j < this.size; j++) {
        for (let i = 0; i < this.size; i++) {
          const x = i / this.size;    // Normalisation de x entre 0 et 1
          const y = 1 - j / this.size; // Inverser Y pour que (0, 0) soit en bas à gauche

          let multFactor = 10;
          // Pas de multiplication pour l'activation binaire ou linéaire bornée
          if (neuron.activation === ActivationFunctions.binary || neuron.activation === ActivationFunctions.linearBounded) {
            multFactor = 10;
          }

          // Calculer l'activation du neurone pour la position (x, y) en tenant compte du biais
          const out = neuron.activate(((x - 0.5) * multFactor) + neuron.bias);
          const act = Math.max(0, Math.min(1, out)); // Limite la sortie entre 0 et 1 (clipping)

          // Calculer l'index du tableau 1D pour stocker l'activation de ce pixel
          const idx = j * this.size + i;

          // Enregistrer l'activation dans le tableau précalculé
          activationArray[idx] = act;
        }
      }

      // Remplir l'image de la heatmap avec les données de couleur en fonction des activations précalculées
      for (let j = 0; j < this.size; j++) {
        for (let i = 0; i < this.size; i++) {
          const idx = (j * this.size + i) * 4;  // Index pour accéder aux composantes RGBA dans image.data
          const act = activationArray[j * this.size + i]; // Récupérer l'activation pour ce pixel

          // Calculer les couleurs des pixels en fonction de l'activation (vert si actif, rouge si inactif)
          const r = Math.floor(255 * (1 - act)); // Rouge (plus l'activation est faible, plus le rouge est élevé)
          const g = Math.floor(255 * act);       // Vert (plus l'activation est élevée, plus le vert est élevé)

          // Remplir les composants RGBA de l'image
          image.data[idx] = r;       // Composant rouge
          image.data[idx + 1] = g;   // Composant vert
          image.data[idx + 2] = 0;   // Composant bleu (fixé à 0 pour une heatmap bicolore)
          image.data[idx + 3] = 255; // Alpha (opacité totale)
        }
      }

      // Dessiner la courbe d'activation par-dessus la heatmap en utilisant les activations précalculées
      this.drawActivationCurve(image, activationArray);
    }

    // Sauvegarder l'image générée dans le cache pour utilisation ultérieure
    this.cachedImage = image;
    this.lastWeight = neuron.getWeights();  // Sauvegarder les poids du neurone
    this.lastBias = neuron.getBias();      // Sauvegarder le biais du neurone
    this.needsDisplay = true;  // Marquer que l'affichage doit être mis à jour
  }

  /**
   * Dessine la courbe d'activation sur l'image en utilisant les activations pré-calculées.
   * Cette méthode prend un tableau d'activations pour chaque position `(x, y)` dans la grille
   * et dessine une courbe continue reliant ces points. La courbe représente l'activation du neurone
   * pour les différentes entrées dans la grille, avec un ajustement de l'échelle pour les afficher sur
   * l'image. La courbe est tracée en noir.
   * 
   * @param {ImageData} image - L'image sur laquelle dessiner la courbe d'activation.
   * @param {Array} activationArray - Un tableau d'activations précalculées pour chaque pixel de l'image.
   *                                     Chaque entrée dans le tableau correspond à l'activation du neurone
   *                                     pour une position `(x, y)` normalisée.
   * 
   * @description
   * 1. La méthode calcule les coordonnées de chaque point de la courbe d'activation en fonction
   *    du tableau `activationArray`, qui contient les activations normalisées pour chaque pixel.
   * 2. Pour chaque activation, la méthode convertit les coordonnées normalisées `(x, y)` en
   *    coordonnées d'image et arrondit ces valeurs pour éviter les erreurs de calcul liées aux pixels flottants.
   * 3. Après avoir calculé les coordonnées pour chaque pixel, elle relie les points adjacents pour
   *    dessiner la courbe d'activation sur l'image.
   * 4. La courbe est tracée en noir, et la méthode utilise `drawLine` pour dessiner des lignes
   *    continues entre les points.
   * 
   * @returns {void}
   */
  drawActivationCurve(image, activationArray) {
    const size = this.size;
    let tab = [];

    // Calculer les coordonnées de la courbe d'activation en utilisant le tableau precalculé
    for (let i = 0; i < size; i++) {
      let norm_x = i / size;

      // Récupérer l'activation pré-calculée depuis le tableau pour la coordonnée (x)
      let norm_y = 1 - activationArray[i]; // Activation modifiée pour ajuster l'échelle

      // Appliquer un ajustement supplémentaire pour écraser un peu
      // la courbe d'activation afin de bien visualiser les extrèmes
      norm_y = (norm_y - 0.025) * 0.90 + 0.05;

      let x = norm_x * size; // Normaliser la coordonnée X
      let y = norm_y * size; // Normaliser la coordonnée Y

      // Arrondir les coordonnées pour éviter les pixels flottants
      x = Math.round(x);
      y = Math.round(y);

      // Vérifier que les coordonnées sont dans les limites de l'image
     /* if (x <= 0 || x >= size || y < 0 || y >= size) {
        continue; // Ignorer les coordonnées hors limites
      }*/

      // Ajouter les coordonnées dans le tableau tab pour le tracé de la courbe
      tab.push({ x, y });
    }

    // Dessiner les traits pour relier les points de la courbe
    for (let i = 1; i < tab.length; i++) {
      const p1 = tab[i - 1];
      const p2 = tab[i];

      //console.log(`Dessin de la ligne de (${p1.x}, ${p1.y}) à (${p2.x}, ${p2.y})`);

      // Tracer la ligne entre les points adjacents pour dessiner la courbe
      this.drawLine(image, p1.x, p1.y, p2.x, p2.y); // Ligne du biais
    }
  }

  /**
   * Dessine une ligne entre deux points dans l'image.
   * @param {ImageData} image - L'image sur laquelle dessiner la ligne.
   * @param {number} x1 - Coordonnée X du premier point.
   * @param {number} y1 - Coordonnée Y du premier point.
   * @param {number} x2 - Coordonnée X du deuxième point.
   * @param {number} y2 - Coordonnée Y du deuxième point.
   */
  drawLine(image, x1, y1, x2, y2) {
    const size = this.size;

    const dx = Math.abs(x2 - x1);
    const dy = Math.abs(y2 - y1);
    const sx = x1 < x2 ? 1 : -1;
    const sy = y1 < y2 ? 1 : -1;
    let err = dx - dy;

    while (true) {
      // Calculer l'index du pixel dans l'image
      if (x1 >= 0 && x1 < size && y1 >= 0 && y1 < size) {
        const idx = (y1 * size + x1) * 4;

        // Dessiner la ligne en noir
        image.data[idx] = 0;     // Composant rouge
        image.data[idx + 1] = 0; // Composant vert
        image.data[idx + 2] = 255; // Composant bleu
        image.data[idx + 3] = 255; // Alpha (opacité totale)
      }

      if (x1 === x2 && y1 === y2) break;

      const e2 = err * 2;
      if (e2 > -dy) {
        err -= dy;
        x1 += sx;
      }
      if (e2 < dx) {
        err += dx;
        y1 += sy;
      }
    }
  }

  /**
   * @brief Définit le mode de couleur pour l'affichage.
   * @param {string} colorMode - Le mode de couleur à appliquer. Doit être `"light"` ou `"dark"`.
   *
   * @warning Si le mode est invalide, la méthode bascule automatiquement sur `"dark"` et affiche un `console.warn`.
   */
  setColorMode(colorMode) {
    const allowedModes = ["light", "dark"];

    if (!allowedModes.includes(colorMode)) {
      console.warn(`[HeatmapView] Mode de couleur "${colorMode}" non valide. Modes autorisés : ${allowedModes.join(", ")}`);
      this.colorMode = "dark";
      return;
    }

    this.colorMode = colorMode;
  }
}  
