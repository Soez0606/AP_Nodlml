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

class ActivationGraph {
  /**
   * Crée un graphique pour visualiser des fonctions d'activation.
   * @param {HTMLCanvasElement} canvas - Le canvas où dessiner le graphique.
   * @param {Object} options - Options de configuration.
   * @param {string} [options.title] - Titre à afficher au-dessus du graphique.
   * @param {number} [options.xMin=-5] - Borne minimum sur l'axe x.
   * @param {number} [options.xMax=5] - Borne maximum sur l'axe x.
   * @param {number} [options.yMin=-2] - Borne minimum sur l'axe y.
   * @param {number} [options.yMax=2] - Borne maximum sur l'axe y.
   * @param {Object} [options.colors] - Couleurs personnalisées pour les fonctions.
   * @param {Object} [options.active] - Fonctions à activer/désactiver par défaut.
   */
  constructor(canvas, options = {}) {
    this.canvas = canvas;
    this.ctx = canvas.getContext('2d');

    // Titre du graphique
    this.title = options.title || "";

    this.labelX = options.labelX || "";
    this.labelY = options.labelY || "";


    // Bornes du repère
    this.xMin = options.xMin ?? -5;
    this.xMax = options.xMax ?? 5;
    this.yMin = options.yMin ?? -2;
    this.yMax = options.yMax ?? 2;

    // Couleurs par défaut
    const defaultColors = {
      linear: 'green',
      sigmoid: 'blue',
      relu: 'orange',
      tanh: 'purple'
    };

    // Personnalisation des couleurs et fonctions activées
    const colorConfig = options.colors || {};
    const activeConfig = options.active || {}; // ⚠️ pas de fallback "true" ici

    // Définition des fonctions d'activation
    this.functions = {
      binary: {
        color: colorConfig.binary || defaultColors.linear,
        active: activeConfig.binary === true,
        fn: ActivationFunctions.binary.f
      },
      linear: {
        color: colorConfig.linear || defaultColors.linear,
        active: activeConfig.linear === true,
        fn: ActivationFunctions.linearBounded.f
      },
      sigmoid: {
        color: colorConfig.sigmoid || defaultColors.sigmoid,
        active: activeConfig.sigmoid === true,
        fn: ActivationFunctions.sigmoid.f
      },
      relu: {
        color: colorConfig.relu || defaultColors.relu,
        active: activeConfig.relu === true,
        fn: ActivationFunctions.relu.f
      },
      tanh: {
        color: colorConfig.tanh || defaultColors.tanh,
        active: activeConfig.tanh === true,
        fn: ActivationFunctions.tanh.f
      }
    };

    // Paramètres du neurone simulé
    this.w = 1;
    this.b = 0;

    // Premier rendu
    this.draw();
  }


  // Change le poids et redessine
  setWeight(w) {
    this.w = w;
    this.draw();
  }

  // Change le biais et redessine
  setBias(b) {
    this.b = b;
    this.draw();
  }

  // Active ou désactive une fonction donnée
  toggleFunction(name, active) {
    if (this.functions[name]) {
      this.functions[name].active = active;
      this.draw();
    }
  }

  // Conversion d'une coordonnée x réelle en pixels
  xToPx(x) {
    return ((x - this.xMin) / (this.xMax - this.xMin)) * this.canvas.width;
  }

  // Conversion d'une coordonnée y réelle en pixels (inversée)
  yToPx(y) {
    return this.canvas.height - ((y - this.yMin) / (this.yMax - this.yMin)) * this.canvas.height;
  }

  // Dessine un quadrillage léger
  drawGrid() {
    const { ctx, canvas } = this;
    ctx.save();

    ctx.strokeStyle = '#eee';
    ctx.lineWidth = 1;

    for (let x = Math.ceil(this.xMin); x <= this.xMax; x++) {
      const px = this.xToPx(x);
      ctx.beginPath();
      ctx.moveTo(px, 0);
      ctx.lineTo(px, canvas.height);
      ctx.stroke();
    }

    for (let y = Math.ceil(this.yMin); y <= this.yMax; y++) {
      const py = this.yToPx(y);
      ctx.beginPath();
      ctx.moveTo(0, py);
      ctx.lineTo(canvas.width, py);
      ctx.stroke();
    }

    ctx.restore();
  }

  // Dessine les axes principaux (X et Y) et les labels
  drawAxes() {
    const { ctx } = this;
    ctx.save();

    ctx.strokeStyle = '#000';
    ctx.lineWidth = 2;

    // Axe horizontal Y = 0
    if (this.yMin < 0 && this.yMax > 0) {
      const y0 = this.yToPx(0);
      ctx.beginPath();
      ctx.moveTo(0, y0);
      ctx.lineTo(this.canvas.width, y0);
      ctx.stroke();
    }

    // Axe vertical X = 0
    if (this.xMin < 0 && this.xMax > 0) {
      const x0 = this.xToPx(0);
      ctx.beginPath();
      ctx.moveTo(x0, 0);
      ctx.lineTo(x0, this.canvas.height);
      ctx.stroke();
    }

    // Labels de graduation
    ctx.lineWidth = 1;
    ctx.fillStyle = '#000';
    ctx.font = '10px sans-serif';
    ctx.textAlign = 'left';
    ctx.textBaseline = 'top';

    for (let x = Math.ceil(this.xMin); x <= this.xMax; x++) {
      const px = this.xToPx(x);
      ctx.fillText(x.toString(), px + 2, this.yToPx(0) + 4);
    }

    for (let y = Math.ceil(this.yMin); y <= this.yMax; y++) {
      const py = this.yToPx(y);
      if (Math.abs(y) > 1e-6) {
        ctx.fillText(y.toString(), this.xToPx(0) + 4, py + 3);
      }
    }

    // Label axe Y (centré en bas)
    if (this.labelY) {
      const text = this.labelY;
      ctx.font = '12px sans-serif';
      const metrics = ctx.measureText(text);
      const textWidth = metrics.width;
      const textHeight = metrics.actualBoundingBoxAscent + metrics.actualBoundingBoxDescent;
      const padding = 4;

      const x = this.canvas.width / 2;
      const y = this.canvas.height - textHeight - padding - 4;

      // Fond opaque
      ctx.fillStyle = 'rgba(255,255,255,0.8)';
      ctx.fillRect(x - textWidth / 2 - padding, y - padding, textWidth + padding * 2, textHeight + padding * 2);

      // Texte
      ctx.fillStyle = '#000';
      ctx.textAlign = 'center';
      ctx.textBaseline = 'top';
      ctx.fillText(text, x, y);
    }

    // Label axe X (à droite de l'axe horizontal, au-dessus)
    if (this.labelX) {
      const text = this.labelX;
      ctx.font = '12px sans-serif';
      const metrics = ctx.measureText(text);
      const textWidth = metrics.width;
      const textHeight = metrics.actualBoundingBoxAscent + metrics.actualBoundingBoxDescent;
      const padding = 4;

      const x = this.canvas.width - 8;
      const y = this.yToPx(0) - textHeight - padding - 4;

      ctx.fillStyle = 'rgba(255,255,255,0.8)';
      ctx.fillRect(x - textWidth - padding, y - padding, textWidth + padding * 2, textHeight + padding * 2);

      ctx.fillStyle = '#000';
      ctx.textAlign = 'right';
      ctx.textBaseline = 'top';
      ctx.fillText(text, x, y);
    }

    ctx.restore();
  }

  // Affiche une légende des fonctions activées
  drawLegend() {
    const { ctx } = this;
    ctx.save();

    const x0 = 10;
    let y0 = 10;
    const lineHeight = 18;

    const labelsFr = {
      linear: "linéaire",
      sigmoid: "sigmoïde",
      relu: "ReLU",
      tanh: "tangente hyperbolique"
    };

    for (const name in this.functions) {
      const { color, active } = this.functions[name];
      if (!active) continue;

      ctx.fillStyle = color;
      ctx.fillRect(x0, y0, 12, 12);

      ctx.fillStyle = '#000';
      ctx.font = '12px sans-serif';
      ctx.textBaseline = 'top';
      ctx.fillText(labelsFr[name] ?? name, x0 + 18, y0);

      y0 += lineHeight;
    }

    ctx.restore();
  }

  // Affiche le titre dans un rectangle semi-transparent centré
  drawTitle() {
    if (!this.title) return;
    const { ctx, canvas } = this;
    ctx.save();

    const text = this.title;
    ctx.font = '16px sans-serif';

    const metrics = ctx.measureText(text);
    const textWidth = metrics.width;
    const textHeight = metrics.actualBoundingBoxAscent + metrics.actualBoundingBoxDescent;
    const padding = 6;

    const x = canvas.width / 2;
    const y = 6;

    // Fond blanc semi-opaque derrière le titre
    ctx.fillStyle = "rgba(255, 255, 255, 0.8)";
    ctx.fillRect(
      x - textWidth / 2 - padding,
      y - padding,
      textWidth + 2 * padding,
      textHeight + 2 * padding
    );

    // Texte centré
    ctx.fillStyle = "#000";
    ctx.textAlign = "center";
    ctx.textBaseline = "top";
    ctx.fillText(text, x, y);

    ctx.restore();
  }

  // Fonction principale de dessin du graphique
  draw() {
    const { ctx, canvas, w, b } = this;

    // Efface et remplit le fond
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    ctx.fillStyle = "#ffffff";
    ctx.fillRect(0, 0, canvas.width, canvas.height);

    // Dessine les différents éléments
    this.drawGrid();
    this.drawAxes();
    this.drawTitle();
    this.drawLegend();

    // Dessine chaque fonction activée
    for (const name in this.functions) {
      const { color, active, fn } = this.functions[name];
      if (!active) continue;

      ctx.save();
      ctx.strokeStyle = color;
      ctx.lineWidth = 2;
      ctx.beginPath();

      for (let px = 0; px <= canvas.width; px++) {
        const x = this.xMin + (px / canvas.width) * (this.xMax - this.xMin);
        const z = w * x + b;
        const y = fn(z);
        const py = this.yToPx(y);
        if (px === 0) ctx.moveTo(px, py);
        else ctx.lineTo(px, py);
      }

      ctx.stroke();
      ctx.restore();
    }
  }
}
