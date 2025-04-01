<?php
session_start();
require_once __DIR__.'/../vendor/autoload.php';

$loader = new \Twig\Loader\FilesystemLoader(__DIR__.'/../Templates');
$twig = new \Twig\Environment($loader, ['cache' => false]);

// Fonction asset
$twig->addFunction(new \Twig\TwigFunction('asset', function ($path) {
    return '/vue/assets/' . ltrim($path, '/');
}));

// TRAITEMENT CONNEXION
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $users = json_decode(file_get_contents(__DIR__.'/../vue/assets/data/users.json'), true);

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

// ROUTAGE
$page = $_GET['page'] ?? 'accueil';
$context = [
    'current_page' => $page,
    'site_name' => 'P.A.I.J',
    'error' => $_SESSION['error'] ?? null
];

// Nettoyage erreur
unset($_SESSION['error']);

// RENDU
echo $twig->render("pages/{$page}.html.twig", $context);