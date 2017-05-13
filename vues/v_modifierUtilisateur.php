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
                     <td> <?php echo '<input type="text" value="'.$id.'"/>';?></td>
                     <td> <?php echo '<input type="text" value="'.$login.'"/>';?></td>
                     <td> <?php echo '<input type="text" value="'.$nom.'"/>';?></td>
                     <td> <?php echo '<input type="text" value="'.$prenom.'"/>';?></td>
                     <td> <?php echo '<input type="text" value="'.$adresse.'"/>';?></td>
                     <td> <?php echo '<input type="text" value="'.$cp.'"/>';?></td>
                     <td> <?php echo '<input type="text" value="'.$dateEmbauche.'"/>';?></td>
                     <td> <?php echo '<select>
                          <option value="visiteur" selected="selected">Visiteur</option>
                          <option value="comptable">Comptable</option>
                          <option value="administrateur">Administrateur</option>
                          </select>';?>
                     </td>
                     <td>
                        <a href="index.php?uc=utilisateur&action=editer=<?php echo $id ?>" 
                           onclick="return confirm('Voulez-vous vraiment supprimer cet Utilisateur?');">Modifier</a>
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