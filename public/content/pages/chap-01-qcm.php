<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>NoodleML - Framework IA en C++ et JavaScript</title>

    <!-- Meta SEO -->
    <meta name="description"
        content="NoodleML est un mini-framework C++/JavaScript pour apprendre les réseaux de neurones de manière pédagogique, concrète et ludique.">
    <meta name="keywords"
        content="NoodleML, C++, JavaScript, réseau de neurones, IA, Machine Learning, intelligence artificielle, apprentissage machine, BTS SIO, SLAM, perceptron, MLP, formation IA, pédagogie IA, Gap, 05000">
    <meta name="author" content="Sébastien Marchand">

    <!-- Responsive -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Open Graph pour réseaux sociaux -->
    <meta property="og:title" content="NoodleML - Framework IA en C++ et JavaScript">
    <meta property="og:description"
        content="Un outil concret et ludique pour comprendre les réseaux de neurones en classe. Basé sur C++ et pensé pour les étudiants.">
    <meta property="og:image" content="https://noodleml.com/resources/NoodleML-a-brain-made-of-noodle-with-logo.jpg">
    <meta property="og:url" content="https://noodleml.com/">
    <meta property="og:type" content="website">

    <!-- Twitter Card (facultatif) -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="NoodleML - Framework IA minimaliste pour l'enseignement">
    <meta name="twitter:description"
        content="Apprenez les réseaux de neurones avec un projet pédagogique fun, clair et concret.">
    <meta name="twitter:image" content="https://noodleml.com/resources/NoodleML-a-brain-made-of-noodle-with-logo.jpg">

    <!-- Feuille de style -->
    <link rel="stylesheet" href="../css/style-noodleml.css">
    <link rel="icon" href="../resources/NoodleML.png" type="image/png">

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

    <section id="qcm">

        <div class="container">
            <h2 class="gradient-text">QCM — Intelligence & neurones artificiels</h2>
            <p class="intro">
                Répondez aux questions ci-dessous, puis cliquez sur <strong>Valider</strong>. Vous pouvez aussi
                <em>Mélanger</em> l’ordre des questions/réponses et <em>Réinitialiser</em> vos choix.
            </p>

            <div class="toolbar">
                <button id="qcmShuffle" type="button" class="btn btn-secondary"
                    title="Mélanger l’ordre des questions et des réponses">🔀 Mélanger</button>
                <button id="qcmSubmit" type="button" class="btn btn-primary" title="Valider vos réponses">✅
                    Valider</button>
                <button id="qcmReset" type="button" class="btn btn-danger" title="Réinitialiser le QCM">↺
                    Réinitialiser</button>
            </div>

            <div id="qcmPanel" class="panel" aria-live="polite" aria-atomic="true"></div>
            <div id="qcmScore" class="score" role="status" aria-live="polite"></div>
        </div>

        <!-- Styles minimalistes et SCOPÉS au #qcm pour ne pas perturber style-noodleml.css -->
        <style>
            #qcm .container {
                max-width: 980px;
                margin: 0 auto;
                padding: 1.25rem;
            }

            #qcm .intro {
                opacity: .85;
                margin: .25rem 0 1rem 0;
            }

            #qcm .toolbar {
                display: flex;
                gap: .5rem;
                flex-wrap: wrap;
                margin: .75rem 0 1rem 0;
            }

            #qcm .btn {
                cursor: pointer;
                border-radius: 10px;
                border: 1px solid transparent;
                padding: .6rem .9rem;
                font-weight: 600
            }

            #qcm .btn-primary {
                background: var(--btn-primary, #2ecc71);
                color: var(--btn-primary-ink, #062b17);
            }

            #qcm .btn-secondary {
                background: var(--btn-secondary, #e5e7eb);
                color: var(--btn-secondary-ink, #111827);
            }

            #qcm .btn-danger {
                background: var(--btn-danger, #ef4444);
                color: #fff;
            }

            #qcm .btn:hover {
                filter: brightness(1.05);
            }

            #qcm .panel {
                border: 1px solid var(--panel-border, #e5e7eb);
                border-radius: 14px;
                padding: 1rem;
            }

            #qcm fieldset {
                border: 1px solid var(--fieldset-border, #e5e7eb);
                border-radius: 12px;
                padding: .9rem;
                margin: 0 0 .9rem 0;
            }

            #qcm legend {
                font-weight: 700;
                padding: 0 .25rem;
            }

            #qcm .answers {
                display: grid;
                gap: .5rem;
                margin-top: .5rem;
            }

            #qcm .ans {
                display: flex;
                gap: .6rem;
                align-items: flex-start;
                border: 1px solid var(--answer-border, #e5e7eb);
                border-radius: 10px;
                padding: .55rem .7rem;
            }

            #qcm .ans.good {
                outline: 2px solid rgba(34, 197, 94, .6);
            }

            #qcm .ans.bad {
                outline: 2px solid rgba(239, 68, 68, .6);
            }

            #qcm .expl {
                margin-top: .5rem;
                opacity: .8;
                border-left: 3px solid rgba(0, 0, 0, .12);
                padding-left: .6rem;
            }

            #qcm .status {
                display: inline-flex;
                align-items: center;
                gap: .4rem;
                border-radius: 999px;
                padding: .35rem .6rem;
                font-weight: 700;
                margin: .35rem 0;
            }

            #qcm .status.ok {
                background: rgba(34, 197, 94, .12);
                color: #10b981;
                border: 1px solid rgba(34, 197, 94, .35);
            }

            #qcm .status.ko {
                background: rgba(239, 68, 68, .12);
                color: #ef4444;
                border: 1px solid rgba(239, 68, 68, .35);
            }

            #qcm .status.warn {
                background: rgba(245, 158, 11, .12);
                color: #f59e0b;
                border: 1px solid rgba(245, 158, 11, .35);
            }

            #qcm .score {
                margin-top: 1rem;
                font-weight: 700;
            }

            /* Respect de ta charte : si style-noodleml.css définit des variables CSS (couleurs), on les utilise via var(). */
        </style>

        <script>
            (function () {
                "use strict";

                // Données QCM (issues de ton cours)
                const qcmData = {
                    category: { title: "Intelligence Artificielle - Introduction" },
                    mcq: {
                        title: "Compréhension de l'intelligence et des premiers neurones artificiels",
                        questions: [
                            {
                                label: "Selon Albert Einstein, qu’est-ce qui définit l’intelligence ?",
                                answers: [
                                    { label: "La capacité de raisonner logiquement.", valid: false },
                                    { label: "La capacité de s’adapter.", valid: true },
                                    { label: "La capacité de mémoriser.", valid: false }
                                ],
                                explanation: "Einstein insistait sur l’adaptation : l’intelligence consiste à ajuster son comportement face au changement."
                            },
                            {
                                label: "Dans l’exemple des spaghettis, quelle est l’approche d’un programme classique ?",
                                answers: [
                                    { label: "Choisir un spaghetti au hasard.", valid: false },
                                    { label: "Comparer méthodiquement chaque spaghetti.", valid: true },
                                    { label: "Secouer le paquet sur la table.", valid: false }
                                ],
                                explanation: "Un programme classique applique une procédure séquentielle : comparer un spaghetti à un autre jusqu’à trouver le plus long."
                            },
                            {
                                label: "Dans le même exemple, quelle stratégie adopte l’être humain ?",
                                answers: [
                                    { label: "Il suit une liste d’instructions rigides.", valid: false },
                                    { label: "Il secoue le paquet et observe le spaghetti qui dépasse.", valid: true },
                                    { label: "Il mémorise toutes les longueurs.", valid: false }
                                ],
                                explanation: "L’humain utilise une heuristique : un geste simple qui fait émerger directement le spaghetti le plus long."
                            },
                            {
                                label: "Comment appelle-t-on le raccourci pratique qu’utilise l’humain dans ce genre de situation ?",
                                answers: [
                                    { label: "Une heuristique.", valid: true },
                                    { label: "Un algorithme.", valid: false },
                                    { label: "Une règle absolue.", valid: false }
                                ],
                                explanation: "Une heuristique est une règle pratique, apprise par l’expérience, qui économise des étapes de calcul."
                            },
                            {
                                label: "Quel est le cycle de l’apprentissage décrit dans le cours ?",
                                answers: [
                                    { label: "Exemples → feedback → ajustements → meilleure stratégie.", valid: true },
                                    { label: "Observation → action → oubli.", valid: false },
                                    { label: "Théorie → pratique → validation.", valid: false }
                                ],
                                explanation: "L’apprentissage repose sur l’itération : on observe des exemples, on reçoit un retour, on ajuste, on s’améliore."
                            },
                            {
                                label: "Qui sont les auteurs de l’article fondateur de 1943 sur le neurone formel ?",
                                answers: [
                                    { label: "Warren McCulloch et Walter Pitts.", valid: true },
                                    { label: "Alan Turing et John McCarthy.", valid: false },
                                    { label: "Marvin Minsky et Arthur Samuel.", valid: false }
                                ],
                                explanation: "McCulloch & Pitts (1943) posent les bases d’un neurone logique binaire."
                            },
                            {
                                label: "Que décrit leur modèle de neurone artificiel ?",
                                answers: [
                                    { label: "Une unité qui additionne les signaux, compare à un seuil et s’active ou non.", valid: true },
                                    { label: "Une cellule biologique exacte.", valid: false },
                                    { label: "Une machine capable d’émotions.", valid: false }
                                ],
                                explanation: "Somme pondérée des entrées + comparaison à un seuil = sortie binaire ON/OFF."
                            },
                            {
                                label: "Quel nom donne-t-on à ce concept de neurone binaire ?",
                                answers: [
                                    { label: "Threshold Logic Unit (TLU).", valid: true },
                                    { label: "Perceptron.", valid: false },
                                    { label: "Machine de Turing.", valid: false }
                                ],
                                explanation: "La TLU est la « brique » logique à seuil, ancêtre direct des neurones artificiels ultérieurs."
                            },
                            {
                                label: "Quel scientifique formule en 1950 la question « Les machines peuvent-elles penser ? »",
                                answers: [
                                    { label: "Alan Turing.", valid: true },
                                    { label: "John McCarthy.", valid: false },
                                    { label: "Frank Rosenblatt.", valid: false }
                                ],
                                explanation: "Turing propose également un test opérationnel : le Test de Turing."
                            },
                            {
                                label: "Quel événement de 1956 baptise officiellement l’Intelligence Artificielle ?",
                                answers: [
                                    { label: "La conférence de Dartmouth.", valid: true },
                                    { label: "Le séminaire de Princeton.", valid: false },
                                    { label: "Le colloque de Cambridge.", valid: false }
                                ],
                                explanation: "La conférence de Dartmouth (1956) réunit les pionniers et cristallise le champ de l’IA."
                            }
                        ]
                    }
                };

                // Utilitaires
                const $ = (sel) => document.querySelector(sel);
                const shuffleInPlace = (arr) => { for (let i = arr.length - 1; i > 0; i--) { const j = Math.floor(Math.random() * (i + 1));[arr[i], arr[j]] = [arr[j], arr[i]]; } return arr; };
                const deepClone = (o) => JSON.parse(JSON.stringify(o));

                let working = deepClone(qcmData);

                function render() {
                    const host = $("#qcmPanel");
                    host.innerHTML = "";
                    const form = document.createElement("form");
                    form.setAttribute("novalidate", "novalidate");

                    working.mcq.questions.forEach((q, qi) => {
                        const fs = document.createElement("fieldset");
                        fs.id = `qcm_q${qi}`;

                        const legend = document.createElement("legend");
                        legend.textContent = `Q${qi + 1}. ${q.label}`;
                        fs.appendChild(legend);

                        const answers = document.createElement("div");
                        answers.className = "answers";

                        q.answers.forEach((a, ai) => {
                            const row = document.createElement("div");
                            row.className = "ans";
                            const id = `qcm_q${qi}_a${ai}`;

                            const input = document.createElement("input");
                            input.type = "radio";
                            input.name = `qcm_q${qi}`;
                            input.id = id;
                            input.value = String(ai);
                            input.dataset.valid = String(a.valid);

                            const label = document.createElement("label");
                            label.setAttribute("for", id);
                            label.textContent = a.label;

                            row.appendChild(input);
                            row.appendChild(label);
                            answers.appendChild(row);
                        });

                        fs.appendChild(answers);

                        const expl = document.createElement("div");
                        expl.id = `qcm_q${qi}_expl`;
                        expl.className = "expl";
                        expl.hidden = true;
                        expl.textContent = q.explanation || "—";
                        fs.appendChild(expl);

                        form.appendChild(fs);
                    });

                    host.appendChild(form);
                    $("#qcmScore").textContent = "";
                }

                function setStatus(fieldset, kind, text) {
                    const old = fieldset.querySelector(".status");
                    if (old) old.remove();
                    const badge = document.createElement("div");
                    badge.className = `status ${kind}`;
                    badge.textContent = text;
                    fieldset.querySelector("legend").insertAdjacentElement("afterend", badge);
                }

                function markCorrect(fs) {
                    fs.querySelectorAll(".ans").forEach(row => {
                        const input = row.querySelector("input[type=radio]");
                        row.classList.remove("good", "bad");
                        if (input.dataset.valid === "true") row.classList.add("good");
                    });
                }

                function grade() {
                    const form = $("#qcmPanel form");
                    let correct = 0;
                    const total = working.mcq.questions.length;

                    working.mcq.questions.forEach((q, qi) => {
                        const fs = document.getElementById(`qcm_q${qi}`);
                        const chosen = form.querySelector(`input[name="qcm_q${qi}"]:checked`);
                        const expl = document.getElementById(`qcm_q${qi}_expl`);
                        fs.querySelectorAll(".ans").forEach(r => r.classList.remove("good", "bad"));

                        if (!chosen) {
                            setStatus(fs, "warn", "Non répondu");
                            expl.hidden = false;
                            markCorrect(fs);
                            return;
                        }

                        if (chosen.dataset.valid === "true") {
                            correct++;
                            setStatus(fs, "ok", "Bonne réponse");
                            chosen.closest(".ans").classList.add("good");
                        } else {
                            setStatus(fs, "ko", "Mauvaise réponse");
                            chosen.closest(".ans").classList.add("bad");
                            markCorrect(fs);
                        }
                        expl.hidden = false;
                    });

                    const pct = Math.round((correct / total) * 100);
                    $("#qcmScore").innerHTML = `<span class="status ${pct >= 80 ? 'ok' : pct >= 50 ? 'warn' : 'ko'}">Score : ${correct}/${total} (${pct}%)</span>`;
                }

                function reset(keepOrder = true) {
                    if (!keepOrder) working = deepClone(qcmData);
                    render();
                }

                function shuffleAll() {
                    working = deepClone(qcmData);
                    shuffleInPlace(working.mcq.questions);
                    working.mcq.questions.forEach(q => shuffleInPlace(q.answers));
                    render();
                }

                // Boot
                render();
                $("#qcmSubmit").addEventListener("click", grade);
                $("#qcmReset").addEventListener("click", () => reset(true));
                $("#qcmShuffle").addEventListener("click", shuffleAll);
            })();
        </script>
    </section>



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