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
 * @class LogicFunctionHeatmapView
 * @classdesc Affiche une heatmap binaire (0 ou 1) représentant les sorties du réseau neuronal pour une fonction logique donnée.
 * Cette classe permet de visualiser les résultats d'un réseau de neurones pour des entrées dans un espace 2D (exemple : XOR, OR, AND, etc.).
 * La heatmap est colorée en fonction de la sortie binaire de chaque neurone (0 pour rouge et 1 pour vert).
 * 
 * La méthode `draw` de cette classe génère et affiche une image représentant les activations binaires du réseau pour un ensemble d'entrées.
 * La couleur de chaque cellule de la heatmap est déterminée par la sortie binaire du réseau (0 ou 1).
 * 
 * @example
 * const heatmapView = new LogicFunctionHeatmapView(ctx, 20, 20, 160, network);
 * heatmapView.draw("XOR Function");
 */
class LogicFunctionHeatmapView {
    /**
     * Crée une nouvelle instance de la heatmap binaire basée sur les prédictions du réseau.
     * 
     * @param {CanvasRenderingContext2D} ctx - Le contexte du canvas sur lequel dessiner la heatmap.
     * @param {number} x - La position horizontale (coordonnée X) de la heatmap sur le canvas.
     * @param {number} y - La position verticale (coordonnée Y) de la heatmap sur le canvas.
     * @param {number} size - La taille de la heatmap (largeur et hauteur), qui définit la taille du carré sur le canvas.
     * @param {NeuralNetwork} network - Le réseau neuronal à utiliser pour les prédictions (entrée -> sortie).
     */
    constructor(ctx, x, y, size, network) {
        this.ctx = ctx;
        this.x = x;
        this.y = y;
        this.size = size;
        this.network = network;
        this.binaryMode = true; // Mode binaire par défaut
    }

    /**
     * Dessine la heatmap binaire en fonction des sorties du réseau pour différentes entrées.
     * Chaque cellule de la heatmap représente la sortie binaire du réseau pour une paire d'entrées dans un espace 2D.
     * 
     * @param {string} title - Titre qui sera affichée en dessus de la heatmap, par exemple "XOR" ou "AND".
     */
    draw(title = "") {
        const gridSize = this.size; // Taille de la grille de la heatmap
        const imageData = this.ctx.createImageData(this.size, this.size); // Crée une image vide de la heatmap

        // Remplissage de la heatmap avec les valeurs basées sur les prédictions du réseau
        for (let i = 0; i < gridSize; i++) {
            for (let j = 0; j < gridSize; j++) {
                const x = i / gridSize;
                const y = 1 - j / gridSize;
                const pred = this.network.predict([x, y])[0]; // Prédiction du réseau pour cette position d'entrée

                let r, g, b;

                // Si le mode binaire est activé
                if (this.getBinaryMode()) {
                    // Conversion de la sortie en valeur binaire (0 ou 1)
                    const binaryValue = pred >= 0.5 ? 1 : 0;

                    // Détermination de la couleur à utiliser en fonction de la valeur binaire
                    if (binaryValue === 1) {
                        r = 0; g = 255; b = 0;  // Vert pour 1
                    } else {
                        r = 255; g = 0; b = 0;  // Rouge pour 0
                    }
                }
                else {
                    // Mode non-binaire : dégradé de rouge à vert en fonction de la sortie
                    r = Math.floor((1 - pred) * 255);  // Le rouge diminue avec l'activation
                    g = Math.floor(pred * 255);        // Le vert augmente avec l'activation
                    b = 0;                            // Bleu reste à 0
                }

                // Remplissage du pixel dans l'image
                const idx = (j * gridSize + i) * 4;
                imageData.data[idx] = r;
                imageData.data[idx + 1] = g;
                imageData.data[idx + 2] = b;
                imageData.data[idx + 3] = 255; // Opacité à 100%
            }
        }

        // Placer l'image dans le canvas
        this.ctx.putImageData(imageData, this.x, this.y);

        // Affichage du titre
        if (title) {
            this.ctx.fillStyle = "#ccc";
            this.ctx.font = "16px sans-serif";
            this.ctx.fillText(title, this.x + (this.size / 2) - (this.ctx.measureText(title).width / 2), this.y - 10);
        }

        // Affichage d'un cadre autour de l'image
        this.ctx.strokeStyle = "#fff";
        this.ctx.lineWidth = 1;
        this.ctx.strokeRect(this.x, this.y, this.size, this.size);
    }

    drawLogicalPoints(targets) {

        const labels = this.network.inputNames;
        const inputs = [[0, 0], [0, 1], [1, 0], [1, 1]];

        // Points
        inputs.forEach(([x, y], i) => {
            const px = this.x + x * this.size + (x === 0 ? -4 : x === 1 ? 4 : 0);
            const py = this.y + (1 - y) * this.size + (y === 0 ? 4 : y === 1 ? -4 : 0);
            const color = targets[i][0] ? "#0f0" : "#f00";
            this.ctx.fillStyle = color;
            this.ctx.beginPath();
            this.ctx.arc(px, py, 6, 0, 2 * Math.PI);
            this.ctx.fill();
            this.ctx.fillStyle = "#fff";
            this.ctx.font = "12px Arial";

            // Modifions la position du texte
            const textOffsetY = y === 0 ? 22 : -12;


            const label = `${labels[0]}=${x},${labels[1]}=${y}`;
            const metrics = this.ctx.measureText(this.label);
            this.ctx.fillText(label, px - metrics.width / 2 - 8, py + textOffsetY);
        });
    }



    /**
     * Définit le mode binaire pour la classe. 
     * Si `binaryMode` est activé, le réseau fonctionnera en mode binaire 
     * pour des prédictions (0 ou 1).
     *
     * @param {boolean} binaryMode - L'état du mode binaire, `true` pour binaire, `false` sinon.
     */
    setBinaryMode(binaryMode) {
        this.binaryMode = binaryMode;
    }

    /**
     * Récupère l'état du mode binaire de la classe.
     * Indique si le réseau utilise un mode binaire pour la prédiction.
     *
     * @returns {boolean} - Le mode binaire, `true` si activé, `false` sinon.
     */
    getBinaryMode() {
        return this.binaryMode;
    }

    /**
     * Définit le réseau neuronal à utiliser dans cette classe.
     * Cette méthode permet de lier un réseau de neurones (NeuralNetwork) à cette classe.
     *
     * @param {NeuralNetwork} network - L'instance du réseau neuronal.
     */
    setNetwork(network) {
        this.network = network;
    }

    /**
     * Récupère l'instance du réseau neuronal actuellement utilisée dans cette classe.
     *
     * @returns {NeuralNetwork} - L'instance du réseau neuronal.
     */
    getNetwork() {
        return this.network;
    }
}
