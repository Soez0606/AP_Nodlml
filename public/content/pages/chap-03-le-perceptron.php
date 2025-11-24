<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <title>NoodleML - Chapitre 3 : Le perceptron ou la naissance d’un neurone numérique</title>

  <!-- Balises META pour SEO et réseaux sociaux -->

  <!-- SEO classique -->
  <title>NoodleML - Chapitre 3 : Le perceptron ou la naissance d’un neurone numérique</title>
  <meta name="description"
    content="Voyagez à travers l'histoire de l'intelligence artificielle avec Udon, votre guide numérique. Illustrations, anecdotes et pédagogie au rendez-vous.">
  <meta name="keywords"
    content="NoodleML, IA, intelligence artificielle, histoire IA, Udon, apprentissage, pédagogie, réseau de neurones, BTS SIO, C++, JavaScript">
  <meta name="author" content="Sébastien Marchand">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- Open Graph pour Facebook et autres -->
  <meta property="og:title" content="NoodleML - Chapitre 3 : Le perceptron ou la naissance d’un neurone numérique">
  <meta property="og:description"
    content="Voyagez à travers l'histoire de l'intelligence artificielle avec Udon, votre guide numérique. Illustrations, anecdotes et pédagogie au rendez-vous.">
  <meta property="og:image" content="https://noodleml.com/resources/chap-03/perceptron-super-saiyan.jpg">
  <meta property="og:url" content="https://noodleml.com/chap-03-le-perceptron.html">
  <meta property="og:type" content="article">
  <meta property="og:locale" content="fr_FR">
  <meta property="og:site_name" content="NoodleML">

  <!-- Twitter Card -->
  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:title" content="NoodleML - Chapitre 3 : Le perceptron ou la naissance d’un neurone numérique">
  <meta name="twitter:description"
    content="Découvrez les origines et l’évolution de l’IA avec une approche pédagogique originale signée Udon.">
  <meta name="twitter:image" content="https://noodleml.com/resources/chap-03/perceptron-super-saiyan.jpg">

  <link rel="icon" href="../resources/NoodleML.png" type="image/png">

  <!-- Feuille de style -->
  <link rel="stylesheet" href="../css/style-noodleml.css">
  <link rel="stylesheet" href="../css/audio-player.css">
  <link rel="stylesheet" href="../css/network-viewer.css">

  <script id="MathJax-script" async src="https://cdn.jsdelivr.net/npm/mathjax@3/es5/tex-mml-chtml.js"> </script>


  <script>
    function formatTime(t) {
      const min = Math.floor(t / 60);
      const sec = Math.floor(t % 60).toString().padStart(2, '0');
      return `${min}:${sec}`;
    }

    window.addEventListener('DOMContentLoaded', () => {
      document.querySelectorAll('.audio-container').forEach(container => {
        const audio = container.querySelector('audio');
        const playBtn = container.querySelector('.playPauseBtn');
        const slider = container.querySelector('.progress');
        const timeDisplay = container.querySelector('.time');

        playBtn.addEventListener('click', () => {
          // Pause les autres lecteurs
          document.querySelectorAll('audio').forEach(other => {
            if (other !== audio) {
              other.pause();
              const otherBtn = other.closest('.audio-container').querySelector('.playPauseBtn');
              if (otherBtn) otherBtn.textContent = '▶️ Lire';
            }
          });

          if (audio.paused) {
            audio.play();
            playBtn.textContent = '⏸️ Pause';
          } else {
            audio.pause();
            playBtn.textContent = '▶️ Lire';
          }
        });

        audio.addEventListener('loadedmetadata', () => {
          slider.max = audio.duration;
          timeDisplay.textContent = `${formatTime(audio.currentTime)} / ${formatTime(audio.duration)}`;
        });

        audio.addEventListener('timeupdate', () => {
          slider.value = audio.currentTime;
          timeDisplay.textContent = `${formatTime(audio.currentTime)} / ${formatTime(audio.duration)}`;
        });

        slider.addEventListener('input', () => {
          audio.currentTime = slider.value;
        });

        audio.addEventListener('ended', () => {
          playBtn.textContent = '▶️ Lire';
        });
      });
    });
  </script>

</head>

<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-X7XL4BEXMD"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag() { dataLayer.push(arguments); }
  gtag('js', new Date());

  gtag('config', 'G-X7XL4BEXMD');
</script>

<body>

  <!-- HEADER -->
  <header>
    <!-- Logo + nom du site -->
    <a href="/" class="logo" style="text-decoration: none;">
      <div class="logo">
        <img src="../resources/noodle.png" alt="NoodleML">
        Noodle<div class="ml-text">ML</div>
      </div>
    </a>

    <!-- Menu de navigation -->
    <div class="nav-links">
    </div>
  </header>

  <!-- CONTENU PRINCIPAL -->
  <main>

    <section class="content-section">

      <h1 class="gradient-text">Chapitre 3 : Le perceptron ou la naissance d’un neurone numérique</h1>

      <div class="bandeau-image bordered">
        <img src="../resources/chap-03/soma-bandeau.jpg" alt="cerveau electrifié frankenstein">
      </div>

      <!-- Bloc Udon avec image à droite -->
      <div class="bloc-texte-image gauche">
        <div class="texte">
          <p>Avant toute chose : non, le perceptron n’a aucun lien de parenté avec Megatron. Toute ressemblance avec la
            franchise de science-fiction distribuée par Paramount Pictures et DreamWorks n’est donc due qu’au hasard… à
            moins qu’il ne s’agisse d’un Super Saïyen ?
            Ou bien encore d’un super-héros Marvel ?
            <br><br>

          </p>
          <p>
            <img class="image bordered_rounded" src="../resources/chap-03/perceptron-transformer.jpg"
              alt="Perceptron Transformer">
            <img class="image bordered_rounded" src="../resources/chap-03/perceptron-super-saiyan.jpg"
              alt="Perceptron Super Saïyan">
            <img class="image  bordered_rounded" src="../resources/chap-03/perceptron-marvel.jpg"
              alt="Perceptron Marvel">
          </p>
          <p>
            Avouez-le : vous y avez pensé aussi ! Non ? <span aria-label="sourire" role="img">😄</span>
            Mais trêve de plaisanteries et revenons à la base : le neurone biologique.
          </p>
        </div>
        <div class="image">
          <div class="audio-container" data-id="chap-01">
            🎧 <button class="playPauseBtn">▶️ Lire</button>
            <audio preload="metadata">
              <source src="../resources/chap-03/audio.mp3" type="audio/mpeg">
              Votre navigateur ne supporte pas l'élément audio HTML5.
            </audio>

            <input type="range" class="progress" value="0" min="0" step="0.1">
            <span class="time">0:00 / 0:00</span>
          </div>

          <img src="../resources/udon/eureka-mini-droite.png" alt="Udon eureka">
        </div>
      </div>

      <div class="bloc-texte-image gauche">

        <div class="texte">
          <h2 class="gradient-text">Une cellule pour capter, décider, transmettre</h2>
          <p>
            Un neurone, c’est une cellule spécialisée avec trois zones clés :
          </p>
          <ul>
            <li>
              <strong>Dendrites</strong> : de multiples “antennes” qui reçoivent des signaux venant d’autres neurones.
            </li>
            <li>
              <strong>Soma (corps cellulaire)</strong> : un centre d’intégration où se cumulent les effets reçus.
            </li>
            <li>
              <strong>Axone</strong> : un long prolongement qui transmet un signal vers d’autres cellules, parfois
              très loin.
            </li>
          </ul>

          <p>
            Ces trois parties dessinent déjà une logique : “capter, décider, transmettre”. Ces trois parties dessinent
            déjà une logique : “capter, décider, transmettre”. Ce principe s'appelle également l'inférence.
          </p>

          <img class="bordered_rounded" src="../resources/chap-03/neurone-biologique-wikipedia.jpg"
            alt="Structure d'un neurone biologique."><br>
          Source : <a href="https://fr.wikipedia.org/wiki/Modèles_du_neurone_biologique" target="_blank">
            Modèles du neurone biologique - Wikipédia
          </a>
        </div>

        <div class="image">
          <img class="bordered_rounded" src="../resources/chap-03/soma-portrait.jpg" alt="Rossum’s Universal Robots">
        </div>

      </div>

      <div class="bloc-texte-image droite">

        <div class="texte">
          <h2 class="gradient-text">Mimétisme biologique</h2>
          <p>
            Le perceptron est l’un des premiers modèles de neurone artificiel conçu pour imiter le fonctionnement des
            neurones biologiques de façon simplifiée. Inventé par le psychologue américain
            <a href="https://fr.wikipedia.org/wiki/Frank_Rosenblatt" target="_blank">Frank Rosenblatt</a> en 1958,
            le perceptron est une brique fondamentale des réseaux de neurones modernes.
            Il a été conçu pour apprendre à partir d’exemples, en ajustant ses connexions internes
            pour minimiser l’erreur de ses prédictions.
          </p>

          <p>
            Et si l’on reparlait maintenant de nos deux sentinelles du chapitre précédent ?
            Appelons-les S1 et S2. Sur le schéma ci-contre, elles sont représentées par les entrées
            à gauche du perceptron. Elles reçoivent des signaux et les transmettent au
            <em>soma</em> (corps cellulaire) du perceptron. Une fois les signaux reçus,
            le perceptron les intègre et décide du signal de sortie à envoyer.
          </p>

          <p>
            Ci-dessous, voici notre premier véritable perceptron opérationnel avec ses deux entrées (S1, S2) et sa
            sortie (Alarme). L'état de
            l'alarme est représentée par la heatmap à droite.
          </p>

        </div>

        <div class="image">
          <img class="bordered_rounded" src="../resources/chap-03/perceptron-stylise.jpg" alt="Perceptron stylisé">
        </div>
      </div>



      <!-- Canvas -->
      <canvas id="canvas" width="880" height="460"></canvas>

      <p>
        La première chose à faire est de vérifier si nos sentinelles sont alertes !!!
        Retestez pour voir. Que se passe-t-il si seulement la sentinelle 2 déclenche son signal ?
        Problème, non ? On dirait bien que la deuxième sentinelle s’est endormie à son poste…
        <span aria-label="sourire" role="img">😄</span>
      </p>

      <!-- Contrôles des sentinelles -->
      <div align="center">
        <label>
          <input type="checkbox" id="sent1"> Sentinelle 1
        </label>
        <label style="margin-left: 20px;">
          <input type="checkbox" id="sent2"> Sentinelle 2
        </label>

        <!-- Affichage de l'alarme -->
        <div id="alarme" style="margin-top: 10px;
              width: 150px;
              height: 30px;
              line-height: 30px;
              text-align: center;
              border: 2px solid #444;
              background-color: grey;
              color: white;
              font-weight: bold;">
          Alarme OFF
        </div>

      </div>

      <p>
        Maintenant, lancez l’entraînement du perceptron pour qu’il apprenne à réagir correctement aux signaux de S1 et
        S2. Incroyable… presque magique, n’est ce pas ? <span aria-label="clin d'œil" role="img">😉</span>
      </p>

      <p align="center">
        <button id="trainBtn">▶️ Entrainement / Pause</button>
        <button id="resetBtn">🔁 Réinitialiser</button>
      </p>

      <!-- Scripts nécessaires -->
      <script src="../js/ActivationFunctions.js"></script>
      <script src="../js/Perceptron.js"></script>
      <script src="../js/Neuron.js"></script>
      <script src="../js/HeatmapView.js"></script>
      <script src="../js/LogicFunctionHeatmapView.js"></script>
      <script src="../js/NeuronView.js"></script>
      <script src="../js/NeuralConnectionView.js"></script>
      <script src="../js/PerceptronView.js"></script>

      <script>
        class Controller {

          constructor() {

            this.canvas = document.getElementById("canvas");
            this.ctx2D = this.canvas.getContext("2d");

            this.perc = null;
            this.view = null;
            this.training = false;
            this.epoch = 0;
            this.errorRate = 1.0;

            this.createPerceptron();
            this.updateInputAndTargets();
            this.loop();

            // Variables pour gérer le clignotement
            this.blinkInterval = null;
            this.blinkState = false;

            this.calculateErrorRate();

            this.needsDisplay = true;
          }

          createPerceptron() {
            this.perc = new Perceptron(2, 0.01);

            this.perc.weights = [1, 0.25]; // Initialisation des poids
            this.perc.bias = -0.4; // Initialisation du biais

            const rect = { x: 40, y: 80, width: 320, height: 380 };
            this.view = new PerceptronView(this.ctx2D, rect, this.perc, ["S1", "S2"], ["Alarme"], 120);
            this.view.setLabelDisplayMode("value");
          }

          updateInputAndTargets() {

            // Entrées possibles pour les sentinelles
            this.inputs = [
              [0, 0],
              [1, 0],
              [0, 1],
              [1, 1]
            ];

            // Cibles pour la fonction OR
            this.targets = [[0], [1], [1], [1]];
          }

          trainStep() {

            for (let k = 0; k < 1; k++) {
              const i = Math.floor(Math.random() * this.inputs.length);
              this.perc.train(this.inputs[i], this.targets[i][0]);
              this.epoch++;
            }

            this.calculateErrorRate();
          }

          calculateErrorRate() {
            let mse = 0;
            for (let i = 0; i < this.inputs.length; i++) {
              const pred = this.perc.predict(this.inputs[i]);
              const err = pred - this.targets[i][0];
              mse += err * err;
            }
            this.errorRate = mse / this.inputs.length;

            if (this.errorRate < 0.01) {
              this.training = false;
            }
          }

          render() {
            const ctx = this.ctx2D;
            ctx.clearRect(0, 0, this.canvas.width, this.canvas.height);
            this.view.draw();

            ctx.fillStyle = "#ccc";
            ctx.font = "14px sans-serif";
            ctx.fillText("Époque : " + Math.floor(this.epoch), 20, 30);
            ctx.fillText("Taux d'erreur : " + this.errorRate.toFixed(3), 20, 50);
            ctx.fillStyle = this.training ? "#ff0" : "#0f0";
            ctx.fillText(
              !this.training && this.errorRate < 0.01
                ? "Entraînement ok."
                : this.training ? "Entraînement en cours..." : "Pause",
              20, 70
            );

            const netLike = {
              predict: (xy) => [this.perc.predict(xy)],
              inputNames: ["S1", "S2"],
            };

            const plotSize = 300;
            const heatmapView = new LogicFunctionHeatmapView(
              ctx,
              this.canvas.width - plotSize - 80,
              (this.canvas.height - plotSize) / 2 + 20,
              plotSize,
              netLike
            );
            heatmapView.draw("Heatmap - Alarme");
            heatmapView.drawLogicalPoints(this.targets);
          }

          setupUI() {
            document.getElementById("trainBtn").onclick = () => {
              this.training = !this.training;
              this.needsDisplay = true;
            };

            document.getElementById("resetBtn").onclick = () => {
              this.epoch = 0;
              this.training = false;
              this.createPerceptron();
              this.needsDisplay = true;
            };
          }

          // Fonction de test
          testerAlarme() {
            const s1 = document.getElementById("sent1").checked ? 1 : 0;
            const s2 = document.getElementById("sent2").checked ? 1 : 0;

            // Calcul de la sortie
            const sortie = this.perc.predict([s1, s2]);
            if (sortie !== undefined) {

              // Seuil 0.5 pour activer
              this.setAlarmeState(sortie >= 0.5);
            }
          }

          setAlarmeState(isOn) {
            const alarmeDiv = document.getElementById("alarme");

            // Si alarme activée -> démarrer clignotement
            if (isOn) {
              alarmeDiv.textContent = "ALARME ON";
              if (!this.blinkInterval) {
                this.blinkInterval = setInterval(() => {
                  this.blinkState = !this.blinkState;
                  alarmeDiv.style.backgroundColor = this.blinkState ? "red" : "grey";
                }, 500); // vitesse de clignotement
              }
            } else {
              // Stopper clignotement et remettre en gris
              alarmeDiv.textContent = "Alarme OFF";
              alarmeDiv.style.backgroundColor = "grey";
              if (this.blinkInterval) {
                clearInterval(this.blinkInterval);
                this.blinkInterval = null;
              }
            }
          }

          loop() {
            if (this.training) {
              this.trainStep();
              this.needsDisplay = true;
            }

            this.testerAlarme();

            if (this.needsDisplay) {
              this.calculateErrorRate();

              this.render();
              this.needsDisplay = false;
            }

            requestAnimationFrame(() => this.loop());
          }
        }

        const app = new Controller();
        app.setupUI();

      </script>

      <p>
        Interprétation de l'entraînement: Le perceptron apprend à ajuster ses paramètres lors de l'entraînement.
        Sur la heatmap, il en résulte que la droite de décision (frontière entre le rouge et le vert) finit par se
        positionner de
        manière à séparer correctement les entrées (S1, S2): les points verts doivent être situés dans la partie verte
        et le point rouge dans la partie rouge.
      </p>

      <div class="bloc-texte-image gauche">

        <div class="texte">
          <h2 class="gradient-text">Derrière le miroir...</h2>

          <blockquote class="citation">
            <i>"Toute technologie suffisamment avancée est indiscernable de la magie."</i> <br> <a
              href="https://fr.wikipedia.org/wiki/Arthur_C._Clarke">- Arthur C. Clarke</a>
          </blockquote>
          <p>

          <p>
            Cette citation, nous invite à passer à présent dans les coulisses pour appréhender la mécanique du
            perceptron. Voici donc sa représentation schématique générique:
          </p>

          <!-- Canvas -->
          <canvas id="canvas1" width="480" height="220"></canvas>

          <script>
            class Controller2 {

              constructor() {

                this.canvas = document.getElementById("canvas1");
                this.ctx2D = this.canvas.getContext("2d");

                this.perc = null;
                this.view = null;

                this.createPerceptron();
                this.updateInputAndTargets();

                this.render();
              }

              createPerceptron() {
                this.perc = new Perceptron(2, 0.01);

                this.perc.name = "";
                this.perc.weights = [1, 1]; // Initialisation des poids
                this.perc.bias = 0; // Initialisation du biais

                const rect = { x: 50, y: -80, width: 320, height: 380 };
                this.view = new PerceptronView(this.ctx2D, rect, this.perc, ["x1", "x2"], ["y"], 120);
                this.view.setLabelDisplayMode("label");
              }

              updateInputAndTargets() {

                // Entrées possibles pour les sentinelles
                this.inputs = [
                  [0, 0],
                  [1, 0],
                  [0, 1],
                  [1, 1]
                ];

                // Cibles pour la fonction OR
                this.targets = [[0], [1], [1], [1]];
              }

              render() {
                const ctx = this.ctx2D;
                ctx.clearRect(0, 0, this.canvas.width, this.canvas.height);

                ctx.fillStyle = "#ccc";
                ctx.font = "14px sans-serif";

                this.view.draw();
              }
            }

            const app2 = new Controller2();

          </script>

        </div>

        <div class="image">
          <img class="bordered_rounded" src="../resources/chap-03/miroir.jpg" alt="Perceptron stylisé">
        </div>
      </div>

      <div class="bloc-texte">
        <p>
          Le perceptron est un modèle de neurone artificiel qui intègre des entrées pondérées, applique une fonction
          d'activation et produit une sortie. Mais comment cela fonctionne concrètement ? Voici la formule
          mathématique avec 2 entrées:
          \[
          y = w_{x1} \cdot x1 + w_{x2} \cdot x2 + b
          \]

          avec :
        <ul>
          <li><strong>\( x1 \)</strong> : valeur d’entrée (de notre sentinelle 1 par exemple )</li>
          <li><strong>\( x2 \)</strong> : valeur d’entrée (de notre sentinelle 2 par exemple )</li>

          <li><strong>\( w_{x1} \)</strong> : poids (un réglage que le modèle peut modifier)</li>
          <li><strong>\( w_{x2} \)</strong> : poids (un réglage que le modèle peut modifier)</li>

          <li><strong>\( b \)</strong> : biais (un ajustement fixe que le modèle peut modifier)</li>

          <li>\( y \) : la valeur de sortie</li>
        </ul>
        </p>

        <p>
          Trop compliqué? Pas de panique! Simplifions encore un peu en ne prenant qu'une seule entrée pour faciliter
          la
          compréhension du principe de fonctionnement.

          <!-- Canvas -->
          <canvas id="canvas3" width="480" height="220"></canvas>

          <script>
            class Controller3 {

              constructor() {

                this.canvas = document.getElementById("canvas3");
                this.ctx2D = this.canvas.getContext("2d");

                this.perc = null;
                this.view = null;

                this.createPerceptron();
                this.updateInputAndTargets();

                this.render();
              }

              createPerceptron() {
                this.perc = new Perceptron(1, 0.01);

                this.perc.name = "";
                this.perc.weights = [1]; // Initialisation des poids
                this.perc.bias = 0; // Initialisation du biais

                const rect = { x: 50, y: -80, width: 320, height: 380 };
                this.view = new PerceptronView(this.ctx2D, rect, this.perc, ["x"], ["y"], 120);
                this.view.setLabelDisplayMode("label");
              }

              updateInputAndTargets() {

                // Entrées possibles pour les sentinelles
                this.inputs = [
                  [0, 0],
                  [1, 0],
                  [0, 1],
                  [1, 1]
                ];

                // Cibles pour la fonction OR
                this.targets = [[0], [1], [1], [1]];
              }

              render() {
                const ctx = this.ctx2D;
                ctx.clearRect(0, 0, this.canvas.width, this.canvas.height);

                ctx.fillStyle = "#ccc";
                ctx.font = "14px sans-serif";

                this.view.draw();
              }
            }

            const app3 = new Controller3();

          </script>

          Nous obtenons alors la formule simplifiée:
          \[
          y = w_{x} \cdot x + b
          \]

          Cela ne vous rappelle-t-il pas quelque chose ? Oui, cela ressemble à s’y méprendre à la formule d’une
          fonction
          affine que vous avez normalement étudiée en mathématiques en classe de 3ᵉ.
          <span aria-label="clin d'œil" role="img">😉</span>
          \[
          \ y = a \cdot x + b
          \]
          C'est donc une simple équation de droite avec :
        <ul>
          <li><strong>\( x \)</strong> : la valeur d'entrée</li>
          <li><strong>\( a \)</strong> : la pente de la droite</li>
          <li><strong>\( b \)</strong> : l'ordonnée à l'origine</li>
          <li><strong>\( y \)</strong> : la valeur de sortie</li>
        </ul>
        </p>

        <p>Faisons le parallèle avec l'entrée de notre perceptron:</p>

        <p>
          La pente de la droite c'est le poids (ou "weight") :
        <ul>
          <li>Plus le poids est important et plus la valeur d'entrée est amplifiée: la connexion est forte.</li>
          <li>Plus le poids est faible et plus la valeur d'entrée est atténuée: la connexion est faible.</li>
          <li>Le poids peut être positif ou négatif, ce qui influence la direction de l'activation.</li>
        </ul>
        </p>

        <p>
          Dans le cadre d'un neurone biologique, on pourrait dire que cela correspond à la sensibilité de la
          dendrite.
        </p>

        <p>
          L'ordonnée à l'origine c'est le biais (ou bias):
        <ul>
          <li>Plus le biais est important et moins le perceptron active facilement sa sortie.</li>
          <li>Plus le biais est faible et plus le perceptron active facilement sa sortie.</li>
          <li>Le biais peut être positif ou négatif.</li>
        </ul>
        </p>

        <p>
          Dans le cadre d'un neurone biologique, on pourrait dire que le biais correspond à la sensibilité
          d'activation
          du soma.
        </p>

      </div>

      <p>En résumé :

      <table border="1" cellpadding="8" cellspacing="0">
        <thead>
          <tr>
            <th>Composant numérique</th>
            <th>Élément biologique associé</th>
            <th>Rôle</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>Poids d'une liaison</td>
            <td>Dendrite / Synapse</td>
            <td>Influence de chaque entrée</td>
          </tr>
          <tr>
            <td>Biais du perceptron</td>
            <td>Seuil du soma</td>
            <td>Déclenchement ou non de l’activation</td>
          </tr>
        </tbody>
      </table>
      </p>

      <p>
        À ce stade, notre perceptron sait déjà faire beaucoup : il reçoit des entrées, les pondère, les additionne,
        puis
        génère une sortie.
      </p>

      <p>
        Nous avons cependant laissé une notion importante de côté pour l'instant: la fonction d'activation. Ici le
        perceptron utilise une fonction d'activation de type seuil qui simule le fonctionnement binaire d'une TLU:
      <ul>
        <li>
          Si la valeur de sortie est supérieure ou égale à 0.5, alors le perceptron s'active et met sa sortie à 1.
        </li>
        <li>
          Sinon, il reste inactif et produit une sortie à 0.
        </li>
      </ul>
      </p>

      <!-- GRAPHE 1 : Binaire -->
      <div>
        <script src="../js/ActivationGraph.js"></script>
        <canvas id="canvas0" width="480" height="300"></canvas>
      </div>

      <script>

        function setupGraph(canvasId, controlsId, title, config = {}) {
          const graph = new ActivationGraph(document.getElementById(canvasId), {
            title,
            colors: config.colors,
            active: config.active,
            xMin: config.xMin ?? -5,
            xMax: config.xMax ?? 5,
            yMin: config.yMin ?? -2,
            yMax: config.yMax ?? 2,
            labelX: "z",
            labelY: "f(z)",
          });

          const controls = document.getElementById(controlsId);
          controls.innerHTML = "";
        }


        setupGraph("canvas0", "controls0", "Activation binaire", {
          active: { binary: true }
        });

      </script>

      <p>
        Nous reviendrons sur la notion d'activation en détail plus loin car un
        perceptron peut faire bien plus.
      </p>

      <p>
        En attendant, McCulloch et Pitts avaient donc raison avec leur neurone formel, véritable sentinelle de
        l’activation.
        Mais une question demeure : un neurone logique peut-il aller plus loin ? Est-il capable de résoudre des
        problèmes
        plus complexes qu’une simple porte logique OU? Et si on testait ses limites ?
      </p>
      <p>
        Prêt pour le chapitre suivant? Voyons donc si notre neurone est vraiment à la hauteur...
      </p>

      <!-- Lien vers le chapitre précédent et suivant -->
      <br>
      <div class="navigation-links">
        <a href="./chap-02-qu-est-ce-que-l-intelligence-artificielle.php" class="previous">
          << Chapitre précédent </a>
            |
            <a href="chap-04-perceptron-et-portes-logiques.php" class="next">
              Chapitre suivant >>
            </a>
      </div>
      <br>

      <hr>
      <img src="../resources/bts-sio.png" class="image" alt="BTS SIO SLAM">
      <h1 class="gradient-text">
        Activité pratique de développement logiciel : Implémenter un perceptron en POO
      </h1>

      <h3>BTS SIO option SLAM (Solutions Logicielles et Applications Métiers)</h3>

      <b>Objectif pédagogique</b>
      <p>
        Mettre en pratique les concepts de programmation orientée objet (POO) à travers l’implémentation d’un perceptron
        simple, en respectant les principes vus en cours :
      <ul>
        <li>Encapsulation (attributs privés + accesseurs/mutateurs).</li>
        <li>Organisation en classe.</li>
        <li>Fonction de calcul de sortie.</li>
      </ul>
      </p>

      <b>Compétences visées</b>
      <p>
      <ul>
        <li>Développer des composants logiciels réutilisables et testables.</li>
        <li>Utiliser les principes de la programmation orientée objet pour structurer le code.</li>
        <li>Appliquer des algorithmes d’apprentissage simples.</li>
      </ul>
      </p>

      <b>Prérequis</b>
      <p>
        Connaissances de base en programmation orientée objet (POO).
      </p>

      <b>Durée</b>
      <p>
        1 heures.
      </p>

      <b>Matériel nécessaire</b>
      <p>
        Un ordinateur avec un éditeur de code (comme Visual Studio Code) et outils de compilation/Interprétation si
        nécessaire en fonction du langage.
      </p>

      <b>Consignes</b>
      <ul>
        <li>Choisissez le langage de votre choix : JavaScript, C++, Java, Python, PHP, Dart, etc.</li>
        <li>Créez une classe Perceptron avec les attributs suivants :
          <ul>
            <li>
              poids : tableau/list des poids des entrées (par exemple deux valeurs si vous simulez deux sentinelles S1
              et
              S2).
            </li>
            <li>
              biais : valeur numérique.
            </li>
            <li> sortie : valeur de la sortie calculée.
            </li>
          </ul>
        </li>
        <li>Ajoutez les méthodes suivantes :
          <ul>
            <li>Constructeur permettant d’initialiser les poids et le biais.</li>
            <li>Accesseurs (get) et mutateurs (set) pour les poids et le biais.</li>
            <li>Une méthode predict(inputs) :
              <ul>
                <li>Calcule la somme pondérée des entrées.</li>
                <li>Ajoute le biais.</li>
                <li>Applique une fonction d’activation seuil (si somme ≥ 0.5 → sortie = 1 ; sinon sortie = 0).</li>
                <li>Retourne la valeur de sortie.</li>
              </ul>
            </li>
          </ul>
        </li>
      </ul>

      <b>Diagramme UML de classe</b>
      <img src="../resources/chap-03/uml-diagram.jpg" class="bordered_rounded image-centree"
        alt="Diagramme UML de classe">
      <br>

      <b>Travail à réaliser</b>
      <ul>
        <li>Implémentez la classe Perceptron dans le langage choisi.</li>
        <li>Instanciez un perceptron avec deux entrées (représentant S1 et S2).</li>
        <li>Testez différents cas :
          <ul>
            <li>(0,0), (0,1), (1,0), (1,1) → comparez les sorties.</li>
            <li>Modifiez les poids et le biais à l’aide des mutateurs, puis observez comment la sortie change.</li>
            <li>Expliquez le rôle des poids et du biais dans la décision finale.</li>
          </ul>
        </li>
      </ul>

    </section>

    <hr>
  </main>

  <!-- FOOTER -->
  <footer>
    <div class="footer-info">
      <img src="../resources/bts-sio-gap.png" alt="">
      <div class="ml-text">Lycée Dominique Villars, Gap, 05000<br></div>
      <hr>
      <div class="gradient-text">© 2025</div>
    </div>
  </footer>

</body>

</html>