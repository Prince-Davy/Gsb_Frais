<div id="contenu">
   <h2>Ajout d'un visiteur</h2>


   <form method="POST" action="index.php?uc=utilisateur&action=editerUtilisateur">
      <div class="corpsForm">
         <fieldset>
            <p>
               <label for="id">Id :</label>
               <input id="id" type="text" name="id"  size="30" maxlength="45">
            </p>
            <p>
               <label for="nom">Nom :</label>
               <input id="nom"  type="text"  name="nom" size="30" maxlength="45">
            </p>
            <p>
               <label for="prenom">Prénom :</label>
               <input id="prenom" type="text" name="prenom"  size="30" maxlength="45">
            </p>

            <p>
               <label for="mdp">Mdp :</label>
               <input id="mdp" type="password" name="mdp"  size="30" maxlength="45">
            </p>

            <p>
               <label for="login">Login :</label>
               <input id="login"  type="text"  name="login" size="30" maxlength="45">
            </p>

            <p>
               <label for="adresse">Adresse :</label>
               <input id="text"  type="text"  name="adresse" size="30" maxlength="45">
            </p>

            <p>
               <label for="ville">Ville :</label>
               <input id="ville"  type="text"  name="ville" size="30" maxlength="45">
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
