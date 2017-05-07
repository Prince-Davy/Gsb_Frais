<div id="contenu">
   <?php
   /*
    * Propriete du formulaire
    */
   if (isset($_POST['id']) && isset($_POST['login']) && isset($_POST['nom']) && isset($_POST['prenom']) && isset($_POST['mdp']) && isset($_POST['cp']) && isset($_POST['compte'])) {
      echo ("Formulaire Enregistré");
   } else {
      echo ("Erreur Creation Formulaire");
   }

   $id = htmlspecialchars($_POST['id']);

   echo $id;
 
//Redirection vers la page de creation 
   header("location:v_sommaireAdministrateur.php");
   ?>
</div>