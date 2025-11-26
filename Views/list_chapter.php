<?php
session_start();
require_once '../Models/BDD.php';

use NoodleML\Models\BDD;

// Vérifier que l'utilisateur est connecté
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'eleve') {
    header('Location: login.php');
    exit();
}

$db = new BDD();
$user = $_SESSION['user'];

// Récupérer la classe de l'élève
$classe_id = $user['classe_id'];

if (!$classe_id) {
    echo "<p>Erreur: Vous n'êtes pas assigné à une classe.</p>";
    exit();
}

// Récupérer les chapitres disponibles pour cette classe
$chap_dispo = $db->getChapDispo($classe_id);

// Définir la liste des chapitres disponibles dans le système
$chapitersDisponibles = [
    1 => [
        'num' => 1,
        'titre' => 'Introduction',
        'description' => 'Introduction à l\'intelligence artificielle et aux réseaux de neurones',
        'url' => '../public/content/pages/chap-01-introduction.php'
    ],
    2 => [
        'num' => 2,
        'titre' => 'Qu\'est-ce que l\'intelligence artificielle ?',
        'description' => 'Comprendre les concepts fondamentaux de l\'IA',
        'url' => '../public/content/pages/chap-02-qu-est-ce-que-l-intelligence-artificielle.php'
    ],
    3 => [
        'num' => 3,
        'titre' => 'Le Perceptron',
        'description' => 'Étude du perceptron: le fondement des réseaux de neurones',
        'url' => '../public/content/pages/chap-03-le-perceptron.php'
    ]
];

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chapitres disponibles - NoodleML</title>
    <link rel="stylesheet" href="../public/content/css/style-noodleml.css">
    <style>
        .chapters-container {
            max-width: 1000px;
            margin: 40px auto;
            padding: 20px;
        }
        .chapter-card {
            background: white;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            padding: 20px;
            margin: 15px 0;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .chapter-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.15);
        }
        .chapter-card.locked {
            opacity: 0.6;
            background-color: #f5f5f5;
        }
        .chapter-number {
            display: inline-block;
            background-color: #007bff;
            color: white;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            text-align: center;
            line-height: 40px;
            font-weight: bold;
            margin-right: 15px;
        }
        .chapter-content {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .chapter-info {
            flex: 1;
        }
        .chapter-title {
            font-size: 1.2em;
            font-weight: bold;
            color: #333;
            margin: 0 0 5px 0;
        }
        .chapter-description {
            font-size: 0.95em;
            color: #666;
            margin: 0;
        }
        .chapter-button {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 0.9em;
            transition: background-color 0.2s;
        }
        .chapter-button:hover {
            background-color: #0056b3;
        }
        .chapter-button.locked {
            background-color: #ccc;
            cursor: not-allowed;
        }
        .chapter-button.locked:hover {
            background-color: #ccc;
        }
        .locked-badge {
            background-color: #dc3545;
            color: white;
            padding: 2px 8px;
            border-radius: 3px;
            font-size: 0.8em;
            margin-left: 10px;
        }
        .page-title {
            text-align: center;
            color: #333;
            margin-bottom: 10px;
        }
        .user-info {
            text-align: center;
            color: #666;
            margin-bottom: 30px;
            font-size: 0.95em;
        }
    </style>
</head>
<body>
    <div class="chapters-container">
        <h1 class="page-title">Chapitres disponibles</h1>
        <div class="user-info">
            <p>Bienvenue <strong><?php echo htmlspecialchars($user['prenom'] ?? 'Élève'); ?></strong> | 
            <a href="../Controllers/connection.php?action=logout">Déconnexion</a></p>
        </div>

        <?php
        // Afficher les chapitres disponibles
        if ($chap_dispo !== null && $chap_dispo > 0) {
            foreach ($chapitersDisponibles as $chap) {
                $isAvailable = $db->isChapDispo($classe_id, $chap['num']);
                $isLocked = !$isAvailable;
                
                echo '<div class="chapter-card ' . ($isLocked ? 'locked' : '') . '">';
                echo '<div class="chapter-content">';
                echo '<div>';
                echo '<span class="chapter-number">' . $chap['num'] . '</span>';
                echo '<div class="chapter-info">';
                echo '<p class="chapter-title">' . htmlspecialchars($chap['titre']);
                if ($isLocked) {
                    echo '<span class="locked-badge">🔒 Verrouillé</span>';
                }
                echo '</p>';
                echo '<p class="chapter-description">' . htmlspecialchars($chap['description']) . '</p>';
                echo '</div>';
                echo '</div>';
                echo '<a href="' . ($isAvailable ? htmlspecialchars($chap['url']) : '#') . '" class="chapter-button ' . ($isLocked ? 'locked' : '') . '" ' . ($isLocked ? 'disabled' : '') . '>';
                echo $isAvailable ? 'Accéder' : 'Verrouillé';
                echo '</a>';
                echo '</div>';
                echo '</div>';
            }
        } else {
            echo '<div style="text-align: center; padding: 40px; background: #f9f9f9; border-radius: 8px;">';
            echo '<p style="font-size: 1.1em; color: #666;">Aucun chapitre disponible pour le moment.</p>';
            echo '<p style="color: #999;">Votre professeur n\'a pas encore déverrouillé de chapitres.</p>';
            echo '</div>';
        }
        ?>
    </div>
</body>
</html>
