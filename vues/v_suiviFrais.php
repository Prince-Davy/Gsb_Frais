<h3>Détail de la fiche de frais : <?php echo $lUtilisateur['nom'] . " " . $lUtilisateur['prenom'] ?> - 
    <?php echo $moisASelectionner ?> </h3>

Etat: <?php echo $infoutilisateur['libEtat']; ?> depuis le <?php echo dateAnglaisVersFrancais($infoutilisateur['dateModif']); ?> <br />
Montant validé : <?php echo $infoutilisateur['montantValide']; ?>

<form method="POST"  action="index.php?uc=validerFrais&action=validerMajFraisForfait">
    <div class="corpsForm">
        <input type="hidden" name="idVis" value="<?php echo $idUtilisateur; ?>" />
        <input type="hidden" name="leMois" value="<?php echo $moisAng; ?>" />
        <table class="listeLegere">
            <caption>Eléments forfaitisés </caption>
            <tr>
                <?php
                foreach ($lesFraisForfait as $unFraisForfait) {
                    $libelle = $unFraisForfait['libelle'];
                    ?>	
                    <th> <?php echo $libelle ?></th>
                    <?php
                }
                ?>
            </tr>
            <tr>
                <?php
                foreach ($lesFraisForfait as $unFraisForfait) {
                    $quantite = $unFraisForfait['quantite'];
                    $sommeFF += $quantite;
                    ?>
                    <td class="qteForfait"><?php echo $quantite ?> </td>
                    <?php
                }
                ?>
            </tr>
        </table>
        <h3>TOTAL DES ELEMENTS FORFAITISES : <?php echo $sommeFF; ?></h3>
    </div>

</form> 

<div class="corpsForm"> 
    <input type="hidden" name="idVis" value="<?php echo $idUtilisateur; ?>" />
    <input type="hidden" name="leMois" value="<?php echo $moisAng; ?>" />
    <table class="listeLegere">
        <caption>Descriptif des éléments hors forfait
        </caption>
        <tr>
            <th class="date">Date</th>
            <th class="libelle">Libellé</th>  
            <th class="montant">Montant</th>    
        </tr>

<?php
if ($ok = FALSE) {
    ?> 
            <table><i><tr> Aucun frais hors forfait pour ce mois </tr></i></table>
        <?php
        } else {
            foreach ($lesFraisHorsForfait as $unFraisHorsForfait) {
                $libelle = $unFraisHorsForfait['libelle'];
                $date = $unFraisHorsForfait['date'];
                $montant = $unFraisHorsForfait['montant'];
                $sommeFHF += $montant;
                ?>

                <tr>
                    <td> <?php echo $date; ?></td>
                    <td><?php echo $libelle; ?></td>
                    <td><?php echo $montant; ?></td>

                </tr>

    <?php }
}
?></table> 		 
    <h3>TOTAL DES FRAIS HORS FORFAITS : <?php echo $sommeFHF; ?> </h3>  
</div> <br />

<div class="piedForm">
<?php if ($infoutilisateur['idEtat'] == "V") {
    ?>           
        <p><a href="index.php?uc=suiviFrais&action=paiementFiche&idUtilisateur=<?php echo $idUtilisateur; ?>&mois=<?php echo $moisAng; ?>" 
              title="Mettre en paiement la fiche de frais">Mise en paiement</a> <?php espace(); ?>
<?php } elseif ($infoutilisateur['idEtat'] == "VA") { ?>

            <a href="index.php?uc=suiviFrais&action=rembourserFiche&idUtilisateur=<?php echo $idUtilisateur; ?>&mois=<?php echo $moisAng; ?>" 
               title="Mettre la fiche de frais à l'état remboursé">Valider le paiement de cette fiche</a></p> 
<?php } else { ?>
        <center> <h3>Etat de la fiche : <?php echo $infoutilisateur['libEtat']; ?> </h3</center>
        <?php } ?>    
</div>

</div>  
