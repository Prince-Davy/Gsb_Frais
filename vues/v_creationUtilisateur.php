<div id="contenu">
   <h2>Ajout d'un visiteur</h2>


   <form method="POST" action="index.php?uc=utilisateur&action=sauverUtilisateur">
      <div class="corpsForm">
         <fieldset>
            <p>
               <label for="id">Id :</label>
               <input id="id" type="text" name="id"  placeholder="Da02" size="30" maxlength="45">
            </p>
            
            <p>
               <label for="prenom">Prénom :</label>
               <input id="prenom" type="text" name="prenom"  placeholder="Franck" size="30" maxlength="45">
            </p>
            
            <p>
               <label for="nom">Nom :</label>
               <input id="nom"  type="text"  name="nom" placeholder="Dubois" size="30" maxlength="45">
            </p>
            
            <p>
               <label for="login">Login :</label>
               <input id="login"  type="text"  name="login" placeholder="dufra" size="30" maxlength="45">
            </p>

            <p>
               <label for="mdp">Mdp :</label>
               <input id="mdp" type="password" name="mdp"  placeholder="dididada" size="30" maxlength="45">
            </p>

            <p>
               <label for="adresse">Adresse :</label>
               <input id="text"  type="text"  name="adresse" placeholder="13 rue des haricots" size="30" maxlength="45">
            </p>
            
            <p>
               <label for="cp">Cp :</label>
               <input id="text"  type="text"  name="cp" placeholder="95000" size="10" maxlength="45">
            </p>

            <p>
               <label for="ville">Ville :</label>
               <input id="ville"  type="text"  name="ville" placeholder="PlanTown" size="30" maxlength="45">
            </p>
            
            <p>
               <label for="dateEmbauche">DateEmbauche :</label>
               <input id="dateEmbauche"  type="text"  name="dateEmbauche" placeholder="10/12/2010" size="10" maxlength="45">
            </p>

            <label for="typeConnexion">Statut :</label>
            <select name="typeConnexion" size="1">
               <option value="visiteur" selected="selected">Visiteur</option>
               <option value="comptable">Comptable</option>
               <option value="administrateur">Administrateur</option>
            </select>
            <br>
            <br>
            <br>
            <p>
               <input type="submit" value="Valider" name="valider">
               <input type="reset" value="Annuler" name="annuler"> 
            </p>
         </fieldset>
      </div>
   </form>

</div>
