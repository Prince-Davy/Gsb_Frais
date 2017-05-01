<div id="contenu">
   <h2>Renseigner ma fiche de frais du mois <?php echo $numMois . "-" . $numAnnee ?></h2>

   <form method="POST"  action="index.php?uc=gererFrais&action=validerMajFraisForfait">
      <div class="corpsForm">

         <fieldset>
            <legend>Eléments forfaitisés
            </legend>
            <?php
            foreach ($lesFraisForfait as $unFrais) {
               $idFrais = $unFrais['idfrais'];
               $libelle = $unFrais['libelle'];
               $quantite = $unFrais['quantite'];
               ?>
               <p>
                  <label for="idFrais"><?php echo $libelle ?></label>
                  <input type="text" id="idFrais" name="lesFrais[<?php echo $idFrais ?>]" size="10" maxlength="5" value="<?php echo $quantite ?>" >
               </p>

               <?php
            }
            ?>           
            <div class="piedForm">
               <p>
                  <input id="ok" type="submit" value="Valider" size="20" />
                  <input id="annuler" type="reset" value="Effacer" size="20" />
               </p> 
            </div>

               <div>
               <fieldset>	
                  <legend>Indemnite Kilometriques</legend>

                  <div>	
                     <p> Type Vehicule
                        <select id="vehicule" name="vehicule" onclick="calculIndemnite()">
                           <option value="Véhicule 4CV">Véhicule 4CV</option>    
                           <option value="Véhicule 5CV">Véhicule 5CV</option>
                           <option value="Véhicule 6CV">Véhicule 6CV</option>
                        </select>
                     </p>
                  </div>

                  <div>	
                     <p> Puissance
                        <select id="puissance" name="puissance" onchange="calculIndemnite()">
                           <option value="Diesel">Diesel</option>
                           <option value="Essence">Essence</option>
                        </select>
                     </p>
                  </div>

                  <div>
                     <p> Distance <input name="Distance" id="distance" style="text-align:right" placeholder="0" type="number"> Km </p>
                  </div>

                  <div>
                     <button onclick="calculIndemnite()">Calculer</button> 
                     <br><br>
                     <p>Le montant de vos frais kilométriques s'élève à : </p>
                     <p id="montant"> Euros</p>
                  </div>


                  <!-- Calcul de l'indemnite Kilometrique -->
                  <script>
                     function calculIndemnite()
                     {
                        var x = document.getElementById("vehicule").value;
                        var y = document.getElementById("puissance").value;
                        var z = document.getElementById("distance").value;
                        var prix;
                        var resultat;

                        if ((y == "Essence") && ((x == "Véhicule 5CV") || ("Véhicule 6CV")))
                        {
                           prix = 0.67;

                        }
                        if ((y == "Essence") && ((x == "Véhicule 4CV")))
                        {
                           prix = 0.62;
                        }

                        if ((y == "Diesel") && ((x == "Véhicule 5CV") || ("Véhicule 6CV")))
                        {
                           prix = 0.58;
                        }
                        if ((y == "Diesel") && ((x == "Véhicule 4CV")))
                        {
                           prix = 0.52;
                        }

                        resultat = prix * z;
                        document.getElementById("montant").value = round(resultat);
                     }
                  </script>

               </fieldset>
            </div>
            </form>
