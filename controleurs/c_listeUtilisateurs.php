<?php

if (!isset($_REQUEST['action'])) {
   $_REQUEST['action'] = 'demandeConnexion';
}
$action = $_REQUEST['action'];

switch ($action) {
   case 'liste': {
         // récupération de la liste des utilisateurs(nom + prénom) sous forme de tableau associatifs
         $lesutilisateurs = $pdo->getLesInfosUtilisateurs();
         include("vues/v_sommaireAdministrateur.php");
         include("vues/v_listeUtilisateurs.php");

         break;
      }
   /* Connexion en fonction du Statut de l'utilisateur */
   case 'creationUtilisateur': {
         
         $nouvUtilisateur = $pdo->creationUtilisateurs();
         include("vues/v_sommaireAdministrateur.php");
         break;
      }
   case 'supprimerUtilisateur': {

         include("vues/v_sommaireAdministrateur.php");
         break;
      }
   default : {
         include("vues/v_connexion.php");
         break;
      }
}
?>
