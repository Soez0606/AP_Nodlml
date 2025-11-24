<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <title>NoodleML - Chapitre 2 : Qu’est-ce que l’intelligence artificielle ?</title>

  <!-- Balises META pour SEO et réseaux sociaux -->

  <!-- SEO classique -->
  <title>NoodleML - Chapitre 2 : Qu’est-ce que l’intelligence artificielle ?</title>
  <meta name="description"
    content="Voyagez à travers l'histoire de l'intelligence artificielle avec Udon, votre guide numérique. Illustrations, anecdotes et pédagogie au rendez-vous.">
  <meta name="keywords"
    content="NoodleML, IA, intelligence artificielle, histoire IA, Udon, apprentissage, pédagogie, réseau de neurones, BTS SIO, C++, JavaScript">
  <meta name="author" content="Sébastien Marchand">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- Open Graph pour Facebook et autres -->
  <meta property="og:title" content="NoodleML - Chapitre 2 : Qu’est-ce que l’intelligence artificielle ?">
  <meta property="og:description"
    content="Voyagez à travers l'histoire de l'intelligence artificielle avec Udon, votre guide numérique. Illustrations, anecdotes et pédagogie au rendez-vous.">
  <meta property="og:image" content="https://noodleml.com/resources/chap-02/garry-kasparov-contre-deep-blue.jpg">
  <meta property="og:url" content="https://noodleml.com/chap-02-qu-est-ce-que-l-intelligence-artificielle.html">
  <meta property="og:type" content="article">
  <meta property="og:locale" content="fr_FR">
  <meta property="og:site_name" content="NoodleML">

  <!-- Twitter Card -->
  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:title" content="NoodleML - Chapitre 2 : Qu’est-ce que l’intelligence artificielle ?">
  <meta name="twitter:description"
    content="Découvrez les origines et l’évolution de l’IA avec une approche pédagogique originale signée Udon.">
  <meta name="twitter:image" content="https://noodleml.com/resources/chap-02/garry-kasparov-contre-deep-blue.jpg">


  <!-- Feuille de style -->
  <link rel="stylesheet" href="../css/style-noodleml.css">
  <link rel="stylesheet" href="../css/audio-player.css">
  <link rel="icon" href="../resources/NoodleML.png" type="image/png">


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

      <h1 class="gradient-text">Chapitre 2 : Qu’est-ce que l’intelligence artificielle ?</h1>

      <div class="bandeau-image bordered">
        <img src="../resources/chap-02/garry-kasparov-contre-deep-blue-bandeau.jpg"
          alt="Garry Kasparov contre Deep Blue">
      </div>

      <!-- Bloc Udon avec image à droite -->
      <div class="bloc-texte-image gauche">
        <div class="texte">
          <p>L'intelligence, un mot simple à prononcer… mais pour autant complexe à définir. </p>
          <p>Albert Einstein disait : <i>“The measure of intelligence is the ability to change”</i>, soit en français :
            <i>“La
              mesure de l’intelligence, c’est la capacité de s’adapter”.</i>
          </p>
          <p>Chez l’être humain, cette adaptation se manifeste par la créativité, l’intuition, la flexibilité… autant de
            qualités naturelles mais difficiles à traduire en code binaire pour un “cerveau” d’ordinateur.</p>
          <p>Et pour comprendre ce défi, faisons un petit détour par… la cuisine! Noodle oblige, imaginez un paquet de
            spaghettis. Parmi eux, un spaghetti un peu plus long que les autres. Ensuite, vous
            mélangez le paquet.
          </p>
          <p>
            Question : comment retrouver le spaghetti le plus long ?
          </p>
        </div>
        <div class="image">
          <div class="audio-container" data-id="chap-01">
            🎧 <button class="playPauseBtn">▶️ Lire</button>
            <audio preload="metadata">
              <source src="../resources/chap-02/audio.mp3" type="audio/mpeg">
              Votre navigateur ne supporte pas l'élément audio HTML5.
            </audio>

            <input type="range" class="progress" value="0" min="0" step="0.1">
            <span class="time">0:00 / 0:00</span>
          </div>

          <img src="../resources/udon/gratte_le_nez-mini-droite.png" alt="Udon heureux">
        </div>
      </div>

      <!-- Bloc texte + image à droite -->
      <div class="bloc-texte-image droite">
        <div class="texte">
          <h2 class="gradient-text">L’approche “programmation classique”</h2>
          <p>Un programme informatique traditionnel suivra une logique méthodique :</p>

          <ul>
            <li>Prendre un spaghetti comme référence.</li>
            <li>Comparer sa longueur à un autre spaghetti.</li>
            <li>Si le second est plus long, il devient la nouvelle référence.</li>
            <li>Répéter jusqu’à avoir comparé tous les spaghettis.</li>
          </ul>

          <p>
            En d’autres termes, on suit une procédure séquentielle, étape par étape, sans jamais dévier de la
            recette. Pour illustrer cette approche, vous pouvez essayer l’activité Blockly ci-jointe.
          </p>

          <p>
            Résultat : le programme finit par trouver le plus long après un certain nombre d’étapes
            prévues à l’avance.
          </p>

        </div>

        <div>
          <!-- Lien vers activité Blockly -->
          <div style="display:flex; justify-content:center; margin: 20px 0;">
            <a href="./resources/chap-02/spaghetti_blockly.html" target="_blank"
              style="text-decoration:none; color:inherit;">
              <figure class="image">
                <img src="../resources/chap-02/spaghetti_blockly.png" alt="Activité Blockly">
                <figcaption
                  style="padding:10px; background:#333; color:#fff; font-size:15px; font-weight:200; letter-spacing:0.5px; text-align:center;">
                  🧩 Activité Blockly
                </figcaption>
              </figure>
            </a>
          </div>
        </div>
      </div>

      <!-- Bloc texte + image à gauche -->
      <div class="bloc-texte-image gauche">
        <div class="texte">

          <h2 class="gradient-text">L’approche “intelligence humaine”</h2>

          <p>
            Un être humain, lui, risque de prendre le paquet entier dans une main et de simplement taper le bas du
            paquet sur la
            table.
          </p>

          <p>En un instant, le spaghetti le plus long dépasse… problème réglé.
            Ce geste n’est pas un tour de magie : c’est le résultat d’une expérience accumulée. Sans dérouler une
            check-list, l’humain reformule le problème (“comment faire émerger le plus long ?”) et applique une
            heuristique – un raccourci pratique appris au fil du temps. Il ajuste l’intensité du geste, observe le
            résultat, corrige si besoin : c’est une boucle d’essai-erreur ultra-rapide.
          </p>

          <p>Autrement dit, l’humain ne suit pas seulement des instructions : il apprend.</p>
          <ul>
            <li>Il transfère ce qu’il sait d’autres situations (aligner, secouer, trier “en bloc”).</li>
            <li>Il exploite le feedback immédiat (le spaghetti dépasse… ou pas).</li>
            <li>Il ajuste son action si besoin.</li>
            <li>Il généralise : la prochaine fois, il saura refaire et sans doute mieux encore, même avec d'autres
              objets.</li>
          </ul>

          <p>
            C’est bien là toute la différence : l’humain adapte sa méthode en fonction de la situation et invente des
            stratégies efficaces en dehors d’une procédure prédéfinie.
          </p>

          <p>
            <b>À retenir pour la suite du cours :</b><br>
            L’apprentissage, c’est le cycle (exemples → feedback → ajustements → meilleure stratégie).
            En IA, l’enjeu sera de donner aux machines cette capacité à extraire, à partir de données et de retours, des
            raccourcis efficaces qui fonctionnent au-delà des cas vus. Nous formaliserons bientôt ce mécanisme (erreur,
            correction, renforcement) mais gardons en tête que tout part de là : apprendre pour s’adapter.
          </p>

        </div>

        <div class="image">
          <img class="bordered_rounded" src="../resources/chap-02/spaghettis.jpg" alt="Rossum’s Universal Robots">
        </div>

      </div>



      <div class="bloc-texte-image droite">

        <div class="texte">
          <h2 class="gradient-text">Là où l’IA entre en jeu</h2>
          <p>
            En somme, l’IA cherche donc à combler ce fossé : au lieu de coder une recette figée, on veut une machine
            capable d’apprendre une manière d’agir à partir d’exemples. Et c'est exactement ce
            qu’évoquait Einstein!
          </p>
          <p>
            Maintenant fermez les yeux, on rembobine. Avant les robots bavards et les applis malignes, il y a d’abord la
            rumeur sourde d’un monde en guerre: la Seconde Guerre mondiale.
          </p>
          <p>
            Car l'intelligence artificielle, ne date pas d'hier. Ses racines remontent au siècle dernier, dans les
            années 1940, à une époque où les machines étaient encore des monstres de câbles et de tubes, loin de nos
            smartphones et des algorithmes sophistiqués d’aujourd’hui.
          </p>
        </div>

        <div class="image">
          <img class="bordered_rounded" src="../resources/chap-02/albert-einstein.jpg" alt="Rossum’s Universal Robots">
        </div>
      </div>

      <div class="bloc-texte-image gauche">
        <div class="texte">
          <h2 class="gradient-text">Naissance du neurone binaire... sur le papier (1943)</h2>
          <p>
            En 1943, Warren McCulloch et Walter Pitts publient un article fondateur :
            <em>A Logical Calculus of Ideas Immanent in Nervous Activity</em>.
            Ils y décrivent un modèle très simple de neurone :
            additionner les signaux d’entrée, comparer la somme à un seuil,
            et décider d’une sortie — activé (ON) ou éteint (OFF).
          </p>
          <p>
            Pour l’expliquer, imaginez deux sentinelles sur un toit :
            si au moins une crie “danger”, la sirène retentit.
            Avec ce principe, McCulloch et Pitts montrent que l’on peut construire
            des opérations logiques comme le ET, le OU ou le NON.
          </p>
          <p>
            Rien de biologique au sens strict, mais une idée révolutionnaire :
            avec de simples décisions binaires, il devient possible de faire émerger du calcul.
            Ce concept portera un nom : la <strong>TLU</strong>, pour <em>Threshold Logic Unit</em>,
            première brique des neurones artificiels.
          </p>

        </div>

        <div class="image">
          <img class="bordered_rounded" src="../resources/chap-02/alerte.jpg" alt="Rossum’s Universal Robots">
        </div>
      </div>

      <h3>Exemple de sortie de neurone logique OR à deux entrées</h3>

      <p>
        Une porte logique OR s'active si au moins une des entrées est à 1. Elle représente donc la logique "OU" où la
        sortie est 1 si l'une ou l'autre des entrées est active.
      </p>

      <p>
        <b>Table de vérité - Porte OU (OR)</b>
      <table>
        <tr>
          <th>Sentinelle 1</th>
          <th>Sentinelle 2</th>
          <th>Sortie</th>
        </tr>
        <tr>
          <td class="zero">0</td>
          <td class="zero">0</td>
          <td class="zero">0</td>
        </tr>
        <tr>
          <td class="zero">0</td>
          <td class="un">1</td>
          <td class="un">1</td>
        </tr>
        <tr>
          <td class="un">1</td>
          <td class="zero">0</td>
          <td class="un">1</td>
        </tr>
        <tr>
          <td class="un">1</td>
          <td class="un">1</td>
          <td class="un">1</td>
        </tr>
      </table>
      </p>
      <p>
        C'est le principe même de notre alarme de sentinelles évoquée précédemment. On peut également visualiser une
        sortie logique sous forme d'un graphique aussi nommé "heatmap" ou en francais "carte de chaleur". Ce type de
        représentation s'avère très pratique pour faire apparaitre la frontière d'activation en fonction de 2 entrées.
      </p>
      <p>
        Voici une visualisation heatmap interactive en 2D/3D. Cliquer sur le bouton "2D/3D" pour basculer entre les
        deux modes de visualisation :
      </p>

      <div class="viewer" id="or-viewer" align="center" style="width: 500px; height: 400px; margin: 20px auto;">
        <div class="label">OR</div>
        <div class="toggle" onclick="toggleProjection('or-viewer')">2D/3D</div>
      </div>

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
        En 2D, chaque axe représente l’état d'une entrée :
      <ul>
        <li>
          Axe horizontal : état de la Sentinelle 1 (0 ou 1).
        </li>
        <li>
          Axe vertical : état de la Sentinelle 2 (0 ou 1).
        </li>
      </ul>
      </p>
      <p>
        La couleur indique l’état de l’alarme. On constate clairement que la limite entre la zone rouge (Alarme
        inactive) et la verte (alarme active) est une ligne droite. Nous y revindrons par la suite.
      </p>
      <p>
        Lorsque l'on passe en 3D, on visualise encore plus concrètement la frontière d'activation.
      </p>
      <p>
        Amusez vous maintenant à manipuler les entrées des sentinelles en les cochant/décochant pour voir comment cela
        affecte l'état de l'alarme.
      </p>
      <p>
        L'air de rien, vous êtes déjà en train de jouer, ici même, avec un véritable neurone
        logique géré par le code de NoodleML!
      </p>

      <script src="../visualiseur-neurones-3d/three.min.js"></script>
      <script src="../visualiseur-neurones-3d/OrbitControls.js"></script>
      <script src="../visualiseur-neurones-3d/NeuralViewer.js"></script>
      <script src="../NoodleML/Model/ActivationFunctions.js"></script>
      <script src="../NoodleML/Model/Perceptron.js"></script>

      <script>
        // Création du perceptron OR
        let perceptron_or = new Perceptron(2, 1, false);
        perceptron_or.setWeights([0.501, 0.501]);
        perceptron_or.setBias(0);
        perceptron_or.set

        const viewers = {
          "or-viewer": new NeuralViewer('or-viewer', 'OR', perceptron_or)
        };

        // Avec un neurone logique, on n'a pas besoin de visualiser le seuil
        viewers["or-viewer"].removeTresholdPane();

        function toggleProjection(id) {
          viewers[id].toggleProjection();
        }

        // Variable pour gérer le clignotement
        let blinkInterval = null;
        let blinkState = false;

        // Fonction de mise à jour visuelle de l'alarme
        function setAlarmeState(isOn) {
          const alarmeDiv = document.getElementById("alarme");

          // Si alarme activée -> démarrer clignotement
          if (isOn) {
            alarmeDiv.textContent = "ALARME ON";
            if (!blinkInterval) {
              blinkInterval = setInterval(() => {
                blinkState = !blinkState;
                alarmeDiv.style.backgroundColor = blinkState ? "red" : "grey";
              }, 500); // vitesse de clignotement
            }
          } else {
            // Stopper clignotement et remettre en gris
            alarmeDiv.textContent = "Alarme OFF";
            alarmeDiv.style.backgroundColor = "grey";
            if (blinkInterval) {
              clearInterval(blinkInterval);
              blinkInterval = null;
            }
          }
        }

        // Fonction de test
        function testerAlarme() {
          const s1 = document.getElementById("sent1").checked ? 1 : 0;
          const s2 = document.getElementById("sent2").checked ? 1 : 0;

          // Calcul de la sortie
          const sortie = perceptron_or.predict([s1, s2]);

          // Seuil 0.5 pour activer
          setAlarmeState(sortie >= 0.5);
        }

        // Événements sur les sentinelles
        document.getElementById("sent1").addEventListener("change", testerAlarme);
        document.getElementById("sent2").addEventListener("change", testerAlarme);

        // Initialisation
        testerAlarme();
      </script>


      <div class="bloc-texte-image doite">

        <div class="texte">

          <h2 class="gradient-text">De la guerre aux ordinateurs, l'IA approche (1939–1958)</h2>

          <p>
            Dans des salles sans fenêtre, des équipes décodent des messages chiffrés. Au cœur de l’effort allié, la
            machine Enigma — utilisée par la Kriegsmarine, la Luftwaffe et l’armée allemande — brouille chaque jour
            les
            communications grâce à des rotors, des anneaux de réglage et un tableau de connexions qui multiplient les
            combinaisons.
          </p>
          <p>

            À Bletchley Park, Alan Turing et ses collègues (notamment Gordon Welchman) reprennent l’avance
            des cryptologues polonais et conçoivent la Bombe, une machine électromécanique qui explore rapidement
            l’espace des clés en s’appuyant sur des “cribs” (mots probables) et des techniques de réduction comme le
            Banburismus. Chaque “clic” gagné sur l’énorme cadenas d’Enigma se traduit en renseignements opérationnels
            :
            itinéraires des U-Boots, cibles, horaires. Selon de nombreux historiens, cette lecture du trafic ennemi a
            changé le cours de la guerre dans l’Atlantique en sauvant des convois et en raccourcissant
            significativement
            le conflit.
          </p>

          <p>
            1950, Turing publie sa fameuse question — “les machines peuvent-elles penser ?” — et surtout propose un
            test. Plus besoin de métaphysique : si une machine soutient la conversation, c’est qu’elle fait quelque
            chose de très proche de penser. Le débat n’est plus seulement philosophique, il devient expérimental.
          </p>

          <p>
            Dans les années qui suivent, l’idée d’apprentissage quitte peu à peu la science-fiction. 1951, Marvin Minsky
            fabrique SNARC, un réseau de neurones matériel qui tâtonne et s’ajuste. 1952, Arthur Samuel fait jouer son
            programme de dames, partie après partie : la machine s’améliore en observant ses propres erreurs. 1954, la
            démonstration Georgetown-IBM traduit automatiquement des phrases ; le public découvre que des tâches
            “intelligentes” peuvent être attaquées par le calcul.
          </p>

          <p>
            Puis tout s’organise. 1956, à la conférence de Dartmouth, on baptise officiellement le champ : Intelligence
            Artificielle. Des
            équipes se forment, un vocabulaire commun émerge. 1957, John McCarthy crée LISP, un langage taillé pour
            manipuler symboles et idées : on peut représenter des connaissances, raisonner sur des structures, tenter de
            planifier.
          </p>

          <p>
            De la guerre aux laboratoires, de la logique du neurone à la question de Turing, des prototypes câblés aux
            premiers programmes qui apprennent, chaque jalon resserre l’étau entre calcul et intelligence. À la fin de
            cette décennie, tout est prêt : les machines savent représenter, comparer, corriger. La prochaine étape ?
            Faire en sorte qu’elles apprennent à s'entrainer par elles-mêmes à partir des données et ne soient plus
            limitées à des valeurs binaires (0 ou 1). Rideau sur ce chapitre; le suivant va s'ouvrir sur la première
            brique neuronale qui rendra cette promesse réellement concrète: Le Perceptron. Près de 20 ans se sont
            écoulés depuis la création des TLU. Nous arrivons alors en 1958...
          </p>

        </div>

        <div class="image">
          <img class="bordered_rounded" src="../resources/chap-02/enigma.png" alt="Rossum’s Universal Robots">
        </div>

      </div>

      <!-- Lien vers le chapitre précédent et suivant -->
      <div class="navigation-links">
        <a href="./chap-01-introduction.php" class="previous">
          << Chapitre précédent </a>
            |
            <a href="./chap-03-le-perceptron.php" class="next">
              Chapitre suivant >>
            </a>
      </div>

      <hr>
      <img src="../resources/bts-sio.png" class="image" alt="BTS SIO SLAM">
      <h1 class="gradient-text">
        Activité pratique de culture numérique : Explorer l’histoire de l’IA
      </h1>

      <h3>BTS SIO toutes options (SLAM et SISR)</h3>

      <b>Objectif pédagogique</b>
      <p>
        Situer les grandes étapes de l’histoire de l’intelligence artificielle et développer des compétences de
        recherche, de
        synthèse et de communication multimédia.
      </p>

      <b>Compétences visées</b>
      <p>
      <ul>
        <li>Rechercher et sélectionner des informations pertinentes.</li>
        <li>Présenter une information technique de façon claire et accessible.</li>
        <li>Utiliser des outils numériques de présentation (PPT, Canva, Genially, vidéo, etc.).</li>
        <li>Travailler en autonomie et, le cas échéant, collaborer en binôme.</li>
        <li>Restituer un travail oralement devant un public.</li>
      </ul>
      </p>

      <b>Prérequis</b>
      <p>
        Avoir étudié la frise chronologique de l’IA ci-après et connaître les bases de recherche documentaire.
      </p>

      <b>Durée</b>
      <p>
        2 heures : préparation + présentations par chaque élève ou binôme.
      </p>

      <b>Matériel nécessaire</b>
      <p>
        Un ordinateur avec accès Internet et un outil de présentation (PowerPoint, Canva, Genially, Prezi, etc.).<br>
        Vidéoprojecteur pour la restitution.
      </p>

      <b>Consignes</b>
      <ul>
        <li>Travail individuel ou en binôme.</li>
        <li>Choisissez un jalon de la frise entre 1949 et 2010 (ex. Turing, Perceptron, ELIZA, Deep Blue, AlphaGo,
          etc.).</li>
        <li>Effectuez des recherches pour documenter :
          <ul>
            <li>Le contexte historique et technologique.</li>
            <li>Les enjeux et apports majeurs.</li>
            <li>Un visuel (photo, schéma, capture, infographie).</li>
            <li>Une anecdote marquante (réaction du public, citation, conséquence inattendue).</li>
          </ul>
        </li>
        <li>Préparez une présentation multimédia (5 minutes max) pour exposer vos résultats.</li>
        <li>Présentez votre travail à la classe en mettant en évidence l’importance de ce jalon dans l’évolution de
          l’IA.</li>
      </ul>

      <b>Travail à réaliser</b>
      <ul>
        <li>Réaliser individuellement (ou en binôme) une présentation multimédia sur l’élément choisi de la frise.</li>
        <li>Présenter oralement le travail (5 minutes max).</li>
        <li>Déposer le support de présentation sur l’ENT à la fin de la séance.</li>
      </ul>


      <hr>

      <img src="../resources/bts-sio.png" class="image" alt="BTS SIO SLAM">
      <h1 class="gradient-text">
        Activité pratique de CEJMA : Analyser les biais d’une frise chronologique générée par l’IA
      </h1>

      <h3>BTS SIO toutes options (SLAM et SISR)</h3>

      <b>Objectif pédagogique</b>
      <p>
        Développer un esprit critique sur les sources d’information et comprendre que les représentations historiques ou
        technologiques peuvent être biaisées. Dans cet exemple, la frise chronologique étudiée a été générée via ChatGPT
        et présente une prépondérance marquée des avancées liées à ce modèle depuis 2019.
      </p>

      <b>Compétences visées</b>
      <p>
        ADU (A définir ultérieurement)
      </p>

      <b>Prérequis</b>
      <p>
        ADU
      </p>

      <b>Durée</b>
      <p>
        ADU
      </p>

      <b>Matériel nécessaire</b>
      <p>
        ADU
      </p>

      <b>Consignes</b>
      <p>
        ADU
      </p>

      <b>Travail à réaliser</b>
      <p>
        ADU
      </p>

      <hr>

      <div class="bloc-texte-image doite">

        <div class="texte">

          <h2 class="gradient-text">Frise chronologique IA</h2>

          <p>Quelques éléments clés de l'histoire de l'IA jusqu’à nos jours...</p>

        </div>

      </div>

      <!-- === Frise chronologique IA — NoodleML (autonome) === -->
      <section id="ai-timeline" data-accent="#ff8a65"></section>
      <script>
        (() => {
          const mount = document.currentScript.previousElementSibling;
          const ACCENT = mount.dataset.accent || "#ff8a65";

          // ---- Données (événements clés) ----
          const events = [
            { y: 1943, t: "McCulloch & Pitts", d: "Modèle de neurone artificiel (logique).", key: true },
            { y: 1950, t: "Alan Turing", d: "Test de Turing et essai « Computing Machinery and Intelligence ». ", key: true },
            { y: 1951, t: "SNARC (Minsky)", d: "Réseau de neurones matériel (précurseur)." },
            { y: 1952, t: "Arthur Samuel", d: "Apprentissage au jeu de dames (machine learning)." },
            { y: 1954, t: "Georgetown–IBM", d: "Démonstration de traduction automatique." },
            { y: 1956, t: "Conférence de Dartmouth", d: "Naissance officielle du terme « Intelligence Artificielle ». ", key: true },
            { y: 1957, t: "LISP (McCarthy)", d: "Langage phare de l’IA symbolique." },
            { y: 1958, t: "Perceptron (Rosenblatt)", d: "Premier classifieur neuronal entraînable.", key: true },
            { y: 1959, t: "ADALINE / ML", d: "Widrow & Hoff ; Samuel popularise « machine learning ». " },
            { y: 1965, t: "DENDRAL", d: "Système expert pour la chimie (Stanford)." },
            { y: 1966, t: "ELIZA", d: "Chatbot de Weizenbaum (thérapie Rogérienne)." },
            { y: 1968, t: "Shakey", d: "Robot mobile avec perception et planification." },
            { y: 1969, t: "Perceptrons (Minsky & Papert)", d: "Limites des réseaux simples → 1er hiver de l’IA.", key: true },
            { y: 1972, t: "PARRY", d: "Chatbot « paranoïde » (Colby)." },
            { y: 1973, t: "Rapport Lighthill", d: "Coupe des financements IA au Royaume-Uni." },
            { y: 1979, t: "Stanford Cart", d: "Navigation autonome dans un environnement encombré." },
            { y: 1980, t: "XCON (DEC)", d: "Âge d’or des systèmes experts (production).", key: true },
            { y: 1982, t: "Réseaux de Hopfield", d: "Mémoire associative." },
            { y: 1986, t: "Rétropropagation", d: "Rumelhart, Hinton & Williams (popularisation).", key: true },
            { y: 1987, t: "2e hiver de l’IA", d: "Bulle systèmes experts / hardware coûteux (≈1987–93)." },
            { y: 1989, t: "LeCun (CNN)", d: "Lecture de chiffres manuscrits (ATM)." },
            { y: 1995, t: "SVM", d: "Cortes & Vapnik (marges maximales)." },
            { y: 1997, t: "Deep Blue", d: "Victoire sur Kasparov (échecs).", key: true },
            { y: 1998, t: "LeNet-5", d: "CNN consolidée pour la vision." },
            { y: 2006, t: "Relance du Deep Learning", d: "Hinton et les Deep Belief Nets.", key: true },
            { y: 2009, t: "ImageNet", d: "Jeu de données massif (Fei-Fei Li)." },
            { y: 2011, t: "IBM Watson", d: "Victoire à Jeopardy! (QA)." },
            { y: 2012, t: "AlexNet", d: "Révolution vision profonde (ImageNet).", key: true },
            { y: 2014, t: "GANs", d: "Goodfellow — début du génératif moderne.", key: true },
            { y: 2015, t: "DQN / ResNet", d: "Atari (DeepMind) ; réseaux très profonds." },
            { y: 2016, t: "AlphaGo", d: "Bats Lee Sedol au Go.", key: true },
            { y: 2017, t: "Transformers", d: "« Attention Is All You Need ». Base des LLM.", key: true },
            { y: 2018, t: "BERT", d: "NLP bidirectionnel (pré-entraînement)." },
            { y: 2019, t: "GPT-2 / AlphaStar", d: "Génération large échelle ; StarCraft II." },
            { y: 2020, t: "GPT-3 / AlphaFold2", d: "LLM & percée en biologie structurale.", key: true },
            { y: 2021, t: "CLIP / DALL·E", d: "Vision-langage et image générative." },
            { y: 2022, t: "Stable Diffusion / ChatGPT", d: "Image open & dialogue grand public.", key: true },
            { y: 2023, t: "GPT-4 / multimodal", d: "Montée des modèles texte+image+code." }
          ];

          // ---- Construction DOM ----
          const css = `#ai-timeline{--accent:${ACCENT}; --ink:#FFA500; --muted:#64748b; --bg:#fff; --chip:#fff3; font-family:system-ui,-apple-system,Segoe UI,Roboto,Arial; color:var(--ink)}
                       #ai-timeline .wrap{max-width:980px;margin:0 auto}
                       #ai-timeline .toolbar{display:flex;flex-wrap:wrap;gap:.6rem;align-items:center;margin-bottom:.8rem}
                       #ai-timeline .toolbar a{padding:.4rem .6rem;border-radius:.6rem;border:1px solid #e5e7eb;text-decoration:none;color:var(--ink);font-weight:600}
                       #ai-timeline .toolbar a:hover{background:#f8fafc}
                       #ai-timeline .search{flex:1;display:flex;gap:.5rem}
                       #ai-timeline input[type="search"]{flex:1;padding:.5rem .7rem;border:1px solid #e5e7eb;border-radius:.6rem}
                       #ai-timeline label.toggle{display:flex;gap:.4rem;align-items:center;font-size:.9rem;color:var(--muted)}
                       #ai-timeline details{border:1px solid #e5e7eb;border-radius:.8rem;margin:.6rem 0;background:var(--bg)}
                       #ai-timeline summary{list-style:none;cursor:pointer;padding:.7rem .9rem;font-weight:800;background:linear-gradient(90deg,#fff, #fff 55%, #fff0 100%)}
                       #ai-timeline summary::marker{display:none}
                       #ai-timeline .decade-title{color:var(--muted);font-weight:700;margin-left:.35rem}
                       #ai-timeline ul{margin:0;padding:.4rem .9rem .9rem 1.1rem}
                       #ai-timeline li{display:grid;grid-template-columns:92px 1fr;gap:.8rem;padding:.55rem .4rem;border-left:4px solid #e5e7eb;border-radius:.4rem}
                       #ai-timeline li:hover{background:#fafafa}
                       #ai-timeline .y{font-weight:800;color:var(--accent)}
                       #ai-timeline .t{font-weight:700}
                       #ai-timeline .d{color:var(--muted)}
                       #ai-timeline .key{display:inline-block;margin-left:.4rem;background:var(--accent);color:#fff;border-radius:.45rem;padding:.05rem .4rem;font-size:.75rem}
                       #ai-timeline .hit{background:#fff7ed}
                       #ai-timeline .count{font-size:.85rem;color:var(--muted);margin-left:.4rem}
                      `;
          const style = document.createElement("style"); style.textContent = css; document.head.appendChild(style);

          mount.innerHTML = `<div class="wrap">
                                <div class="toolbar">
                                  <nav class="nav"></nav>
                                    <div class="search">
                                      <input type="search" placeholder="Rechercher (ex. perceptron, 2012, Go…)" aria-label="Rechercher dans la frise"/>
                                      <label class="toggle"><input type="checkbox" id="onlyKey"/> Événements majeurs</label>
                                      <span class="count" aria-live="polite"></span>
                                    </div>
                                  </div>
                                <div class="panel"></div>
                              </div>
                            `;

          // Regroupement par décennie
          const byDecade = new Map();
          events.forEach(e => {
            const d = Math.floor(e.y / 10) * 10;
            if (!byDecade.has(d)) byDecade.set(d, []);
            byDecade.get(d).push(e);
          });
          [...byDecade.values()].forEach(arr => arr.sort((a, b) => a.y - b.y));

          // Navigation décennies
          const nav = mount.querySelector(".nav");
          [...byDecade.keys()].sort((a, b) => a - b).forEach(dec => {
            const a = document.createElement("a");
            a.href = `#d${dec}`;
            a.textContent = `${dec}s`;
            nav.appendChild(a);
          });

          const panel = mount.querySelector(".panel");
          function render(filterText = "", onlyKey = false) {
            panel.innerHTML = "";
            let shown = 0;
            [...byDecade.keys()].sort((a, b) => a - b).forEach(dec => {
              const list = byDecade.get(dec).filter(e => {
                const txt = (e.y + " " + e.t + " " + e.d).toLowerCase();
                const okTxt = !filterText || txt.includes(filterText.toLowerCase());
                const okKey = !onlyKey || e.key;
                return okTxt && okKey;
              });
              if (!list.length) return;

              const det = document.createElement("details");
              det.id = `d${dec}`;
              det.open = true;
              det.innerHTML = `<summary><span class="decade-title">${dec}s</span></summary>`;
              const ul = document.createElement("ul");

              list.forEach(e => {
                shown++;
                const li = document.createElement("li");
                if (filterText) li.classList.add("hit");
                li.id = `y${e.y}-${e.t.replace(/\W+/g, "-").toLowerCase()}`;
                li.innerHTML = `
          <div class="y">${e.y}</div>
          <div>
            <div class="t">${e.t}${e.key ? ` <span class="key">clé</span>` : ""}</div>
            <div class="d">${e.d}</div>
          </div>`;
                ul.appendChild(li);
              });

              det.appendChild(ul);
              panel.appendChild(det);
            });
          }

          // Recherche & filtre
          const search = mount.querySelector('input[type="search"]');
          const onlyKey = mount.querySelector('#onlyKey');
          const apply = () => render(search.value.trim(), onlyKey.checked);
          search.addEventListener('input', apply);
          onlyKey.addEventListener('change', apply);

          // Gestion ancre #yYYYY…
          window.addEventListener('hashchange', () => {
            const id = location.hash.slice(1);
            if (!id) return;
            const el = document.getElementById(id);
            if (el) {
              const details = el.closest('details'); if (details) details.open = true;
              el.scrollIntoView({ behavior: "smooth", block: "start" });
              el.classList.add('hit'); setTimeout(() => el.classList.remove('hit'), 1200);
            }
          });

          render();
          // Ouvre automatiquement la décennie de l’ancre si fournie au chargement
          if (location.hash) window.dispatchEvent(new Event('hashchange'));
        })();
      </script>
      <!-- === /Frise chronologique IA === -->

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