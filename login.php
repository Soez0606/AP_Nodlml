<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <link rel="stylesheet" href="css/style_login.css">
</head>

<body>
    <div class="login-container">
        <h1>Connexion</h1>

        <!-- Formulaire de connexion -->
        <form id="loginForm" class="login-form">
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