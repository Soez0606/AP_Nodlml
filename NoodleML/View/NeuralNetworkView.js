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
 * @class NeuralNetworkView
 * @classdesc Cette classe affiche les neurones du réseau sous forme de `NeuronView` et les connexions entre eux.
 * Elle est responsable de la gestion de la disposition des neurones et de la mise à jour de l'affichage sur le canvas.
 */
class NeuralNetworkView {
  /**
   * Crée une instance de `NeuralNetworkView`.
   * Cette méthode initialise les propriétés de la vue du réseau et organise les neurones et leurs connexions.
   * 
   * @param {CanvasRenderingContext2D} ctx - Contexte 2D du canvas où le réseau sera dessiné.
   * @param {Object} canvasRect - Rectangle représentant la position et les dimensions du canvas.
   * @param {NeuralNetwork} network - L'objet représentant le réseau de neurones à afficher.
   * @param {number} [neuronSize=80] - Taille des neurones (par défaut 80).
   * @param {boolean} [displayResultHeatmapIfPossible=false] - Indique si la heatmap doit être affichée comme 
   * @param {string} [colorMode="light"] - Mode d'affichage des couleurs ("light" ou "dark").
   * le résutat des ses sorties (par défaut false). -Représentation possible uniquement si le neurone à au 
   * maximum deux entrées.-
   * 
   * @description
   * 1. Le constructeur initialise les paramètres nécessaires pour afficher le réseau de neurones sur un canvas.
   * 2. Il calcule la taille et l'espacement des couches de neurones en fonction de la taille du canvas et de la taille des neurones.
   * 3. La méthode crée les vues pour les neurones (via `NeuronView`) et les connexions entre eux.
   * 4. Elle place les neurones sur le canvas de manière à représenter graphiquement les couches du réseau.
   * 5. La méthode crée également des connexions entre les couches du réseau.
   * 
   * @returns {void}
   */
  constructor(ctx, canvasRect, network, neuronSize = 80, displayResultHeatmapIfPossible = false, colorMode = "dark") {
    this.ctx = ctx;                   // Contexte 2D du canvas
    this.network = network;           // Réseau de neurones à afficher
    this.neuronSize = neuronSize;     // Taille des neurones à afficher
    this.layerSpacing = 150;          // Espacement entre les couches de neurones
    this.neuronSpacing = 35;          // Espacement entre les neurones dans une couche
    this.availableHeight = canvasRect.height; // Hauteur disponible du canvas

    this.neuronViews = [];            // Tableau pour stocker les vues des neurones
    this.connections = [];            // Tableau pour stocker les connexions entre neurones

    this.canvasRect = canvasRect;
    this.x = canvasRect.x;            // Position X du réseau sur le canvas
    this.y = canvasRect.y;            // Position Y du réseau sur le canvas
    this.width = canvasRect.width;    // Largeur du canvas
    this.height = canvasRect.height;  // Hauteur du canvas
    this.displayResultHeatmapIfPossible = displayResultHeatmapIfPossible; // Mode d'affichage de la heatmap
    this.labelDisplayMode = "text"; // Mode d'affichage des labels (par défaut "label")

    const allowedModes = ["light", "dark"];
    if (!allowedModes.includes(colorMode)) {
      console.warn(`[NeuralNetworkView] Mode de couleur "${colorMode}" non valide. Modes autorisés : ${allowedModes.join(", ")}`);
      colorMode = "dark"; // Valeur par défaut
    }

    this.colorMode = colorMode;

    this.buildView();                 // Construire la vue du réseau
  }

  /**
`  * @brief Génère la vue graphique complète du réseau de neurones.
   * @description
   * Génère la vue graphique complète du réseau de neurones sur le canvas :
   * - Crée et positionne les `NeuronView` pour chaque couche du réseau.
   * - Positionne les points d'entrée (cercles) et de sortie (cercles) de façon fixe.
   * - Assure un espacement horizontal **régulier** entre les couches (entrées → neurones cachés → sortie).
   * - Ajuste finement la position de la première et de la dernière couche pour respecter un équilibre visuel :
   *   - La première couche est légèrement **rapprochée des entrées**.
   *   - La dernière couche est légèrement **décalée vers la gauche** pour éviter de coller aux cercles de sortie.
   * - Génère toutes les connexions (`NeuralConnectionView`) entre les couches, avec ou sans libellé selon la configuration.
   * 
   * @returns {void}
   */
  buildView() {
    // Recherche de la taille de la couche la plus grande
    let maxLayerSize = 0;
    for (let i = 1; i < this.network.layerSizes.length; i++) {
      const layerSize = this.network.layerSizes[i];
      if (layerSize > maxLayerSize) {
        maxLayerSize = layerSize;
      }
    }

    const layers = this.network.layers;
    let step = this.availableHeight / maxLayerSize;
    this.neuronSize = Math.min(this.neuronSize, step - this.neuronSpacing);

    const totalLayers = this.network.layerSizes.length;

    const xInput = this.x;
    const xOutput = this.x + this.width;
    const xStep = (xOutput - xInput) / (totalLayers);

    // Construction des neurones (NeuronView)
    for (let l = 0; l < layers.length; l++) {
      const layer = layers[l];
      const count = layer.length;
      const views = [];

      const spacing = this.neuronSpacing;
      const totalHeight = count * this.neuronSize + (count - 1) * spacing;
      const yLayerOffset = this.y + (this.height - totalHeight) / 2;
      let xLayerOffset = xInput + ((l + 1) * xStep) - this.neuronSize / 2; // 🔄 plus de +1 ici

      if (l === 0) {
        xLayerOffset = xLayerOffset - this.neuronSize / 4;
      }
      else if (l === totalLayers - 2) {
        xLayerOffset = xLayerOffset + this.neuronSize / 2;
      }


      for (let i = 0; i < count; i++) {
        const nx = xLayerOffset;
        const ny = yLayerOffset + i * (this.neuronSize + spacing);
        const neuron = layer[i];
        views.push(new NeuronView(this.ctx, nx, ny, this.neuronSize, i, neuron, this.displayResultHeatmapIfPossible));
      }

      this.neuronViews.push(views);
    }

    // Connexions entre les couches (internes)
    for (let l = 0; l < this.neuronViews.length - 1; l++) {
      const fromLayer = this.neuronViews[l];
      const toLayer = this.neuronViews[l + 1];

      for (let j = 0; j < toLayer.length; j++) {
        const to = toLayer[j];
        const targetNeuron = this.network.layers[l + 1][j];
        const step = to.size / (fromLayer.length + 1);

        for (let i = 0; i < fromLayer.length; i++) {
          const from = fromLayer[i];
          const currentNeuron = this.network.layers[l][i];

          // Connexion explicite
          const connection = targetNeuron.connections.find(c => c.index === i);
          if (!connection) continue; // Ne pas dessiner si pas de connexion

          const weightFn = () => connection.weight;
          const toPos = { x: to.x, y: to.y + step * (i + 1) };

          const conn = new NeuralConnectionView(this.ctx, from.getRightEdge(), toPos, weightFn);
          conn.setDisplayMode("arrow_with_label");
          conn.label = `${currentNeuron.name},${targetNeuron.name}`;
          this.connections.push(conn);
        }
      }
    }

    // Positionnement des entrées virtuelles
    const inputCount = this.network.layerSizes[0];
    const outputLayer = this.neuronViews.at(-1);
    const centerY = (outputLayer[0].y + outputLayer.at(-1).y + outputLayer.at(-1).size) / 2;
    const inputSpacing = this.availableHeight / (inputCount + 1);

    this.inputPositions = [];
    for (let i = 0; i < inputCount; i++) {
      const x = xInput;
      const y = centerY - (inputSpacing * (inputCount - 1) / 2) + i * inputSpacing;
      const label = this.network.inputNames?.[i] || `x${i}`;
      this.inputPositions.push({ x, y, label });
    }

    // Connexions des entrées vers la première couche
    const firstLayer = this.neuronViews[0];
    for (let i = 0; i < this.inputPositions.length; i++) {
      const input = this.inputPositions[i];
      let firstLabel = true;

      for (let j = 0; j < firstLayer.length; j++) {
        const neuron = firstLayer[j];
        const step = neuron.size / (this.inputPositions.length + 1);
        const toPos = { x: neuron.x, y: neuron.y + step * (i + 1) };
        const targetNeuron = this.network.layers[0][j];

        // Connexion explicite
        const connection = targetNeuron.connections.find(c => c.index === i);
        if (!connection) continue; // Pas de connexion, on ignore

        const weightFn = () => connection.weight;

        const label = `${input.label},${neuron.neuron.name}`;
        const conn = new NeuralConnectionView(
          this.ctx,
          { x: input.x, y: input.y },
          toPos,
          weightFn,
          input.label,
          label,
          "left"
        );

        conn.setDisplayMode(firstLabel ? "arrow_with_circle_at_left_and_label" : "arrow_with_label");
        firstLabel = false;

        this.connections.push(conn);
      }
    }


    // Positionnement des sorties virtuelles
    const outputCount = this.network.layerSizes.at(-1);
    this.outputPositions = [];

    const outputSpacing = this.availableHeight / (outputCount + 1);
    for (let i = 0; i < outputCount; i++) {
      const x = xOutput;
      const y = centerY - (outputSpacing * (outputCount - 1) / 2) + i * outputSpacing;
      const label = this.network.outputNames?.[i] || `ŷ${i}`;
      this.outputPositions.push({ x, y, label });
    }

    // Connexions entre neurones de sortie et sorties virtuelles
    const lastLayer = this.neuronViews.at(-1);
    for (let i = 0; i < outputCount; i++) {
      const output = this.outputPositions[i];
      const neuron = lastLayer[i];

      let title = `${output.label} = f(`;
      title += this.inputPositions.map(inp => inp.label).join(", ") + ")";

      const conn = new NeuralConnectionView(
        this.ctx,
        { x: neuron.x + neuron.size, y: neuron.y + neuron.size / 2 },
        { x: output.x, y: output.y },
        undefined,
        title,
        "",
        "right"
      );

      conn.setDisplayMode("arrow_with_circle_at_right");
      this.connections.push(conn);
    }

    // Mise à jour des connexions avec le mode d'affichage des étiquettes
    this.setColorMode(this.colorMode);
  }

  /**
   * @brief Définit le mode de couleur pour l'affichage du réseau.
   * @param {string} colorMode - Le mode de couleur à appliquer. Doit être `"light"` ou `"dark"`.
   *
   * @warning Si le mode est invalide, la méthode bascule automatiquement sur `"dark"` et affiche un `console.warn`.
   */
  setColorMode(colorMode) {

    const allowedModes = ["light", "dark"];
    if (!allowedModes.includes(colorMode)) {
      console.warn(`[NeuralNetworkView] Mode de couleur "${colorMode}" non valide. Modes autorisés : ${allowedModes.join(", ")}`);
      colorMode = "dark"; // Valeur par défaut
    }

    this.colorMode = colorMode;

    // Mise à jour des couleurs des connexions et des neurones
    for (const conn of this.connections) {
      conn.setColorMode(colorMode);
    }

    for (const layer of this.neuronViews) {
      for (const neuronView of layer) {
        neuronView.setColorMode(colorMode);
      }
    }

    // Redessine le réseau avec les nouvelles couleurs
    this.draw();
  }

  /**
   * Dessine le réseau de neurones sur le canvas.
   * 
   * @description
   * Cette méthode efface la zone définie par `canvasRect`, puis redessine dynamiquement :
   * 
   * 1. Les **connexions** entre les neurones (avec ou sans affichage des poids selon le `labelDisplayMode`).
   * 2. Les **neurones** de chaque couche via leur `NeuronView`, avec leurs éventuelles heatmaps.
   * 3. Les **poids numériques** (`drawWeight`) ou **étiquettes symboliques** (`drawLabel`) des connexions.
   * 
   * Le mode `labelDisplayMode` contrôle le rendu :
   * - `"value"` → affiche les poids réels sur les connexions.
   * - `"text"`  → affiche les noms symboliques (`x₁,x₂`) et titres mathématiques (ex: `ŷ = f(x₁,x₂)`).
   * 
   * @returns {void}
   */
  draw() {

    if (this.colorMode === "light") {
      this.ctx.fillStyle = "#fff"; // Texte noir pour le mode clair
    }
    else if (this.colorMode === "dark") {
      this.ctx.fillStyle = "#000"; // Texte blanc pour le mode sombre
    }

    // TODO: a supprimer
    //this.ctx.fillRect(this.x, this.y, this.width, this.height);

    this.ctx.clearRect(this.x, this.y, this.width, this.height);

    // Dessin des connexions
    for (const conn of this.connections) {
      if (this.labelDisplayMode === "value") {
        // Dessine la connexion (si applicable)
        conn.drawConnection(true);
      }
      else {
        // Dessine la connexion sans couleur pour représentation 
        // du réseau de façon purement théorique
        conn.drawConnection(false);
      }
    }

    // Dessin des neurones
    for (const layer of this.neuronViews) {
      for (const neuron of layer) {
        neuron.draw(this.labelDisplayMode);
      }
    }

    // Dessin des poids
    for (const conn of this.connections) {
      if (this.labelDisplayMode === "value") {
        conn.drawWeight(); // Dessine le poids (si applicable)
      }
      else {
        //this.drawConnection(false); // Dessine la connexion sans couleur
        conn.drawLabel();
      }
    }

    // Affichage d'un rectantgle englobant le réseau pour débug
    //this.ctx.strokeStyle = "#cccccc";
    //this.ctx.lineWidth = 2;
    //this.ctx.strokeRect(this.x, this.y, this.width, this.height);
  }

  /**
   * Définit le mode d'affichage des étiquettes pour les connexions et les neurones.
   * 
   * @param {string} labelDisplayMode - Mode d'affichage des étiquettes :
   *   - `"value"` : affiche les poids numériques des connexions.
   *   - `"text"` (ou tout autre) : affiche uniquement les noms des connexions.
   * 
   * @description
   * Cette méthode met à jour le mode d'affichage global du réseau, influençant la façon dont les connexions
   * et les neurones sont dessinés :
   * 
   * - En mode `"value"`, les poids réels des connexions sont affichés (via `drawWeight()`).
   * - En mode `"text"`, seules les étiquettes symboliques (comme `x₁,x₂`) sont dessinées (via `drawLabel()`).
   * 
   * Après modification du mode, la méthode force aussi le rafraîchissement des heatmaps des neurones
   * pour s'assurer que leur affichage est mis à jour lors du prochain `draw()`.
   * 
   * @returns {void}
   */
  setLabelDisplayMode(labelDisplayMode) {
    this.labelDisplayMode = labelDisplayMode;

    // passage en revue de tous les vues des neurones
    for (const layer of this.neuronViews) {
      for (const neuronView of layer) {
        // On force la heatmap à se redessiner pour régénérer le cache
        neuronView.heatmap.needsDisplay = true;
      }
    }
  }

  /**
   * Définit l'affichage de la heatmap des résultats si possible.
   * @param {boolean} heatmap - Si true, le neurone affiche la heatmap si possible (nb entrées <= 2).
   * Si false, le neurone affiche la fonction d'activation.
   */
  setDisplayResultHeatmapIfPossible(heatmap) {
    this.displayResultHeatmapIfPossible = heatmap;

    // passage en revue de tous les vues des neurones
    for (const layer of this.neuronViews) {
      for (const neuronView of layer) {
        neuronView.setDisplayResultHeatmapIfPossible(heatmap);
        // On force la heatmap à se redessiner pour régénérer le cache
        neuronView.heatmap.needsDisplay = true;
      }
    }
  }
}
