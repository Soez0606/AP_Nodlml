/**********************************************************
 *  NeuralNetworkEditorView
 *  -------------------------------------------------------
 *  Vue HTML pour l’édition interactive d’un réseau de neurones
 *  (poids et biais) organisée couche par couche, de gauche à droite.
 *
 *  Règles de nommage (alignées sur NeuralNetworkView) :
 *    - Couches : A, B, C…  (titres : "Couche A", "Couche B", …)
 *    - Neurones : A1, A2, …, B1, …
 *    - Entrées  : x1, x2 (ou net.inputNames si fournis)
 *    - Poids    : w(x1→A1)
 *    - Biais    : b(A1)
 *
 *  © 2025 Sébastien Marchand — GPL-3.0-or-later
 **********************************************************/

/**
 * @class NeuralNetworkEditorView
 * @classdesc
 * Vue DOM/HTML d’édition des neurones d’un {@link NeuralNetwork}.
 * Elle ne gère ni l’entraînement ni le rendu canvas : c’est le rôle du contrôleur.
 *
 * @typedef {Object} NeuralNetworkEditorViewOptions
 * @property {HTMLElement|string} [container='#nn-editor'] Élément conteneur ou sélecteur CSS.
 * @property {{net: NeuralNetwork, render?: Function, view?: any}} [controller]
 *          Contrôleur (recommandé) : fournit le réseau et, si possible, la fonction de rendu.
 * @property {NeuralNetwork} [net] Réseau si aucun contrôleur n’est fourni.
 * @property {boolean} [showInputNames=true] Utiliser net.inputNames pour nommer x1,x2…
 */
class NeuralNetworkEditorView {
  /**
   * @constructor
   * @param {NeuralNetworkEditorViewOptions} [options={}] Options.
   * @throws {Error} Si le conteneur ou le réseau ne sont pas disponibles.
   */
  constructor(options = {}) {
    const container = options.container ?? '#nn-editor';
    this.root = (typeof container === 'string')
      ? document.querySelector(container)
      : container;

    this.controller     = options.controller ?? null;
    this.net            = options.net ?? (this.controller?.net ?? null);
    this.renderFn       = this.controller?.render ?? (() => {});
    this.view           = this.controller?.view ?? null;
    this.showInputNames = options.showInputNames ?? true;

    if (!this.root) throw new Error('NeuralNetworkEditorView: conteneur introuvable.');
    if (!this.net)  throw new Error('NeuralNetworkEditorView: aucun réseau fourni (options.net ou options.controller.net).');

    // Fallback si le réseau n’expose pas getNeuronMatrix()
    if (typeof this.net.getNeuronMatrix !== 'function') {
      /**
       * @function NeuralNetwork#getNeuronMatrix
       * @returns {Neuron[][]} Tableau 2D des couches contenant de vrais neurones (hors couche d’entrée).
       */
      this.net.getNeuronMatrix = function () { return this.layers.slice(1); };
    }

    /** @private @type {HTMLElement|null} */
    this._body = null;
    /** @private @type {number|null} */
    this._raf  = null;
  }

  /**
   * Monte la vue dans le DOM et construit l’interface (sans heading global).
   * @returns {void}
   */
  mount() {
    // Corps unique (pas d’entête global)
    this.root.classList.add('nn-editor');
    this.refresh(true);
  }

  /**
   * Reconstruit l’UI à partir de l’état courant du réseau.
   * @param {boolean} [rebuild=false] Si true, recrée le conteneur interne.
   * @returns {void}
   */
  refresh(rebuild = false) {
    if (rebuild) {
      this.root.innerHTML = '';
      this._body = document.createElement('div');
      this._body.className = 'nn-editor-body';
      this.root.appendChild(this._body);
    } else {
      this._body = this.root.querySelector('.nn-editor-body');
      this._body.innerHTML = '';
    }

    const matrix = this.net.getNeuronMatrix(); // [[Neuron,...],[Neuron,...],...]

    // Colonnes : une par couche (gauche → droite)
    for (let L = 0; L < matrix.length; L++) {
      const layer = matrix[L];

      const column = document.createElement('div');
      column.className = 'nn-layer';
      column.innerHTML = `<h3>${this._layerTitle(L)}</h3>`;
      this._body.appendChild(column);

      // Neurones de la couche
      for (let j = 0; j < layer.length; j++) {
        const neuron = layer[j];
        const neuronName = this._neuronTitle(L, j);

        const box = document.createElement('div');
        box.className = 'nn-neuron';

        const head = document.createElement('div');
        head.className = 'nn-row';
        head.innerHTML = `<strong>${neuronName}</strong>`;
        box.appendChild(head);

        // Poids (via getWeights/setWeights)
        const wArray   = neuron.getWeights();
        const inputIdx = this._getInputIndicesFromNeuron(neuron, wArray.length);

        for (let k = 0; k < wArray.length; k++) {
          const row = document.createElement('div');
          row.className = 'nn-row';

          const srcIdx = inputIdx[k];
          const src    = this._inputName(srcIdx);     // x1/x2 ou net.inputNames
          const dst    = neuronName;                  // A1, A2, B1, …

          const label = document.createElement('label');
          label.textContent = this._weightLabel(src, dst);

          const input = document.createElement('input');
          input.type  = 'number';
          input.step  = '0.01';
          input.value = Number(wArray[k]).toFixed(2);
          input.addEventListener('input', (e) => {
            const val = Number(e.target.value);
            if (!Number.isNaN(val)) {
              const cur = neuron.getWeights();
              cur[k]    = val;
              neuron.setWeights(cur);
              this._scheduleRender();
            }
          });

          row.appendChild(label);
          row.appendChild(input);
          box.appendChild(row);
        }

        // Biais
        const rowB = document.createElement('div');
        rowB.className = 'nn-row';
        const labelB = document.createElement('label');
        labelB.textContent = this._biasLabel(neuronName);

        const inputB = document.createElement('input');
        inputB.type  = 'number';
        inputB.step  = '0.01';
        inputB.value = Number(neuron.bias).toFixed(2);
        inputB.addEventListener('input', (e) => {
          const val = Number(e.target.value);
          if (!Number.isNaN(val)) {
            neuron.bias = val;
            this._scheduleRender();
          }
        });

        rowB.appendChild(labelB);
        rowB.appendChild(inputB);
        box.appendChild(rowB);

        column.appendChild(box);
      }
    }
  }

  /**
   * Remplace le réseau édité et reconstruit l’UI.
   * @param {NeuralNetwork} net Nouveau réseau.
   * @returns {void}
   */
  setNetwork(net) {
    if (!net) throw new Error('NeuralNetworkEditorView.setNetwork: réseau invalide.');
    this.net = net;
    if (typeof this.net.getNeuronMatrix !== 'function') {
      this.net.getNeuronMatrix = function () { return this.layers.slice(1); };
    }
    this.refresh(true);
  }

  /**
   * Remplace le contrôleur (et donc potentiellement le réseau et le render).
   * @param {{net: NeuralNetwork, render?: Function, view?: any}} controller
   * @returns {void}
   */
  setController(controller) {
    if (!controller?.net) throw new Error('NeuralNetworkEditorView.setController: controller.net manquant.');
    this.controller = controller;
    this.net        = controller.net;
    this.renderFn   = controller.render ?? (() => {});
    this.view       = controller.view ?? null;
    this.refresh(true);
  }

  /**
   * Nettoie le conteneur et supprime l’UI.
   * @returns {void}
   */
  destroy() {
    this.root.innerHTML = '';
    this._body = null;
  }

  /* ===================== Helpers de nommage (alignés sur NeuralNetworkView) ===================== */

  /**
   * Convertit un index de couche en lettre (0→A, 1→B, …).
   * @private
   * @param {number} L Index de couche (0-based pour la première couche de neurones).
   * @returns {string} Lettre de couche : A, B, C, …
   */
  _letterOf(L) {
    return String.fromCharCode('A'.charCodeAt(0) + L);
  }

  /**
   * Titre de couche, ex. "Couche A".
   * @private
   * @param {number} L Index de couche.
   * @returns {string}
   */
  _layerTitle(L) {
    return `Couche ${this._letterOf(L)}`;
  }

  /**
   * Nom de neurone, ex. "A1", "B2", …
   * @private
   * @param {number} L Index de couche.
   * @param {number} j Index de neurone (0-based).
   * @returns {string}
   */
  _neuronTitle(L, j) {
    return `${this._letterOf(L)}${j + 1}`;
  }

  /**
   * Nom d’entrée, ex. "x1" (ou net.inputNames[idx] si fourni).
   * @private
   * @param {number} idx Index de l’entrée (0-based).
   * @returns {string}
   */
  _inputName(idx) {
    if (this.showInputNames && Array.isArray(this.net.inputNames)) {
      const n = this.net.inputNames[idx];
      if (typeof n === 'string' && n.length) return n;
    }
    return `x${idx + 1}`;
  }

  /**
   * Libellé d’un poids, ex. "w(x1→A1)".
   * @private
   * @param {string} src Nom d’entrée.
   * @param {string} dst Nom de neurone destination.
   * @returns {string}
   */
  _weightLabel(src, dst) {
    return `w(${src}→${dst})`;
  }

  /**
   * Libellé d’un biais, ex. "b(A1)".
   * @private
   * @param {string} dst Nom de neurone.
   * @returns {string}
   */
  _biasLabel(dst) {
    return `b(${dst})`;
  }

  /* ===================================== Autres utilitaires ===================================== */

  /**
   * Lit de manière robuste les indices d’entrée d’un neurone à partir de ses connexions.
   * Repli sur 0..N-1 si aucune structure exploitable.
   * @private
   * @param {Neuron} neuron Neurone cible.
   * @param {number} [fallbackLen=0] Longueur si fallback.
   * @returns {number[]} Indices d’entrée pour chaque poids.
   */
  _getInputIndicesFromNeuron(neuron, fallbackLen = 0) {
    const out   = [];
    const conns = neuron.connections || neuron.incoming || neuron.inputs || [];
    for (let i = 0; i < conns.length; i++) {
      const c   = conns[i] || {};
      const idx = (typeof c.index      === 'number') ? c.index
               : (typeof c.srcIndex    === 'number') ? c.srcIndex
               : (typeof c.src         === 'number') ? c.src
               : (typeof c.from        === 'number') ? c.from
               : (typeof c.inputIndex  === 'number') ? c.inputIndex
               : i;
      out.push(idx);
    }
    if (out.length === 0 && fallbackLen > 0) {
      for (let k = 0; k < fallbackLen; k++) out.push(k);
    }
    return out;
  }

  /**
   * Throttle du redraw : regroupe les mises à jour dans un seul render par frame.
   * @private
   * @returns {void}
   */
  _scheduleRender() {
    if (this._raf) return;
    this._raf = requestAnimationFrame(() => {
      this._raf = null;
      this.renderFn?.();
    });
  }
}