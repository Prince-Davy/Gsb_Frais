<div id="page">
   <div id="contenu">
      <div class="corpsForm">
         <fieldset>

            <table class="listeLegere">
               <tr>
                  <th class="id">ID</th>
                  <th class="login">Login</th>  
                  <th class="nom">Nom</th>  
                  <th class="prenom">Prenom</th> 
                  <th class="adresse">Adresse</th>
                  <th class="cp">Cp</th>  
                  <th class="dateEmbauche">DateEmbauche</th>  
                  <th class="compte">Compte</th>
                  <th class="button">Bouton</th> 
               </tr>

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
                     <td> <?php echo $compte ?></td>
                     <td>
                        <a href="index.php?uc=utilisateur&action=modifier&id=<?php echo $id ?>" 
                           onclick="return confirm('Voulez-vous vraiment supprimer cet Utilisateur?');">Supprimer</a>
                     </td>

                  </tr>
                  <?php
               }
               ?>
            </table>
         </fieldset>
      </div>
   </div>
</div>