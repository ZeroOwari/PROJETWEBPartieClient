<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pagination des entreprises</title>
    <link rel="stylesheet" href="page_css.css">
</head>
<body class="body_Recruteur_offre">

<div class="container_recruteur_offre">
    <!-- Section Modifier Offre -->
    <div class="Offre_visible_modifier_offre_recruteur_offre">
        <div class="barre_horizontal_recruteur_offre"></div>
        <div class="barre_vertical_recruteur_offre"></div>
        <div class="offre_visible_Recruteur_offre">Offre Visible</div>
        <div class="Modifier_offre_Recruteur_offre">Modifier Offre</div>

        <form id="entrepriseFormModifier" method="post">
            <input type="text" id="modi_nom" name="modi_nom" class="input_intitule_mission_Recruteur_offre" placeholder="Nom de l'entreprise" required>
            <input type="text" id="modi_ville" name="modi_ville" class="input_localisation_Recruteur_offre" placeholder="Ville" required>
            <input type="text" id="modi_Competence" name="modi_Competence" class="input_Competence_Recruteur_offre" placeholder="Compétence" required>
            <input type="text" id="modi_Remuneration" name="modi_Remuneration" class="input_base_de_remuneration_Recruteur_offre" placeholder="Rémunération" required>
            <textarea id="modi_description" name="modi_description" class="input_textarea_description_Recruteur_offre" placeholder="Description"></textarea>
            <input type="date" id="modi_Debut_stage" name="modi_Debut_stage" class="input_debut_du_stage_Recruteur_offre" required>
            <input type="date" id="modi_Fin_stage" name="modi_Fin_stage" class="input_fin_du_stage_Recruteur_offre" required>
            <button type="button" id="modi_Supprimer" class="input_supprimer_offre_Recruteur_offre">Supprimer l'offre</button>
            <button type="submit" id="modi_Modifier" class="input_modifier_offre_Recruteur_offre">Modifier l'offre</button>
        </form>


            <div class="container_offre_visible_Recruteur_offre">
        <?php
        // Charger le contenu du JSON
        $json = file_get_contents("Entreprise.json");
        $entreprises = json_decode($json, true);

        if (!$entreprises) {
            die("Erreur : Impossible de charger les données.");
        }


        if (!empty($entreprises)) {
            echo "<div class='container'>";
            foreach ($entreprises as $entreprise) {

                $nom = htmlspecialchars($entreprise['nom']);
                $ville = htmlspecialchars($entreprise['ville']);
                $secteur = htmlspecialchars($entreprise['secteur']);
                $description = htmlspecialchars($entreprise['Description']);
                $Intitule_de_la_formation = htmlspecialchars($entreprise['Intitule_de_la_formation']);
                $chemin_d_acces_logo = htmlspecialchars($entreprise['chemin_d_acces_logo']);
                $competences = isset($entreprise['Competence']) ? $entreprise['Competence'] : [];
                $A_propos_de_l_entreprise = htmlspecialchars($entreprise['A_propos_de_l_entreprise']);
                $Objectif_du_poste = htmlspecialchars($entreprise['Objectif_du_poste']);



                // Affichage des informations de l'entreprise
                echo "<div>";
                echo "<div class='case_pour_annonce_Recruteur_offre'>";
                echo "<div class='Barre_verticale_case_pour_annonce_Recruteur_offre'></div>";
                echo "<div class='Barre_horizontale_case_pour_annonce_Recruteur_offre'></div>";
                echo "<div class='intitule_de_la_mission_Offre_visible_Recruteur_offre'>$Intitule_de_la_formation</div>";
                if (!empty($chemin_d_acces_logo)) {
                    echo "<p><img class='logo_de_l_entreprise_Recruteur_offre' src='$chemin_d_acces_logo' alt='Logo entreprise ' width='30'></p>";
                }
                echo "<div class='Localisation_entreprise_Recruteur_offre'>$ville</div>";
                echo "<p><img src='image/icon_map_ping.png' class='icon_localisation_Recruteur_offre' alt='Logo entreprise' width='15'></p>";


                echo "</div>";

                // Affichage des compétences sous forme de liste
                if (!empty($competences)) {
                    echo "<p><strong>Compétences :</strong></p><ul>";
                    foreach ($competences as $competence) {
                        echo "<li>" . htmlspecialchars($competence) . "</li>";
                    }
                    echo "</ul>";
                }

                // Affichage du logo si disponible


                echo "</div>"; // Fermeture de la div entreprise
            }
            echo "</div>"; // Fermeture du div container
        } else {
            echo "<p>Aucune entreprise trouvée.</p>";
        }
        ?>
        </div>
    </div>

    <!-- Section Ajouter Offre -->
    <div class="ajouter_une_offre">
        <div class="barre_horizontal_recruteur_offre"></div>
        <div class="titre_ajouter_une_offre">Ajouter Une Offre</div>

        <form id="entrepriseFormAjout" method="post">
            <input type="text" id="ajout_nom" name="ajout_nom" class="ajout_input_intitule_mission_Recruteur_offre" placeholder="Nom de l'entreprise" required>
            <input type="text" id="ajout_ville" name="ajout_ville" class="ajout_input_localisation_Recruteur_offre" placeholder="Ville" required>
            <input type="text" id="ajout_Competence" name="ajout_Competence" class="ajout_input_Competence_Recruteur_offre" placeholder="Compétence" required>
            <input type="text" id="ajout_Remuneration" name="ajout_Remuneration" class="ajout_input_base_de_remuneration_Recruteur_offre" placeholder="Rémunération" required>
            <textarea id="ajout_description" name="ajout_description" class="ajout_input_textarea_description_Recruteur_offre" placeholder="Description"></textarea>
            <input type="date" id="ajout_Debut_stage" name="ajout_Debut_stage" class="ajout_input_debut_du_stage_Recruteur_offre" required>
            <input type="date" id="ajout_Fin_stage" name="ajout_Fin_stage" class="ajout_input_fin_du_stage_Recruteur_offre" required>
            <button type="submit" id="ajout_Ajouter" class="ajout_input_offre_Recruteur_offre">Ajouter l'offre</button>
        </form>
    </div>
</div>




</body>
</html>


</body>
</html>
