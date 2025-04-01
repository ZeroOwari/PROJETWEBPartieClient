<?php
require_once __DIR__.'/../vendor/autoload.php';

$loader = new \Twig\Loader\FilesystemLoader([
    __DIR__.'/../Templates',
    __DIR__.'/../vue' // Pour accéder aux assets depuis Twig si besoin
]);

$twig = new \Twig\Environment($loader, [
    'cache' => false,
    'debug' => true
]);

// Fonction asset() corrigée pour votre architecture
$twig->addFunction(new \Twig\TwigFunction('asset', function ($path) {
    // Chemin relatif depuis la racine du site
    return '/vue/assets/' . ltrim($path, '/');
}));

// Routeur
$page = $_GET['page'] ?? 'accueil';
$context = [
    'current_page' => $page,
    'site_name' => 'P.A.I.J'
];

try {
    echo $twig->render("pages/{$page}.html.twig", $context);
} catch (\Twig\Error\LoaderError $e) {
    header("HTTP/1.0 404 Not Found");
    echo $twig->render("pages/404.html.twig", $context);
}