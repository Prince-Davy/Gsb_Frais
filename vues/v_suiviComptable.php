
<div id="contenu">
    <h2> Suivi des fiches de frais  </h2>
    <h3>Utilisateur et mois à sélectionner </h3>
    <form action="index.php?uc=suiviFrais&action=miseEnPaiement" method="POST">
        <div class="corpsForm">
            <p>
                <input id ="modifListeUtilisateur" type="hidden" name="modifListeUtilisateur" value="<?php echo "Non" ; ?>">
                <label for="lstUtilisateur">Utilisateur :</label>

                <select id ="lstutilisateur" name="lstutilisateur" onchange=" changeutilisateurOuValidation();
                        this.form.submit()">

                    <?php
                    // parcour du tableau associatif les utilisateurs
                    foreach ($lesutilisateurs as $unutilisateur) {
                        // récupération du nom dans la variable $nom
                        $nom = $unutilisateur['nom'];
                        // récupération du prenom dans la variable $prenom
                        $prenom = $unutilisateur['prenom'];
                        // récupération de l'id dans la variable $id
                        $id = $unutilisateur['id'];
                        //condition pour savoir si le utilisateur se trouvant à la position t est égale au utilisateur choisi
                        if ($nom . " " . $prenom === $lUtilisateur['nom'] . " " . $lUtilisateur['prenom']) {
                            ?>
                            <option selected value="<?php echo $id; ?>"><?php echo $nom . " " . $prenom; ?> </option>
                            <?php
                        } else {
                            ?> 
                            <option  value="<?php echo $id; ?>"><?php echo $nom . " " . $prenom; ?> </option>
                            <?php
                        }
                    }
                    ?>    
                </select>

            </p>
            <p>
                <label for="lstFrais" accesskey="n">Mois des fiches à valider : </label>
                <select id="lstMois" name="lstMois"> 

                    <?php
                    if ($lesMois != NULL) { // Boucle pour le parcour du tableau associatif $lesMois
                        foreach ($lesMois as $unMois) {
                            //récupération du mois au format aa/mmmm
                            $mois = $unMois['numMois'] . "/" . $unMois['numAnnee'];
                            $numAnnee = $unMois['numAnnee'];
                            $numMois = $unMois['numMois'];
                            // condition pour savoir si le mois à la position t est égale au mois choisi
                            if ($mois == $moisASelectionner) {
                                ?>
                                <option selected value="<?php echo $mois; ?>"><?php echo $numMois . "/" . $numAnnee; ?> </option>
                            <?php } else {
                                ?>
                                <option value="<?php echo $mois; ?>"><?php echo $numMois . "/" . $numAnnee; ?> </option>
                                <?php
                            }
                        }
                    }
                    ?>         
                </select>
            </p>          
        </div>
        <div class="piedForm">
            <p>
                <input id="ok" type="submit" value="Valider" size="20" />
            </p> 
        </div>
    </form>

</div>

