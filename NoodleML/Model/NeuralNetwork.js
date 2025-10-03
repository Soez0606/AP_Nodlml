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
 * @class NeuralNetwork
 * @classdesc Implémentation d’un réseau de neurones multicouche avec rétropropagation.
 */
class NeuralNetwork {
  /**
   * Crée un réseau de neurones à partir des tailles des couches.
   * @param layerSizes - Ex: (2, 4, 1) = 2 entrées, 1 couche cachée de 4, 1 sortie
   */
  constructor(layerSizes, inputNames = null, outputNames = null, learningRate = 0.1, activation = ActivationFunctions.sigmoid) {

    if (!Array.isArray(layerSizes) || layerSizes.length < 2) {
      throw new Error("layerSizes doit être un tableau avec au moins deux éléments.");
    }

    if (layerSizes.some(size => typeof size !== 'number' || size <= 0)) {
      throw new Error("Chaque élément de layerSizes doit être un nombre entier positif.");
    }

    this.activation = activation;
    this.layerSizes = layerSizes;
    this.learningRate = learningRate;
    this.layers = [];

    // Initialiser inputNames à une valeur par défaut si elle est null
    this.inputNames = inputNames || [];
    this.outputNames = outputNames || [];

    for (let l = 1; l < layerSizes.length; l++) {
      const inputSize = layerSizes[l - 1];
      const count = layerSizes[l];
      const layer = [];
      const layerPrefix = String.fromCharCode(64 + l); // A = 65, B = 66, etc.

      for (let i = 0; i < count; i++) {
        const neuron = new Neuron(inputSize, learningRate, activation);
        neuron.name = `${layerPrefix}${i + 1}`; // Exemple : A1, A2, B1...
        layer.push(neuron);
      }

      this.layers.push(layer);
    }
  }

  /**
   * Renvoie la structure d'une couche pour visualisation (poids + biais)
   * @param {number} index - Indice de la couche (0 = première cachée)
   * @returns {{ weights: number[][], biases: number[] }}
   */
  getLayer(index) {
    const layer = this.layers[index];
    return {
      weights: layer.map(n => n.getWeights()),
      biases: layer.map(n => n.getBias())
    };
  }

  /**
   * Calcule la sortie du réseau pour une entrée donnée.
   * @param {number[]} input 
   * @returns {number[]} sortie du réseau
   */
  predict(input) {
    let a = input;
    for (const layer of this.layers) {
      a = layer.map(n => n.predict(a));
    }
    return a;
  }

  /**
   * Fonction d'entraînement global (par échantillons)
   * @param {number[][]} inputs 
   * @param {number[][]} targets 
   * @param {number} epochs 
   * @param {number} lr 
   */
  train(inputs, targets, epochs, lr = 0.1) {
    this.learningRate = lr;
    for (let e = 0; e < epochs; e++) {
      for (let i = 0; i < inputs.length; i++) {
        this.backpropagate(inputs[i], targets[i]);
      }
    }
  }

  backpropagate(input, target) {
    const activations = [input];
    const zs = [];

    // -------- PROPAGATION AVANT --------
    for (const layer of this.layers) {
      const z = [];
      const a = [];

      for (const neuron of layer) {
        const sum = neuron.getWeights().reduce(
          (s, w, i) => s + w * activations.at(-1)[i],
          0
        ) + neuron.getBias();

        z.push(sum);
        a.push(neuron.activate(sum)); // utilisation de la fonction d’activation propre au neurone
      }

      zs.push(z);
      activations.push(a);
    }

    // -------- ERREUR EN SORTIE --------
    const outputLayer = this.layers.at(-1);
    const outputZ = zs.at(-1);

    let delta = activations.at(-1).map((a, i) => {
      const z = outputZ[i];
      return (a - target[i]) * outputLayer[i].activation.df(z);
    });

    // -------- RÉTROPROPAGATION --------
    for (let l = this.layers.length - 1; l >= 0; l--) {
      const layer = this.layers[l];
      const prevA = activations[l];
      const z = zs[l];

      // Mise à jour des poids/biais
      for (let j = 0; j < layer.length; j++) {
        const neuron = layer[j];
        const d = delta[j];

        const newWeights = neuron.getWeights().map(
          (w, k) => w - this.learningRate * d * prevA[k]
        );
        const newBias = neuron.getBias() - this.learningRate * d;

        neuron.setWeights(newWeights);
        neuron.setBias(newBias);
      }

      // Préparation du delta précédent
      if (l > 0) {
        const prevLayer = this.layers[l - 1];
        const nextDelta = [];

        for (let i = 0; i < prevLayer.length; i++) {
          let sum = 0;

          for (let j = 0; j < layer.length; j++) {
            sum += layer[j].getWeights()[i] * delta[j];
          }

          const prevZ = zs[l - 1][i]; // zᵢ de la couche précédente
          nextDelta[i] = sum * prevLayer[i].activation.df(prevZ);
        }

        delta = nextDelta;
      }
    }
  }



  /**
   * Sauvegarde du modèle au format JSON
   * @param {string} filename 
   */
  saveToFile(filename) {
    const data = {
      epoch: this.epoch,
      layerSizes: this.layerSizes,
      weights: this.layers.map(layer => layer.map(neuron => neuron.getWeights())),
      biases: this.layers.map(layer => layer.map(neuron => neuron.getBias())),
      inputNames: this.inputNames,  // Sauvegarde des noms des entrées
      outputNames: this.outputNames, // Sauvegarde des noms des sorties
      activations: this.layers.map(layer => layer.map(neuron => neuron.activation.name)),
    };

    // Créer un Blob avec les données du modèle pour le téléchargement
    const blob = new Blob([JSON.stringify(data)], { type: 'application/json' });

    // Créer un lien pour télécharger le modèle
    const a = document.createElement('a');
    a.href = URL.createObjectURL(blob);
    a.download = filename;
    a.click();
  }


  /**
   * Chargement d'un modèle sauvegardé
   * @param {string} url - L'URL ou le chemin du fichier JSON contenant le modèle
   * @returns {Promise<NeuralNetwork>} - Une promesse renvoyant l'instance du réseau chargé
   */
  async loadFromFile(url) {
    // Récupérer le fichier JSON
    const res = await fetch(url);
    const json = await res.json();

    // Vérification de la validité du fichier
    if (!json.layerSizes || !json.weights || !json.biases || !json.epoch) {
      throw new Error("Modèle invalide ou corrompu");
    }

    // Créer une nouvelle instance du réseau de neurones
    const network = new NeuralNetwork(json.layerSizes, json.inputNames, json.outputNames);

    network.epoch = json.epoch; // Récupérer l'epoch

    // Vider les couches existantes
    network.layers = [];

    // Remplir les couches avec les poids, biais et fonctions d'activation
    const activationsData = json.activations || [];
    for (let l = 1; l < json.layerSizes.length; l++) {
      const layer = [];
      const inSize = json.layerSizes[l - 1];
      const outSize = json.layerSizes[l];
      const layerActivations = activationsData[l - 1] || [];
      for (let i = 0; i < outSize; i++) {
        const actName = layerActivations[i];
        let activation = ActivationFunctions.sigmoid;
        if (actName) {
          const found = Object.values(ActivationFunctions).find(a => a.name === actName);
          if (found) activation = found;
        }
        const neuron = new Neuron(inSize, network.learningRate, activation);
        neuron.setWeights(json.weights[l - 1][i]); // Set weights for the neuron
        neuron.setBias(json.biases[l - 1][i]);     // Set bias for the neuron
        layer.push(neuron);
      }
      network.layers.push(layer);  // Add the layer to the network
    }

    // Retourner l'instance du modèle chargé
    return network;
  }

  /**
   * Retourne le nombre total de neurones dans le réseau
   */
  countNeurons() {
    let count = 0;
    this.layers.forEach(layer => {
      count += layer.length;
    });
    return count;
  }

  /**
   * Affiche les poids et biais dans la console (debug)
   */
  debugPrintWeightsAndBiases() {
    console.log("=== Réseau de Neurones ===");
    for (let l = 0; l < this.layers.length; l++) {
      console.log(`Couche ${l + 1}:`);
      this.layers[l].forEach((n, i) => {
        console.log(`  Neurone ${i}:`);
        console.log("    Poids :", n.getWeights().map(w => w.toFixed(3)));
        console.log("    Biais :", n.getBias().toFixed(3));
      });
    }
  }

  /**
   * Affiche l'équation de sortie d'un neurone donné.
   * Cette méthode génère une représentation mathématique de l'équation en utilisant les poids et les biais.
   * @param {number} outputNeuronIndex - L'indice du neurone de la couche de sortie.
   * @returns {string} - L'équation de sortie du neurone sous forme de chaîne.
   */
  getOutputEquation(outputNeuronIndex) {
    if (this.layers.length < 2) {
      throw new Error("Le réseau doit avoir au moins une couche cachée pour générer une équation de sortie.");
    }

    const outputLayer = this.layers[this.layers.length - 1];  // Récupère la couche de sortie
    if (outputNeuronIndex < 0 || outputNeuronIndex >= outputLayer.length) {
      throw new Error("L'indice du neurone de sortie est invalide.");
    }

    const outputNeuron = outputLayer[outputNeuronIndex];
    const weights = outputNeuron.getWeights();
    const bias = outputNeuron.getBias();

    // Génère l'équation de sortie
    let equation = `y = ${bias.toFixed(3)}`; // Commence par le biais

    // Ajoute les poids et les entrées correspondantes
    for (let i = 0; i < weights.length; i++) {
      equation += ` + (${weights[i].toFixed(3)}) * x${i + 1}`;  // x(i+1) pour les noms des entrées
    }

    return equation;
  }

  /**
   * Affiche l'équation complète du réseau de neurones, du neurone de sortie aux neurones d'entrée.
   * Cette méthode génère l'expression mathématique complète de la sortie en fonction des entrées.
   * @param {number} outputNeuronIndex - L'indice du neurone de la couche de sortie.
   * @returns {string} - L'équation complète du réseau, en partant des entrées jusqu'à la sortie.
   */
  getFullEquation(outputNeuronIndex) {
    if (this.layers.length < 2) {
      throw new Error("Le réseau doit avoir au moins une couche cachée pour générer une équation complète.");
    }

    const outputLayer = this.layers[this.layers.length - 1];  // Récupère la couche de sortie
    if (outputNeuronIndex < 0 || outputNeuronIndex >= outputLayer.length) {
      throw new Error("L'indice du neurone de sortie est invalide.");
    }

    // Fonction pour représenter une équation à partir des poids, biais et entrées
    const getLayerEquation = (layer, prevActivations) => {
      return layer.map(neuron => {
        const weights = neuron.getWeights();
        const bias = neuron.getBias();
        let equation = `${bias.toFixed(3)}`;

        // Ajout des termes pondérés par les entrées
        for (let i = 0; i < weights.length; i++) {
          equation += ` + (${weights[i].toFixed(3)}) * ${prevActivations[i]}`;
        }
        //return `fact(${equation})`; // Fonction d'activation
        return `1 / (1 + exp(-(${equation})))`; // Sigmoïde de la somme pondérée
      });
    };

    // On commence par les entrées
    let prevActivations = this.inputNames.map((_, index) => `x${index + 1}`);

    // Traverse du réseau couche par couche pour construire l'équation
    let equations = [];
    for (let l = 0; l < this.layers.length; l++) {
      equations = getLayerEquation(this.layers[l], prevActivations);
      prevActivations = equations;  // De la couche actuelle à la suivante, les activations sont les équations
    }

    // L'équation finale de la sortie
    const outputNeuron = outputLayer[outputNeuronIndex];
    const outputEquation = equations[outputNeuronIndex];

    // On retourne l'équation complète, du début à la fin
    return `y = ${outputEquation}`;
  }

  // NeuralNetwork.js
  setActivationFunctionForAll(activation) {
    for (const layer of this.layers) {
      for (const neuron of layer) {
        if (typeof neuron.setActivationFunction === 'function') {
          neuron.setActivationFunction(activation);
        }
      }
    }
  }

  /**
   * Ajoute une couche au réseau de neurones.
   * @param {*} nbNeurons - Le nombre de neurones dans la couche.
   * @param {*} activation - La fonction d'activation à utiliser pour les neurones de cette couche.
   */
  addLayer(nbNeurons, activation = "linear") {
    const previousLayer = this.layers.length > 0 ? this.layers[this.layers.length - 1] : null;
    const inputSize = previousLayer ? previousLayer.length : this.inputSize;

    const newLayer = [];

    for (let i = 0; i < nbNeurons; i++) {
      const neuron = new Neuron(inputSize);
      neuron.setActivation(activation);
      newLayer.push(neuron);
    }

    this.layers.push(newLayer);
  }

  /**
   * Retourne une représentation structurée des couches de neurones du réseau.
   *
   * @returns {Neuron[][]} Un tableau 2D contenant directement les objets `Neuron`.
   * 
   * - La couche d’entrée (layer[0]) est ignorée car elle ne contient pas de neurones
   *   dotés de poids ou de biais.
   * - Chaque élément du tableau retourné correspond donc à une couche "active"
   *   (cachée ou de sortie).
   * - Exemple pour un MLP [2, 2, 1] :
   *   - res[0] = tableau des 2 neurones de la première couche cachée
   *   - res[1] = tableau du neurone de sortie
   *
   * Cette méthode est utile pour :
   * - Construire un éditeur de réseau (accéder aux poids et biais de chaque neurone)
   * - Visualiser ou analyser directement la topologie du réseau
   * - Manipuler les couches sans passer par la logique d’entraînement
   */
  getNeuronMatrix() {
    const out = [];
    for (let L = 0; L < this.layers.length; L++) {
      const layer = this.layers[L];
      // On ne conserve que les couches qui contiennent de vrais objets Neuron
      if (Array.isArray(layer) && layer.length && typeof layer[0]?.getWeights === 'function') {
        out.push(layer);
      }
    }
    return out;
  }

}
