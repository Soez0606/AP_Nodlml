<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Se connecter – NoodleML</title>

    <!-- Meta SEO -->
    <meta name="description"
        content="Découvrez l’approche pédagogique et didactique de NoodleML, en lien avec le BTS SIO SLAM. Un framework IA clair, visuel et accessible.">
    <meta name="keywords"
        content="pédagogie, didactique, BTS SIO, SLAM, IA, intelligence artificielle, enseignement, accessibilité, voix synthèse, NoodleML, framework, éducation">
    <meta name="author" content="Sébastien Marchand">

    <!-- Responsive -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Open Graph pour réseaux sociaux -->
    <meta property="og:title" content="NoodleML - Approche pédagogique et didactique">
    <meta property="og:description"
        content="Découvrez l’approche pédagogique et didactique de NoodleML, en lien avec le BTS SIO SLAM. Un framework IA clair, visuel et accessible.">
    <meta property="og:image" content="https://noodleml.com/resources/classe-ia.jpg">
    <meta property="og:url" content="https://noodleml.com/approche-pedagogique-et-didactique.html">
    <meta property="og:type" content="website">

    <!-- Twitter Card (facultatif) -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="NoodleML - Approche pédagogique et didactique">
    <meta name="twitter:description"
        content="Découvrez l’approche pédagogique et didactique de NoodleML, en lien avec le BTS SIO SLAM. Un framework IA clair, visuel et accessible.">
    <meta name="twitter:image" content="https://noodleml.com/resources/classe-ia.jpg">

    <!-- Feuille de style -->
    <!-- <link rel="stylesheet" href="content/css/style-noodleml.css"> -->
    <link rel="icon" href="content/resources/NoodleML.png" type="image/png">
    <link rel="stylesheet" href="content/css/styles-dashboard.css">
</head>

<body>
    <div class="login-container">
        <h1>Connexion</h1>

        <!-- Formulaire de connexion -->
        <form id="loginForm" action="validate.php" method="POST" class="login-form">
            <div>
                <label for="email">Adresse e-mail</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div>
                <label for="password">Mot de passe</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="forgot-password">
                <a href="#" id="forgotPasswordLink">Mot de passe oublié ?</a>
            </div>
            <div>
                <button type="submit">Se connecter</button>
            </div>
        </form>

        <!-- Formulaire de réinitialisation du mot de passe (caché par défaut) -->
        <form id="resetPasswordForm" class="login-form" style="display: none;">
            <h2>Réinitialiser le mot de passe</h2>
            <p>Entrez votre adresse e-mail pour recevoir un lien de réinitialisation.</p>
            <div>
                <label for="resetEmail">Adresse e-mail</label>
                <input type="email" id="resetEmail" name="resetEmail" required>
            </div>
            <div>
                <button type="submit">Envoyer le lien</button>
            </div>
            <div class="back-to-login">
                <a href="#" id="backToLoginLink">Retour à la connexion</a>
            </div>
        </form>
        <button><a href="/">Retour</a></button>
    </div>

    <script>
        // Gestion de l'affichage des formulaires
        document.getElementById('forgotPasswordLink').addEventListener('click', function (e) {
            e.preventDefault();
            document.getElementById('loginForm').style.display = 'none';
            document.getElementById('resetPasswordForm').style.display = 'block';
        });

        document.getElementById('backToLoginLink').addEventListener('click', function (e) {
            e.preventDefault();
            document.getElementById('resetPasswordForm').style.display = 'none';
            document.getElementById('loginForm').style.display = 'block';
        });
    </script>
</body>

</html>