<?php
require_once __DIR__.'/../vendor/autoload.php';

$loader = new \Twig\Loader\FilesystemLoader(__DIR__.'/../templates');
$twig = new \Twig\Environment($loader, [
    'cache' => false // Désactivé en dev
]);

// Routeur basique
$page = $_GET['page'] ?? 'accueil';

// Données communes à toutes les pages
$context = [
    'current_page' => $page,
    'site_name' => 'P.A.I.J'
];

// Rend la page demandée

try {
    echo $twig->render("pages/{$page}.html.twig", $context);
} catch (\Twig\Error\LoaderError $e) {
    header("HTTP/1.0 404 Not Found");
    if ($twig->getLoader()->exists("pages/404.html.twig")) {
        echo $twig->render("pages/404.html.twig", $context);
    } else {
        die("Erreur 404 - Page non trouvée");
    }
}

// Ajoute ceci dans le contexte Twig
$context = [
    'current_page' => $page,
    'site_name' => 'P.A.I.J',
    'assets_version' => '1.0.0' // Change à chaque mise à jour CSS/JS
];