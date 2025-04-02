<?php
session_start();
require_once __DIR__.'/../vendor/autoload.php';

$loader = new \Twig\Loader\FilesystemLoader(__DIR__.'/../Templates');
$twig = new \Twig\Environment($loader, ['cache' => false]);

// Fonction asset
$twig->addFunction(new \Twig\TwigFunction('asset', function ($path) {
    $vueAssets = ['style-web.css', 'logo.png', 'backgroundcompte.png', 'Avatar.png'];
    if (in_array(basename($path), $vueAssets)) {
        return '/Vue/assets/' . ltrim($path, '/');
    }
    return '/public/assets/' . ltrim($path, '/');
}));

$twig->addGlobal('base_url', '');

// Gestion de la déconnexion
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: ?page=accueil');
    exit;
}

// Traitement de la connexion
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

// Pages autorisées
$publicPages = ['accueil', 'connexion-etu', 'a-propos', 'connexion-pil', 'connexion-adm'];
$privatePages = ['infos-compte', 'recherche', 'espace-tuteur'];
$allPages = array_merge($publicPages, $privatePages, ['404']);

// Gestion de la page demandée
$page = $_GET['page'] ?? 'accueil';

// Vérification de l'accès
if (!in_array($page, $allPages)) {
    $page = '404';
} elseif (in_array($page, $privatePages) && !isset($_SESSION['user'])) {
    header('Location: ?page=connexion-etu');
    exit;
} elseif ($page === 'connexion-etu' && isset($_SESSION['user'])) {
    header('Location: ?page=accueil');
    exit;
}

// Contexte
$context = [
    'current_page' => $page,
    'site_name' => 'P.A.I.J',
    'error' => $_SESSION['error'] ?? null,
    'user' => $_SESSION['user'] ?? null
];

unset($_SESSION['error']);

// Rendu
try {
    echo $twig->render("pages/{$page}.html.twig", $context);
} catch (\Twig\Error\LoaderError $e) {
    echo $twig->render("pages/404.html.twig", $context);
}