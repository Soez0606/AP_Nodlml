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
 * @class NeuralConnection
 * @classdesc Représente une connexion pondérée entre deux neurones dans un réseau de neurones.
 * Contient un lien explicite vers le neurone source et un poids synaptique.
 */
class NeuralConnection {
    /**
     * Crée une nouvelle connexion synaptique.
     *
     * @param {Neuron} from - Neurone source (pré-synaptique).
     * @param {number} [weight] - Poids initial (sinon valeur aléatoire entre -1 et 1).
     */
    constructor(from, weight = null) {
        /** @type {Neuron} Neurone source */
        this.from = from;

        /** @type {number} Poids de la connexion */
        this.weight = weight !== null ? weight : (Math.random() * 2 - 1);
    }

    /**
     * Calcule la contribution de cette connexion à la somme d’entrée d’un neurone cible.
     * @returns {number} Produit de l’activation du neurone source et du poids.
     */
    compute() {
        return this.from.output * this.weight;
    }

    /**
     * Met à jour le poids de la connexion en ajoutant un delta.
     * @param {number} delta - Valeur de mise à jour du poids (e.g., gradient * learning rate).
     */
    update(delta) {
        this.weight += delta;
    }

    /**
     * Remet le poids à une nouvelle valeur (sans accumulation).
     * @param {number} newWeight - Nouveau poids à appliquer.
     */
    setWeight(newWeight) {
        this.weight = newWeight;
    }

    /**
     * Renvoie le neurone source.
     * @returns {Neuron} Neurone précédent.
     */
    getSource() {
        return this.from;
    }

    /**
     * Renvoie le poids actuel.
     * @returns {number}
     */
    getWeight() {
        return this.weight;
    }
}