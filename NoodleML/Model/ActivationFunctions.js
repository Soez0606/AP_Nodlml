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

const ActivationFunctions = {
    binary: {
        name: "binary [0,1]", // Fonction d'activation binaire pour compatibilité Perceptron
        f: x => (x >= 0.5 ? 1 : 0),
        df: x => (0) // Dérivée nulle car c'est une fonction de seuil - incompatible avec la backpropagation dans un réseau de neurones
    },
    linearBounded: {
        name: `linear[-1,1]`,
            f: x => Math.max(-1, Math.min(1, x)),
            df: x => (x < -1 || x > 1) ? 0 : 1
    },
    sigmoid: {
        name: "sigmoid",
        f: x => 1 / (1 + Math.exp(-x)),
        df: x => {
            const s = 1 / (1 + Math.exp(-x));
            return s * (1 - s);
        }
    },
    relu: {
        name: "relu",
        f: x => Math.max(0, x),
        df: x => x > 0 ? 1 : 0
    },
    tanh: {
        name: "tanh",
        f: x => Math.tanh(x),
        df: x => 1 - Math.pow(Math.tanh(x), 2)
    },
    leakyRelu: {
        name: "leakyReLU",
        f: x => x > 0 ? x : 0.01 * x,
        df: x => x > 0 ? 1 : 0.01
    },
    elu: {
        name: "ELU",
        f: x => x >= 0 ? x : Math.expm1(x),
        df: x => x >= 0 ? 1 : Math.exp(x)
    },
    softplus: {
        name: "Softplus",
        f: x => Math.log(1 + Math.exp(x)),
        df: x => 1 / (1 + Math.exp(-x)) // sigmoid
    }
};
