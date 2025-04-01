<?php
session_start();
require_once __DIR__.'/../vendor/autoload.php';

// Ajoutez ceci juste après session_start()
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: ?page=accueil');
    exit;
}

$loader = new \Twig\Loader\FilesystemLoader(__DIR__.'/../Templates');
$twig = new \Twig\Environment($loader, [
    'cache' => false,
    'debug' => true // Ajout du mode debug pour Twig
]);

// Fonction asset optimisée pour votre structure
$twig->addFunction(new \Twig\TwigFunction('asset', function ($path) {
    // Assets spécifiques à Vue
    $vueAssets = [
        'style-web.css',
        'Avatar.png',
        'backgroundcompte.png',
        'flou.png',
        'flou2.png',
        'flou3.png',
        'logo.png',
        'logo2.png'
    ];

    if (in_array(basename($path), $vueAssets)) {
        return '/Vue/assets/' . ltrim($path, '/');
    }

    // Par défaut, utilise public/assets
    return '/public/assets/' . ltrim($path, '/');
}));

// Variable globale pour les URLs de base
$twig->addGlobal('base_url', '');

// TRAITEMENT CONNEXION
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $users = json_decode(file_get_contents(__DIR__.'/../public/assets/users.json'), true);

    if ($users) {
        foreach ($users['etudiants'] as $user) {
            if ($user['email'] === $_POST['email'] && $user['password'] === $_POST['password']) {
                $_SESSION['user'] = $user;
                header('Location: ?page=infos-compte');
                exit;
            }
        }
    }

    $_SESSION['error'] = 'Email ou mot de passe incorrect';
    header('Location: ?page=connexion-etu');
    exit;
}

// Gestion des pages
$allowedPages = ['accueil', 'connexion-etu', 'infos-compte'];
$page = $_GET['page'] ?? 'infos-compte';

// Redirection si non connecté
if (!isset($_SESSION['user']) && $page !== 'connexion-etu') {
    header('Location: ?page=connexion-etu');
    exit;
}

// Préparation du contexte
$context = [
    'current_page' => $page,
    'site_name' => 'P.A.I.J',
    'error' => $_SESSION['error'] ?? null,
    'user' => $_SESSION['user'] ?? null
];

unset($_SESSION['error']);

// Rendu sécurisé
try {
    $template = "pages/{$page}.html.twig";
    if (!$loader->exists($template)) {
        $template = "pages/404.html.twig";
    }
    echo $twig->render($template, $context);
} catch (Exception $e) {
    echo $twig->render("pages/404.html.twig", $context);
}