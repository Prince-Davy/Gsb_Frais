<div id="page">
   <div id="contenu">
      <div class="corpsForm">
         <fieldset>
               <table class="listeLegere">
                  <tr>
                     <th class="login">Login</th>  
                     <th class="nom">Nom</th>  
                     <th class="prenom">Prenom</th> 
                     <th class="compte">Compte</th>
                     <th class="button">Bouton</th> 
                  </tr>

                  <?php
                  foreach ($lesutilisateurs as $unutilisateur) {
                     $id = $unutilisateur['id'];
                     $login = $unutilisateur['login'];
                     $nom = $unutilisateur['nom'];
                     $prenom = $unutilisateur['prenom'];
                     $compte = $unutilisateur['compte'];
                     ?>

                     <tr>
                        <td> <?php echo '<input type="text" value="' . $login . '"/>'; ?></td>
                        <td> <?php echo '<input type="text" value="' . $nom . '"/>'; ?></td>
                        <td> <?php echo '<input type="text" value="' . $prenom . '"/>'; ?></td>
                        <td> <?php echo '<select>
                          <option value="' . $compte . '" selected="selected">Visiteur</option>
                          <option value="' . $compte . '">Comptable</option>
                          <option value="' . $compte . '">Administrateur</option>
                          </select>'; ?>
                        </td>
                        <td>

                           <input type="submit" name="modifier" value="modifier"
                                  onclick="return confirm('Voulez-vous modifier cet Utilisateur?')">  
                           
                           <a onclick="return confirm('Vouslez vous modifier cet Utilisateur ?')"
                              href="editerUtilisateur.php?id=<?php echo $id ?>">Modifier</a>

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