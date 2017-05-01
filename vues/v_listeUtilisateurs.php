<div id="contenu">
   <table class="listeLegere">
      <caption>Liste des Utilisateurs Enregistrés Dans la Base de données
      </caption>
      <tr>
         <th class="id">ID</th>
         <th class="login">Login</th>  
         <th class="nom">Nom</th>  
         <th class="prenom">prenom</th> 
         <th class="adresse">adresse</th>
         <th class="cp">cp</th>  
         <th class="dateEmbauche">dateEmbauche</th>  
         <th class="compte">compte</th> 
      </tr>
      
      <fieldset>
         <legend>Eléments forfaitisés</legend>
         
         <?php
         foreach ($lesutilisateurs as $unutilisateur) {
            $id = $unutilisateur['id'];
            $login = $unutilisateur['login'];
            $nom = $unutilisateur['nom'];
            $prenom = $unutilisateur['prenom'];
            $adresse = $unutilisateur['adresse'];
            $cp = $unutilisateur['cp'];
            $dateEmbauche = $unutilisateur['dateEmbauche'];
            $compte = $unutilisateur['compte'];
            ?>

            <tr>
               <td> <?php echo $id ?></td>
               <td> <?php echo $login ?></td>
               <td> <?php echo $nom ?></td>
               <td> <?php echo $prenom ?></td>
               <td> <?php echo $adresse ?></td>
               <td> <?php echo $cp ?></td>
               <td> <?php echo $dateEmbauche ?></td>
               <td> <?php echo $compte . '<br>' ?></td>
            </tr>
            <?php
         }
         ?>

   </table>
</div>