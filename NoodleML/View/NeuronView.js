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
 * @class NeuronView
 * @classdesc Représente visuellement un neurone avec sa heatmap et son biais.
 * Cette classe gère l'affichage du neurone avec son heatmap et son biais associé.
 */
class NeuronView {
    /**
     * Crée une vue pour un neurone avec son heatmap et son biais.
     * 
     * @param {CanvasRenderingContext2D} ctx - Contexte de rendu 2D du canvas.
     * @param {number} x - Coordonnée x de l'emplacement du neurone sur le canvas.
     * @param {number} y - Coordonnée y de l'emplacement du neurone sur le canvas.
     * @param {number} size - Taille du neurone (largeur et hauteur).
     * @param {object} layer - Objet contenant les biais de la couche du neurone (optionnel).
     * @param {number} index - Index du neurone dans la couche (optionnel).
     * @param {object} neuron - Instance de `Perceptron` ou `Neuron` (optionnel).
     * @param {boolean} [displayResultHeatmapIfPossible=false] - Indique si la heatmap doit être 
     * @param {string} [colorMode="dark"] - Mode d'affichage de la heatmap (clair ou sombre).
     * affichée comme le résultat d'une prédiction du neurone. -Représentation possible uniquement si le neurone à au 
     * maximum deux entrées.-
     */
    constructor(ctx, x, y, size, index = null, neuron = null, displayResultHeatmapIfPossible = false, colorMode = "dark") {
        this.ctx = ctx;
        this.x = x;
        this.y = y;
        this.size = size;
        this.index = index;
        this.neuron = neuron;

        if (!this.neuron) {
            console.warn("[NeuronView] Neurone invalide :", this.neuron);
            return;
        } else if (typeof this.neuron.predict !== "function") {
            console.warn("[NeuronView] Méthode predict absente :", this.neuron);
            return;
        }
        
        // Création de la heatmap associée au neurone
        this.heatmap = new HeatmapView(ctx, x, y, size, neuron, displayResultHeatmapIfPossible);
    }

    /**
     * Dessine le neurone avec sa heatmap et son biais.
     * 
     * @param {string} label - Étiquette facultative à afficher sur la heatmap.
     */
    draw(labelDisplayMode) {
        if (!this.neuron || !this.heatmap) {
            console.warn("[NeuronView] Problème de neurone ou de heatmap");
            return;
        }

        // Dessin de la heatmap du neurone avec son nom (ex: A1)
        if (labelDisplayMode === "value") {
            this.heatmap.draw(this.neuron.name, true);
        }
        else {
            this.heatmap.draw(this.neuron.name, false);
        }

        this.ctx.save();

        // Affichage du biais sous la heatmap avec un indice simulé
        const bias = this.neuron.getBias().toFixed(2);
        const biasY = this.y + this.size + 12 - 18;

        // Affichage d'un rectangle noir semi-transparent derrière le texte du biais
        if (this.colorMode === "light") {
            this.ctx.fillStyle = "rgba(255, 255, 255, 0.5)"; // Fond blanc avec 50% de transparence
        }
        else if (this.colorMode === "dark") {
            this.ctx.fillStyle = "rgba(0, 0, 0, 0.5)"; // Fond noir avec 50% de transparence
        }

        this.ctx.fillRect(this.x, biasY - 12, this.size, 18);


        // Affichage facultatif d'un label explicite sous le biais
        if (labelDisplayMode === "value") {

            if (this.colorMode === "light") {

                this.ctx.fillStyle = "#000"; // Texte noir pour le mode clair
            }
            else if (this.colorMode === "dark") {

                this.ctx.fillStyle = "#fff"; // Texte blanc pour le mode sombre
            }

            // Affichage du biais avec un indice simulé
            this.ctx.font = "11px sans-serif";
            this.ctx.textAlign = "left";
            this.ctx.fillText("b=" + bias, this.x + 2, biasY);
        }
        else {
            this.drawSubscriptLabel("b", this.neuron.name, "", this.x + 2, biasY);
        }

        // Dessin d'un cadre autour de la heatmap
        if (this.colorMode === "light") {
            this.ctx.strokeStyle = "#000"; // Bordure noire pour le mode clair
        }
        else if (this.colorMode === "dark") {
            this.ctx.strokeStyle = "#fff"; // Bordure blanche pour le mode sombre
        }

        this.ctx.lineWidth = 1;
        this.ctx.strokeRect(this.x, this.y, this.size, this.size);
        this.ctx.restore();
    }

    /**
     * Récupère le centre du neurone (pour les calculs de connexion ou de positionnement).
     * 
     * @returns {{x: number, y: number}} Les coordonnées du centre du neurone.
     */
    getCenter() {
        return {
            x: this.x + this.size / 2,
            y: this.y + this.size / 2
        };
    }

    /**
     * Récupère l'extrémité droite du neurone (pour les calculs de connexion).
     * 
     * @returns {{x: number, y: number}} Les coordonnées de l'extrémité droite du neurone.
     */
    getRightEdge() {
        return {
            x: this.x + this.size,
            y: this.y + this.size / 2
        };
    }

    /**
     * Récupère l'extrémité gauche du neurone (pour les calculs de connexion).
     * 
     * @returns {{x: number, y: number}} Les coordonnées de l'extrémité gauche du neurone.
     */
    getLeftEdge() {
        return {
            x: this.x,
            y: this.y + this.size / 2
        };
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

        if (this.colorMode === "dark") {
            this.ctx.fillStyle = "#fff";
        }
        else if (this.colorMode === "light") {
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
     * @brief Définit le mode de couleur pour l'affichage du neurone.
     * @param {string} colorMode - Le mode de couleur à appliquer. Doit être `"light"` ou `"dark"`.
     *
     * @warning Si le mode est invalide, la méthode bascule automatiquement sur `"dark"` et affiche un `console.warn`.
     */
    setColorMode(colorMode) {
        const allowedModes = ["light", "dark"];

        if (!allowedModes.includes(colorMode)) {
            console.warn(`[NeuronView] Mode de couleur "${colorMode}" non valide. Modes autorisés : ${allowedModes.join(", ")}`);
            this.colorMode = "dark"; // Valeur par défaut
            return;
        }

        this.colorMode = colorMode;

        this.heatmap.setColorMode(colorMode); // Met à jour le mode de couleur de la heatmap
    }

    /**
     * Définit l'affichage de la heatmap des résultats si possible.
     * @param {boolean} display - Si true, le neurone affiche la heatmap si possible (nb entrées <= 2).
     * Si false, le neurone affiche la fonction d'activation.
     */
    setDisplayResultHeatmapIfPossible(display) {
        this.heatmap.displayResultHeatmapIfPossible = display;
    }
}
