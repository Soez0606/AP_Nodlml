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
 * @class Perceptron
 * @classdesc Implémentation d’un perceptron simple à n entrées avec apprentissage supervisé.
 *
 * Ce perceptron utilise une fonction d’activation à seuil (step function).
 * Il peut être entraîné à l’aide de la règle de correction basée sur l’erreur.
 *
 * Exemple typique d’utilisation :
 * - Classification linéaire binaire
 * - Apprentissage de fonctions logiques (AND, OR, etc.)
 * - Visualisation pédagogique des réseaux de neurones
 */
class Perceptron {
  /**
   * Crée une instance du perceptron.
   *
   * Initialise le perceptron avec un nombre d'entrées donné et attribue des poids
   * aléatoires à chacune de ces entrées, ainsi qu'un biais aléatoire. Un seuil d'activation
   * est fixé à 0 par défaut. Le paramètre disableThreshold est prévu pour indiquer
   * si l'activation par seuil doit être désactivée, bien que dans l'initialisation,
   * la valeur soit forcée à false.
   *
   * @param {number} inputSize - Nombre d'entrées du perceptron.
   * @param {number} [learningRate=0.1] - Taux d'apprentissage utilisé pour la mise à jour des poids.
   * @param {boolean} [disableThreshold=false] - Indique si le seuil d'activation doit être désactivé.
   */
  constructor(inputSize, learningRate = 0.1, disableThreshold = false) {
    this.learningRate = learningRate;
    this.weights = Array.from({ length: inputSize }, () => Math.random() * 2 - 1); // [-1, 1]
    this.bias = Math.random() * 2 - 1;
    this.activationThreshold = 0;
    this.disableThreshold = disableThreshold;
    this.name = "Perceptron";
  }

  /**
    * Fonction d’activation de type seuil.
    *
    * @param {number} x - Entrée brute (somme pondérée + biais).
    * @returns {number} 1 si x > 0, sinon 0.
    */
  activate(x) {

    if (this.disableThreshold) {
      // Fonction d'activation linéaire bornée
      return ActivationFunctions.linearBounded.f(x);
    }
    else {
      // Fonction d'activation de type seuillage binaire standard 
      // d'un perceptron simple
      return ActivationFunctions.binary.f(x);
    }
  }

  /**
    * Prédit la sortie binaire du perceptron à partir d’un vecteur d’entrée.
    *
    * @param {number[]} input - Tableau d’entrées numériques.
    * @returns {number} 0 ou 1 selon le résultat de la fonction d’activation.
    */
  predict(input) {

    let sum = this.bias;
    for (let i = 0; i < input.length; i++) {
      sum += this.weights[i] * input[i];
    }
    
    return this.activate(sum);
  }

  /**
    * Entraîne le perceptron à l’aide d’un exemple d’entrée et d’une sortie attendue.
    * Met à jour les poids et le biais en fonction de l’erreur observée.
    *
    * @param {number[]} input - Tableau d’entrées numériques.
    * @param {number} target - Valeur cible (0 ou 1).
    */
  train(input, target) {
    const prediction = this.predict(input);
    const error = target - prediction;

    for (let i = 0; i < input.length; i++) {
      this.weights[i] += this.learningRate * error * input[i];
    }

    this.bias += this.learningRate * error;
  }

  /**
   * Réinitialise les poids et le biais avec des valeurs aléatoires
   */
  reset() {
    this.weights = this.weights.map(() => Math.random() * 2 - 1);
    this.bias = Math.random() * 2 - 1;
  }

  /**
  * Retourne une copie des poids actuels du perceptron.
  *
  * @returns {number[]} Un tableau contenant les poids.
  */
  getWeights() {
    return [...this.weights];
  }

  /**
   * Retourne la valeur actuelle du biais.
   *
   * @returns {number} Le biais.
   */
  getBias() {
    return this.bias;
  }

  /**
   * Définit les poids du perceptron.
   * Les poids existants sont remplacés par une copie du tableau fourni.
   *
   * @param {number[]} weights - Un tableau contenant les nouveaux poids.
   */
  setWeights(weights) {
    this.weights = [...weights];
  }

  /**
   * Définit la valeur du biais du perceptron.
   *
   * @param {number} bias - La nouvelle valeur du biais.
   */
  setBias(bias) {
    this.bias = bias;
  }

  /**
   * Définit le seuil d'activation du perceptron.
   *
   * @param {number} treshold - La nouvelle valeur du seuil d'activation.
   */
  setActivationThreshold(treshold) {
    this.activationThreshold = treshold;
  }

  /**
   * Désactive ou active le seuil d'activation. Cette possibilité est intéressante 
   * pour visualiser le comportement du perceptron sans l'influence du seuil 
   * (visualisation 3D).
   * 
   * @param {boolean} disable - true pour désactiver le seuil, false pour l'activer.
   * 
   * @returns {void}
   */
  setDisableThreshold(disable) {
    this.disableThreshold = disable;
  }

  /**
   * @brief Entraîne le perceptron sur un ensemble d'exemples étiquetés.
   *
   * Cette méthode applique la règle d'apprentissage du perceptron sur
   * plusieurs itérations (epochs) pour ajuster les poids et le biais.
   * À chaque epoch, l'ensemble des exemples est traité séquentiellement
   * (avec possibilité de les mélanger avant chaque passage).
   *
   * @param {Array<Object>} data 
   *        Tableau d'objets de la forme :
   *        [
   *          { x: [Number, Number, ...], y: Number },
   *          ...
   *        ]
   *        où `x` est le vecteur d'entrée et `y` la sortie attendue (0 ou 1).
   *
   * @param {Number} [epochs=20]
   *        Nombre de passages complets sur l'ensemble de données.
   *
   * @param {Boolean} [shuffle=true]
   *        Si vrai, mélange l'ordre des exemples à chaque epoch
   *        afin d'éviter un biais lié à l'ordre des données.
   *
   * @return {void}
   *
   * @note L'algorithme converge uniquement si les données
   *       sont linéairement séparables.
   * @note La méthode `train()` est appelée en interne pour chaque exemple.
   * @warning Pour un bon apprentissage, normalisez les données d'entrée.
   */
  fit(data, epochs = 20, shuffle = true) {
    for (let e = 0; e < epochs; e++) {
      if (shuffle) {
        data = data.sort(() => Math.random() - 0.5);
      }
      for (const { x, y } of data) {
        this.train(x, y);
      }
    }
  }
}