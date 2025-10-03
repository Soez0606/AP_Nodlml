<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">

  <!-- SEO classique -->
  <title>NoodleML - Chapitre 1 : Introduction au concept d’Intelligence Artificielle</title>
  <meta name="description"
    content="Voyagez à travers l'histoire de l'intelligence artificielle avec Udon, votre guide numérique. Illustrations, anecdotes et pédagogie au rendez-vous.">
  <meta name="keywords"
    content="NoodleML, IA, intelligence artificielle, histoire IA, Udon, apprentissage, pédagogie, réseau de neurones, BTS SIO, C++, JavaScript">
  <meta name="author" content="Sébastien Marchand">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- Balises META pour SEO et réseaux sociaux -->

  <!-- Open Graph pour Facebook et autres -->
  <meta property="og:title" content="NoodleML - Chapitre 1 : Introduction historique à l’IA">
  <meta property="og:description"
    content="Voyagez à travers l'histoire de l'intelligence artificielle avec Udon, votre guide numérique. Illustrations, anecdotes et pédagogie au rendez-vous.">
  <meta property="og:image" content="https://noodleml.com/resources/chap-01/cerveau-elec.jpg">
  <meta property="og:url" content="https://noodleml.com/chap-01-introduction.html">
  <meta property="og:type" content="article">
  <meta property="og:locale" content="fr_FR">
  <meta property="og:site_name" content="NoodleML">

  <!-- Twitter Card -->
  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:title" content="NoodleML - Chapitre 1 : Introduction historique à l’IA">
  <meta name="twitter:description"
    content="Découvrez les origines et l’évolution de l’IA avec une approche pédagogique originale signée Udon.">
  <meta name="twitter:image" content="https://noodleml.com/resources/chap-01/cerveau-elec.jpg">


  <!-- Feuille de style -->
  <link rel="stylesheet" href="style-noodleml.css">
  <link rel="stylesheet" href="./css/audio-player.css">
  <link rel="icon" href="./resources/NoodleML.png" type="image/png">


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
    <a href="https://noodleml.com" class="logo" style="text-decoration: none;">
      <div class="logo">
        <img src="./resources/noodle.png" alt="NoodleML">
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

      <h1 class="gradient-text">Chapitre 1 : Introduction au concept d’Intelligence Artificielle</h1>

      <div class="bandeau-image bordered">
        <img src="./resources/chap-01/cerveau-elec-frankenseb-bandeau.jpg" alt="cerveau electrifié frankenstein">
      </div>

      <!-- Bloc Udon avec image à droite -->
      <div class="bloc-texte-image gauche">

        <div class="texte">
          <p>Voilà un titre de premier chapitre un peu pompeux, n'est-t-il pas ? Et si je commençais par me présenter ?
            Appelez-moi Udon. D’où est-ce que je viens si ce n'est de l'imagination de mon créateur?
            Je vous donne un indice: pays du Soleil-Levant. Un clin d’œil se cache dans mon nom alors je vous laisse
            faire vos petites recherches.
          </p>

          <p>Mon créateur compte sur moi — vous l’aurez deviné, il préfère rester dans l'ombre. C’est donc à moi que
            revient la tâche de vous guider dans cet univers fascinant...
            Mais ne vous inquiétez pas. Je ne suis pas une nouille quand on parle de neurones.</p>

          <p>Préparez-vous à sortir des sentiers battus. On va flâner ensemble, au fil des idées, dans l’histoire
            trépidante de l’intelligence artificielle et de ses concepts fondateurs auxquels nous donnerons corps ici
            même grâce à <strong>NoodleML</strong>.
          </p>
        </div>

        <div class="image">
          <div class="audio-container" data-id="chap-01">
            🎧 <button class="playPauseBtn">▶️ Lire</button>
            <audio preload="metadata">
              <source src="./resources/chap-01/audio.mp3" type="audio/mpeg">
              Votre navigateur ne supporte pas l'élément audio HTML5.
            </audio>

            <input type="range" class="progress" value="0" min="0" step="0.1">
            <span class="time">0:00 / 0:00</span>
          </div>

          <img src="./resources/udon/heureux-mini-gauche.png" alt="Udon heureux">
        </div>

      </div>

      <!-- Bloc texte + image à droite -->
      <div class="bloc-texte-image droite">
        <div class="texte">
          <h2 class="gradient-text">Le futur, aujourd’hui bien présent… et avec un sacré passé !</h2>

          <p><strong>IA</strong>. Deux petites lettres qui font frissonner certains, briller les yeux des autres... et
            parfois les deux à la fois. Pourquoi ? Parce que l’Intelligence Artificielle — oui, vous pouvez dire “IA”,
            on est entre nous — alimente notre imaginaire depuis bien longtemps. Bien avant qu’elle ne débarque dans nos
            smartphones ou nos voitures, elle hantait déjà les pages des romans de science-fiction.</p>

          <p>Des auteurs comme <strong>Arthur C. Clarke</strong>, <strong>Robert A. Heinlein</strong>, ou bien sûr
            <strong>Isaac Asimov</strong> (le papa des célèbres lois de la robotique), ont nourri nos rêves — ou nos
            cauchemars — d’un avenir peuplé de machines pensantes. Un peu comme <strong>Jules Verne</strong> en son
            temps, ils ont vu loin. Très loin.
          </p>

          <p>Et aujourd’hui ? Eh bien… on y est. Reconnaissance faciale, vocale, analyse comportementale, exosquelettes
            intelligents, drones autonomes, IA médicales, assistants de rédaction... Bref, l’Intelligence Artificielle
            s’est glissée dans nos vies. Elle est dans nos maisons, nos voitures, nos poches. Elle apprend, elle
            conseille, elle anticipe.
            Elle ne dort (presque) jamais.
          </p>

          <p>L'<strong>intelligence artificielle</strong>” n’est plus de la science-fiction. C’est une réalité
            industrielle, stratégique, et même politique. Mais derrière ce tourbillon de promesses, comment ça marche
            réellement côté logiciel?
          </p>

          <p><em>Spoiler</em> : on va décortiquer tout ça ensemble. Et promis, on évite le charabia… sauf si vous
            insistez!
            <span aria-label="sourire" role="img">😄</span>
          </p>

        </div>
        <div class="image">
          <img class="bordered_rounded" src="./resources/chap-01/robotique-2.jpg" alt="Illustration robotique IA">
        </div>
      </div>

      <!-- Bloc texte + image à gauche -->
      <div class="bloc-texte-image gauche">
        <div class="texte">

          <h2 class="gradient-text">Quand les humains rêvaient de machines qui pensent</h2>

          <p>De l’imaginaire à la réalité… faisons un petit détour ensemble par l’Histoire.</p>

          <p>Bien avant que les ordinateurs ne voient le jour, le mot <strong>robot</strong> apparaît pour la première
            fois en 1920, dans une pièce de théâtre de science-fiction signée <strong>Karel Čapek</strong>, intitulée
            <em>R.U.R.</em> (*Rossum’s Universal Robots*). Inspiré du mot tchèque <em>“robota”</em> — qui signifie
            “travail” ou “corvée” — le terme évoque déjà une vision futuriste : celle d’êtres artificiels, construits à
            l’image de l’humain, mais conçus pour obéir, produire, servir.
          </p>

          <p>À l’époque, on parlait plutôt d’<strong>automates</strong> : des machines mécaniques capables de reproduire
            une séquence d’actions prédéfinies, parfois légèrement adaptables grâce à quelques capteurs. Mais il leur
            manquait encore une chose essentielle : un <strong>esprit</strong>.</p>

          <p>C’est cette <strong>absence de “cerveau”</strong> qui va donner naissance à l’informatique, portée par les
            avancées extraordinaires du XXᵉ siècle. Dès les années 1940, des figures visionnaires comme <strong>Alan
              Turing</strong> ou <strong>Konrad Zuse</strong> imaginent une nouvelle génération de machines : des
            machines <strong>programmables</strong>, capables d’exécuter n’importe quel algorithme. L’ordinateur allait
            naître.</p>

          <p>À partir de là, une nouvelle ère s’ouvre : celle où la machine n’est plus seulement un outil mécanique,
            mais un système capable de <strong>raisonner</strong>, <strong>traiter des données</strong>, voire
            <strong>apprendre</strong>. Le terrain est prêt pour l’émergence de l’<strong>intelligence
              artificielle</strong>.
          </p>

        </div>
        <div class="image">
          <img src="./resources/chap-01/rur.jpg" alt="Rossum’s Universal Robots">
        </div>
      </div>

      <h2 class="gradient-text">Des IAs omniprésentes aujourd’hui</h2>

      <div class="texte">
        <p>Plus besoin d’aller chercher très loin pour croiser une intelligence artificielle : elle sont
          partout. Dans nos poches, nos maisons, nos voitures… et même dans les lignes de ce texte ou dans les images
          de style Manga qui agrémente votre lecture.</p>

        <p>Des suggestions de films sur une plateforme de streaming à la détection de visages sur une caméra de
          sécurité, des assistants vocaux qui répondent à vos moindres demandes aux IA médicales qui aident à poser un
          diagnostic… l’intelligence artificielle s’est glissée dans les rouages invisibles de notre quotidien.
        </p>

        <p>Parmi les plus récentes, les <strong>IA génératives</strong> ont ouvert une nouvelle ère : elles ne se
          contentent plus d’analyser ou de prédire… elles <em>créent</em>. Du texte, des images, de la musique — voire
          des idées.
        </p>

        <p>On l’oublie parfois, mais derrière chaque “petite magie” numérique, il y a souvent une machine qui apprend,
          compare, décide. Doucement mais sûrement, les IA sont devenues des partenaires silencieux de nos vies
          connectées.</p>

        <p>Et pourtant… ce n’est que le début. Alors, faites comme moi : installez-vous confortablement… Les choses
          sérieuses vont commencer.</p>
      </div>

      <div >
        <img class="image-centree" src="./resources/udon/cocktail-mini.png" alt="Illustration robotique IA">
      </div>

    </section>

    <!-- Lien vers le chapitre précédent et suivant -->
    <div class="navigation-links">
      <a href="chap-02-qu-est-ce-que-l-intelligence-artificielle.html" class="next">
        Chapitre suivant >>
      </a>
    </div>

    <hr>
  </main>

  <!-- FOOTER -->
  <footer>
    <div class="footer-info">
      <img src="./resources/bts-sio-gap.png" alt="">
      <div class="ml-text">Lycée Dominique Villars, Gap, 05000<br></div>
      <hr>
      <div class="gradient-text">© 2025</div>
    </div>
  </footer>

</body>

</html>