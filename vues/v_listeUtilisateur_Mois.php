<div id="contenu">
    <?php if ($page == "Validation") { ?>
        <h2> Valider les fiches de frais  </h2>
    <?php } else { ?>
        <h2> Suivi du paiement des fiches de frais  </h2>
    <?php } ?>

    <h3>utilisateur et mois à sélectionner </h3>

    <?php if ($page == "Validation") { ?>
        <form action="index.php?uc=validerFrais&action=validerFrais" method="POST" >
        <?php }?>
           
        <?php if ($page == "Suivi") { ?>
            <form action="index.php?uc=suiviFrais&action=miseEnPaiement" method="POST">        
            <?php } ?>  
            <div class="corpsForm">
                <p>
                    <input id ="modifListeUtilisateur" type="hidden" name="modifListeUtilisateur" value="<?php echo "Non"; ?>">
                    <label for="lstutilisateur">utilisateur :</label>

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
                    <label for="lstMois" accesskey="n">Mois : </label>
                    <select id="lstMois" name="lstMois"> 

                        <?php
                        if ($lesMois != NULL) {
                            foreach ($lesMois as $unMois) {
                                $mois = $unMois['numMois'] . "/" . $unMois['numAnnee'];
                                $numAnnee = $unMois['numAnnee'];
                                $numMois = $unMois['numMois'];

                                if ($mois === $moisASelectionner) {
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
                    <input id="valider" type="submit" value="Valider" size="20" name="valid" type="hidden"/>
                </p> 
            </div>
        </form>



