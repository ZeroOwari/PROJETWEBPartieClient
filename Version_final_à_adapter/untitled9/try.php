    <!DOCTYPE html>
    <html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Pagination des entreprises</title>
        <link rel="stylesheet" href="page_css.css">
    </head>
    <body class="body_Page_de_recherche">
    <?php

    $limit = 6; // Nombre d'entreprises par page
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $offset = ($page - 1) * $limit;

    $json = file_get_contents("Entreprise.json");
    $entreprises = json_decode($json, true);

    // Vérifier si le JSON est valide
    if (json_last_error() !== JSON_ERROR_NONE) {
        die("Erreur JSON : " . json_last_error_msg());
    }

    // Découper les entreprises pour la pagination
    $paginated_entreprises = array_slice($entreprises, $offset, $limit);

    // Vérifier s'il y a des entreprises à afficher
    if (!empty($paginated_entreprises)) {
        echo "<div class='container'>";
        foreach ($paginated_entreprises as $entreprise) {
            // Sécuriser les variables contre XSS
            $nom = htmlspecialchars($entreprise['nom']);
            $ville = htmlspecialchars($entreprise['ville']);
            $secteur = htmlspecialchars($entreprise['secteur']);
            $description = htmlspecialchars($entreprise['Description']);
            $Intitule_de_la_formation = htmlspecialchars($entreprise['Intitule_de_la_formation']);
            $chemin_d_acces_logo = htmlspecialchars($entreprise['chemin_d_acces_logo']);
            $Competence = $entreprise['Competence'];
            $A_propos_de_l_entreprise=htmlspecialchars($entreprise['A_propos_de_l_entreprise']);
            $Objectif_du_poste=htmlspecialchars($entreprise['Objectif_du_poste']);

            // Créer un ID unique pour le popup
            $entreprise_id = str_replace(' ', '_', $nom);

            echo "<div class='Base-page_de_recherche'>";
            echo "<div class='Nom_de_l_annonce_page_de_recherche'>$Intitule_de_la_formation</div>";

            echo "<div class='icon_avatar'><img width='30' src='image/icon_avatar.png' alt='Icone utilisateur'></div>";
            echo "<div class='icon_localisation'><img width='15' src='image/icon_map_ping.png' alt='Icone Localisation'></div>";
            echo "<div class='icon_malette'><img width='15' src='image/icon_malette.png' alt='Icone Malette'></div>";
            echo "<div class='icon_download'><img width='18' src='image/icon_download.png' alt='Icone Download'></div>";
            echo "<div class='icon_partager'><img width='11' src='image/icon_partager.png' alt='Icone Partager'></div>";

            echo "<div class='carre_description_page_de_recherche'>$description</div>";
            echo "<div class='carre_localisation_page_de_recherche'>$ville</div>";
            echo "<div class='carre_nom_de_lentreprise_page_de_recherche'>$nom</div>";
            echo "<button class='btn' onclick='ouvrirPopup(\"popup_$entreprise_id\")'></button>";

            // Popup de l'entreprise avec compétences
            echo "<div id='popup_$entreprise_id' class='modal'>";
            echo "<div class='modal-content'>";
            echo "<div>";
            echo "<div class='carre_noir'>$Intitule_de_la_formation</div>";

            echo "<div class='logo_de_l_entreprise_popup'><img width='150' src='$chemin_d_acces_logo'></div>";
            echo "<div class='nom_de_l_entreprise_popup'>$nom :</div>";
            echo "<div class='ville_popup'>$ville</div>";
            echo "<div class='Qualification_popup'>Qualification :</div>";

            $topPosition = 350;
            foreach ($Competence as $competence) {
                echo "<div class='competence_popup' style='top: " . $topPosition . "px;'>" . htmlspecialchars($competence) . "</div>";
                $topPosition += 50;
            }

            echo "<div class='titre_a_propos_de_l_entreprise_popup'>A Propos De l'Entreprise</div>";
            echo "<div class='texte_a_propos_de_l_entreprise_popup'>$A_propos_de_l_entreprise</div>";
            echo "<div class='titre_objectif_du_post_popup'>Objectif du poste :</div>";
            echo "<div class='texte_objectif_du_post_popup'>$Objectif_du_poste</div>";

            echo "</div>";
            echo "<div class='modal-footer'></div>";
            echo "<span class='close' onclick='fermerPopup(\"popup_$entreprise_id\")'>&times;</span>";
            echo "</div>";
            echo "</div>";

            echo "<div class='panneau'>";
            echo "<div class='like'></div>";
            echo "</div>";

            echo "<div class='notifications'></div>";

            echo "</div>"; // Fin .Base-page_de_recherche
        }
        echo "</div>"; // Fin .container
    } else {
        echo "<p>Aucune entreprise à afficher.</p>";
    }

    // PAGINATION
    $total_entreprises = count($entreprises);
    $total_pages = ceil($total_entreprises / $limit);

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




