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
 * @class Neuron
 * @extends Perceptron
 * @classdesc Représente un neurone standard avec connexions explicites
 * et fonction d'activation continue (sigmoïde, tanh, relu, etc.).
 */
class Neuron extends Perceptron {

    /**
     * Initialise un neurone avec des connexions spécifiques.
     * Si aucune connexion n’est précisée, on suppose une connexion complète via inputSize.
     *
     * @param {number} inputSize - Nombre total d'entrées possibles (uniquement utilisé si `connections` est vide).
     * @param {number} [learningRate=0.1] - Taux d'apprentissage.
     * @param {object} [activation=ActivationFunctions.sigmoid] - Fonction d'activation.
     * @param {Array<{index: number, weight: number}>} [connections=[]] - Connexions explicites.
     */
    constructor(inputSize, learningRate = 0.1, activation = ActivationFunctions.sigmoid, connections = []) {

        const weights = connections.length > 0
            ? connections.map(conn => conn.weight)
            : Array.from({ length: inputSize }, () => Math.random() * 2 - 1);

        super(weights.length, learningRate);

        /**
         * @type {Array<{index: number, weight: number}>}
         * Connexions actives de ce neurone vers les entrées.
         */
        this.connections = connections.length > 0
            ? connections
            : weights.map((w, i) => ({ index: i, weight: w }));

        this.activation = activation;
    }

    /**
     * Calcule la sortie du neurone à partir des entrées spécifiées.
     *
     * @param {number[]} inputs - Entrées externes au neurone.
     * @returns {number} Sortie activée.
     */
    predict(inputs) {
        let sum = this.bias;
        for (const { index, weight } of this.connections) {
            if (index >= inputs.length) {
                throw new Error(`Index ${index} hors des bornes d'entrée.`);
            }
            sum += inputs[index] * weight;
        }
        return this.activate(sum);
    }

    /**
     * Applique la fonction d’activation au résultat.
     * @param {number} sum - Somme pondérée des entrées + biais.
     * @returns {number} Sortie activée.
     */
    activate(sum) {
        return this.activation.f(sum);
    }

    /**
     * Calcule la dérivée de la fonction d’activation.
     * @param {number} sum - Somme pondérée des entrées + biais.
     * @returns {number} Dérivée de l'activation.
     */
    activationDerivative(sum) {
        return this.activation.df(sum);
    }

    /**
     * Change dynamiquement la fonction d’activation.
     * @param {object} activation - Objet fonctionnel {f, df}.
     */
    setActivationFunction(activation) {
        this.activation = activation;
    }

    /**
     * Ajoute dynamiquement une connexion à ce neurone.
     * @param {number} index - Indice de l’entrée source.
     * @param {number|null} [weight=null] - Poids de la connexion (aléatoire si null).
     */
    addConnection(index, weight = null) {
        this.connections.push({
            index,
            weight: weight !== null ? weight : Math.random() * 2 - 1
        });
    }

    /**
     * Met à jour tous les poids des connexions existantes.
     * @param {number[]} weights - Nouveaux poids.
     */
    setWeights(weights) {
        if (weights.length !== this.connections.length) {
            throw new Error("Le nombre de poids ne correspond pas au nombre de connexions.");
        }
        for (let i = 0; i < weights.length; i++) {
            this.connections[i].weight = weights[i];
        }
    }

    /**
     * Retourne tous les poids actuels.
     * @returns {number[]} Tableau des poids.
     */
    getWeights() {
        return this.connections.map(conn => conn.weight);
    }
}