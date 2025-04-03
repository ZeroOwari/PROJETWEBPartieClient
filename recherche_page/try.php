<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pagination des entreprises</title>
    <link rel="stylesheet" href="page_css.css">
</head>
<body>


<?php

$entreprises = [
    "Entreprise 1", "Entreprise 2", "Entreprise 3", "Entreprise 4", "Entreprise 5",
    "Entreprise 6", "Entreprise 7", "Entreprise 8", "Entreprise 9", "Entreprise 10",
    "Entreprise 11", "Entreprise 12", "Entreprise 13", "Entreprise 14", "Entreprise 15",
    "Entreprise 16", "Entreprise 17", "Entreprise 18", "Entreprise 19", "Entreprise 20",
    "Entreprise 21", "Entreprise 22", "Entreprise 23", "Entreprise 24", "Entreprise 25"
];

$limit = 6;


$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;



$paginated_entreprises = array_slice($entreprises, $offset, $limit);


if (!empty($paginated_entreprises)) {
    echo "<div class='container'>";
    foreach ($paginated_entreprises as $entreprise) {
        echo "<div class='Base-page_de_recherche'>";
        echo "<div class='Nom_de_l_annonce_page_de_recherche'>$entreprise</div>";

        // les logos pour tous sauf le coeur car ça va etre du javascript (voir tuto sur discord)

        echo "<div class='icon_avatar'><img width='30' src='image/icon_avatar.png' alt='Icone utilisateur'></div>";
        echo "<div class='icon_localisation'><img width='15' src='image/icon_map_ping.png' alt='Icone Localisation'></div>";
        echo "<div class='icon_malette'><img width='15' src='image/icon_malette.png' alt='Icone Malette'></div>";
        echo "<div class='icon_download'><img width='18' src='image/icon_download.png' alt='Icone Download'></div>";
        echo "<div class='icon_partager'><img width='11' src='image/icon_partager.png' alt='Icone Partager'></div>";
        echo "<div class='carre_description_page_de_recherche'></div>";
        echo "<div class='carre_localisation_page_de_recherche'></div>";
        echo "<div class='carre_nom_de_lentreprise_page_de_recherche'></div>";
        echo "<button class='btn' onclick='ouvrirPopup(\"popup_$entreprise\")'></button>";



        echo "<div id='popup_$entreprise' class='modal'>";
        echo "<div class='modal-content'>";
        echo "<div class='carre_noir'>$entreprise</div>";
        echo "<div class='modal-footer'></div>";

        echo "<span class='close' onclick='fermerPopup(\"popup_$entreprise\")'>&times;</span>";

        echo "</div>";
        echo "</div>";








        echo "<div class='panneau'>";
        echo "<div class='like'></div>";
        echo "</div>";

        echo "<div class='notifications'></div>";


        echo "</div>";
    }
    echo "</div>";
} else {
    echo "<p>Aucune entreprise à afficher.</p>";
}



$total_entrepises = count($entreprises);
$total_pages = ceil($total_entrepises / $limit);


echo "<div class='pagination'>";

if ($page > 1) {
    echo "<a href='?page=" . ($page - 1) . "'>Précédent</a>";
}

if ($page < $total_pages) {
    echo "<a href='?page=" . ($page + 1) . "'>Suivant</a>";
}
echo "</div>";
?>

<script src="page_de_recherche_jvsc.js"></script>
</body>
</html>
