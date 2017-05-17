<div id="contenu">
   <table class="listeLegere">
      <caption>Liste des Utilisateurs Enregistrés Dans la Base de données
      </caption>
      <tr>
         <th class="id">ID</th>
         <th class="nom">Nom</th>  
         <th class="prenom">Prenom</th> 
         <th class="login">Login</th>  
         <th class="adresse">Adresse</th>
         <th class="cp">Cp</th>  
         <th class="ville">Ville</th>  
         <th class="dateEmbauche">DateEmbauche</th>  
         <th class="compte">Compte</th> 
      </tr>

      <?php
      foreach ($lesutilisateurs as $unutilisateur) {
         $id = $unutilisateur['id'];
         $login = $unutilisateur['login'];
         $nom = $unutilisateur['nom'];
         $prenom = $unutilisateur['prenom'];
         $adresse = $unutilisateur['adresse'];
         $cp = $unutilisateur['cp'];
         $ville = $unutilisateur['ville'];
         $dateEmbauche = $unutilisateur['dateEmbauche'];
         $compte = $unutilisateur['compte'];
         ?>

         <tr>
            <td> <?php echo $id ?></td>
            <td> <?php echo $nom ?></td>
            <td> <?php echo $prenom ?></td>
            <td> <?php echo $login ?></td>
            <td> <?php echo $adresse ?></td>
            <td> <?php echo $cp ?></td>
            <td> <?php echo $ville ?></td>
            <td> <?php echo $dateEmbauche ?></td>
            <td> <?php echo $compte . '<br>' ?></td>
         </tr>
         <?php
      }
      ?>

   </table>
</div>