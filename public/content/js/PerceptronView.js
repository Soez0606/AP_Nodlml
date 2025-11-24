/**********************************************************
 *      _   _                 _ _     ___  ___ _
 *     | \ | |               | | |    |  \/  || |
 *     |  \| | ___   ___   __| | | ___| .  . || |
 *     | . ` |/ _ \ / _ \ / _` | |/ _ \ |\/| || |
 *     | |\  | (_) | (_) | (_| | |  __/ |  | || |____
 *     \_| \_/\___/ \___/ \__,_|_|\___\_|  |_/\_____/
 *
 *      NoodleML — PerceptronView (compat NeuralNetworkView)
 **********************************************************/

/**
 * @class PerceptronView
 * @classdesc Vue Canvas d’un perceptron (N→1) qui reprend l’interface de NeuralNetworkView :
 * - Entrées/sorties virtuelles
 * - Un neurone (Perceptron) rendu via NeuronView
 * - Connexions rendues via NeuralConnectionView
 * - Modes d’affichage : "value" (poids numériques) / "text" (étiquettes symboliques)
 */
class PerceptronView {
    /**
     * @brief Constructeur (mêmes paramètres que NeuralNetworkView).
     * @param {CanvasRenderingContext2D} ctx Contexte 2D du canvas.
     * @param {{x:number,y:number,width:number,height:number}} canvasRect Rectangle du sous-canevas à dessiner.
     * @param {Object} perceptron Instance du Perceptron (expose au minimum `weights:Array<number>`, `bias:number`, `name?:string`).
     * @param {number} [neuronSize=80] Taille du neurone (diamètre en px).
     * @param {boolean} [displayResultHeatmapIfPossible=false] Heatmap si le neurone accepte jusqu’à 2 entrées.
     * @param {"light"|"dark"} [colorMode="dark"] Mode de couleur (cohérent avec NeuralNetworkView).
     *
     * @details
     * Aligne sa structure interne sur NeuralNetworkView :
     * - this.neuronViews: Array<Array<NeuronView>> (ici un seul "layer" contenant 1 NeuronView)
     * - this.connections: Array<NeuralConnectionView>
     * - this.inputPositions / this.outputPositions : positions des ports virtuels
     */
    constructor(ctx, canvasRect, perceptron, inputNames = null, outputNames = null, neuronSize = 80, displayResultHeatmapIfPossible = false, colorMode = "dark") {

        if (perceptron instanceof Perceptron && perceptron.constructor === Perceptron) {
            // Assure que le perceptron utilise une activation binaire
            // pour la compatibilité avec NeuralNetworkView
            perceptron.activation = ActivationFunctions.binary;
        } else if (!perceptron || !(perceptron instanceof Perceptron)) {
            // Si ce n'est pas un Perceptron ou un héritier valide, on lève une erreur
            throw new Error("[PerceptronView] Instance de perceptron invalide.");
        }

        this.ctx = ctx;
        this.perceptron = perceptron;

        this.neuronSize = neuronSize;
        this.layerSpacing = 150;       // conservé pour cohérence
        this.neuronSpacing = 35;       // idem
        this.availableHeight = canvasRect.height;

        this.neuronViews = [];
        this.connections = [];

        // Initialiser inputNames à une valeur par défaut si elle est null
        // Noms d’entrées/sorties (compat : NeuralNetworkView lit network.inputNames/outputNames)
        this.inputNames = inputNames || [];
        this.outputNames = outputNames || [];

        this.canvasRect = canvasRect;
        this.x = canvasRect.x;
        this.y = canvasRect.y;
        this.width = canvasRect.width;
        this.height = canvasRect.height;

        this.displayResultHeatmapIfPossible = displayResultHeatmapIfPossible;
        this.labelDisplayMode = "text";

        const allowedModes = ["light", "dark"];
        if (!allowedModes.includes(colorMode)) {
            console.warn(`[PerceptronView] Mode de couleur "${colorMode}" non valide. Utilisation de "dark".`);
            colorMode = "dark";
        }
        this.colorMode = colorMode;

        this.buildView();
    }

    /**
     * @brief Construit la vue : positions, NeuronView, connexions et ports virtuels.
     * @return {void}
     */
    buildView() {
        this.neuronViews = [];
        this.connections = [];
        this.inputPositions = [];
        this.outputPositions = [];

        // Mise à l’échelle éventuelle de la taille du neurone
        const maxLayerSize = 1; // un seul neurone (sortie)
        let step = this.availableHeight / maxLayerSize;
        this.neuronSize = Math.min(this.neuronSize, step - this.neuronSpacing);

        // Emplacement horizontal des colonnes (entrées | neurone | sorties)
        const xInput = this.x;
        const xOutput = this.x + this.width;

        // NeuralNetworkView utilise totalLayers = network.layerSizes.length ; ici [N,1] → 2
        const totalLayers = 2; // 1 couche d'entrées + 1 couche de sortie (neurone)
        const xStep = (xOutput - xInput) / totalLayers;

        // 1) Placement du neurone de sortie via NeuronView
        const views = [];
        // Décalage x de la "couche" unique (celle du neurone)
        let xLayerOffset = xInput + xStep;          // colonne centrale
        xLayerOffset = xLayerOffset - this.neuronSize / 4; // même petit décalage que dans NeuralNetworkView(l==0)

        // Centrage vertical du neurone
        const nx = xLayerOffset;
        const ny = this.y + (this.availableHeight - this.neuronSize) / 2;

        // Important : on passe l’instance du perceptron comme "neuron" au NeuronView
        const neuronView = new NeuronView(this.ctx, nx, ny, this.neuronSize, 0, this.perceptron, this.displayResultHeatmapIfPossible);
        views.push(neuronView);
        this.neuronViews.push(views);

        // 2) Ports d’entrée virtuels (mêmes règles de positionnement)
        const inputCount = this.perceptron.weights.length;
        const centerY = ny + this.neuronSize / 2;
        const inputSpacing = this.availableHeight / (inputCount + 1);

        for (let i = 0; i < inputCount; i++) {
            const px = xInput;
            const py = centerY - (inputSpacing * (inputCount - 1) / 2) + i * inputSpacing;
            const label = this.inputNames[i] || `x${i}`;
            this.inputPositions.push({ x: px, y: py, label });
        }

        // 3) Connexions des entrées vers le neurone (poids du perceptron)
        // Étalement vertical des attaches sur le bord gauche du neurone
        const stepAttach = neuronView.size / (this.inputPositions.length + 1);
        for (let i = 0; i < this.inputPositions.length; i++) {
            const input = this.inputPositions[i];

            const toPos = { x: neuronView.x, y: neuronView.y + stepAttach * (i + 1) };

            // Getter live sur le poids (pour voir les updates sans reconstruire la vue)
            const weightFn = () => this.perceptron.weights[i];

            // Libellé identique à NeuralNetworkView: "xᵢ,NomDuNeurone"
            let label = "";
            if (this.perceptron.name !== "") {
                label = `${input.label},${this.perceptron.name}`;
            }
            else {
                label = input.label; // juste l'étiquette d'entrée
            }

            // Création de la connexion avec le poids dynamique
            const conn = new NeuralConnectionView(
                this.ctx,
                { x: input.x, y: input.y },
                toPos,
                weightFn,
                input.label,
                label,
                "left"
            );

            conn.setDisplayMode("arrow_with_circle_at_left_and_label");
            conn.label = label;
            this.connections.push(conn);
        }

        // 4) Port(s) de sortie virtuel(s) et connexion depuis le neurone
        const outputCount = 1;
        const outputSpacing = this.availableHeight / (outputCount + 1);
        for (let i = 0; i < outputCount; i++) {
            const ox = xOutput;
            const oy = centerY - (outputSpacing * (outputCount - 1) / 2) + i * outputSpacing;
            const label = this.outputNames[i] || `ŷ${i}`;
            this.outputPositions.push({ x: ox, y: oy, label });
        }

        {
            const output = this.outputPositions[0];
            // Titre mathématique identique à NNView: "ŷ = f(x0,x1,...)"
            let title = `${output.label} = f(`;
            title += this.inputPositions.map(inp => inp.label).join(", ") + ")";

            const conn = new NeuralConnectionView(
                this.ctx,
                { x: neuronView.x + neuronView.size, y: neuronView.y + neuronView.size / 2 },
                { x: output.x, y: output.y },
                undefined,           // pas de poids sur ce segment "neurone -> port de sortie"
                title,               // title
                "",                  // optional label
                "right"              // direction (pointe vers la droite)
            );
            conn.setDisplayMode("arrow_with_circle_at_right_and_label");
            this.connections.push(conn);
        }

        this.setColorMode(this.colorMode);
    }

    /**
     * @brief Applique le mode couleur (cascade sur connexions et neurone).
     * @param {"light"|"dark"} colorMode
     * @return {void}
     */
    setColorMode(colorMode) {
        const allowedModes = ["light", "dark"];
        if (!allowedModes.includes(colorMode)) {
            console.warn(`[PerceptronView] Mode de couleur "${colorMode}" non valide. Utilisation de "dark".`);
            colorMode = "dark";
        }
        this.colorMode = colorMode;

        for (const conn of this.connections) conn.setColorMode(colorMode);
        for (const layer of this.neuronViews)
            for (const neuronView of layer) neuronView.setColorMode(colorMode);

        this.draw();
    }

    /**
     * @brief Dessine la vue (connexions + neurone + poids/labels).
     * @return {void}
     *
     * @details
     * Efface la zone, puis :
     * 1) dessine les connexions (avec/without couleur selon `labelDisplayMode`),
     * 2) dessine le neurone (via NeuronView),
     * 3) dessine soit les **poids** (mode `"value"`) soit les **étiquettes** (mode `"text"`).
     */
    draw() {
        
        // Efface la zone d’affichage
        // (Même logique que NNView : on s’appuie sur canvasRect, sans remplir le fond)
        this.ctx.clearRect(this.x, this.y, this.width, this.height);
        

        this.ctx.font = "14px sans-serif";

        // Connexions
        for (const conn of this.connections) {
            if (this.labelDisplayMode === "value") {
                conn.drawConnection(true);
            } else {
                conn.drawConnection(false);
            }
        }

        // Neurone
        for (const layer of this.neuronViews) {
            for (const neuron of layer) {
                neuron.draw(this.labelDisplayMode);
            }
        }

        // Poids/Labels
        for (const conn of this.connections) {
            if (this.labelDisplayMode === "value") {
                conn.drawWeight();
            } else {
                conn.drawLabel();
            }
        }
    }

    /**
     * @brief Change le mode d’affichage des étiquettes/poids et invalide les caches de heatmap.
     * @param {"value"|"text"} labelDisplayMode
     * @return {void}
     */
    setLabelDisplayMode(labelDisplayMode) {
        this.labelDisplayMode = labelDisplayMode;

        // Force le recalcul des heatmaps (même stratégie que NeuralNetworkView)
        for (const layer of this.neuronViews) {
            for (const neuronView of layer) {
                if (neuronView.heatmap) neuronView.heatmap.needsDisplay = true;
            }
        }
    }
}