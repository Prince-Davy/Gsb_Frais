<?php

include("vues/v_sommaireAdministrateur.php");

if (!isset($_REQUEST['action'])) {
   $_REQUEST['action'] = 'demandeConnexion';
}
$action = $_REQUEST['action'];

switch ($action) {

   case 'listeUtilisateur': {
         // récupération de la liste des utilisateurs(nom + prénom) sous forme de tableau associatifs
         $lesutilisateurs = $pdo->getLesInfosUtilisateurs();

         include("vues/v_listeUtilisateurs.php");
         break;
      }

   case 'creationUtilisateur': {
         include("vues/v_creationUtilisateur.php");
         break;
      }

   case 'sauverUtilisateur': {
         $id = $_POST['id'];
         $login = $_POST['login'];
         $nom = $_POST['nom'];
         $prenom = $_POST['prenom'];
         $adresse = $_POST['adresse'];
         $mdp = $_POST['mdp'];
         $cp = $_POST['cp'];
         $dateEmbauche = $_POST['dateEmbauche'];
         $ville = $_POST['ville'];
         $typeConnexion = $_POST['typeConnexion'];
         $connexion;
         $nbId = $pdo->getIdExist($id);
         valideInfosUtilisateur($id, $nom, $prenom, $login, $mdp, $adresse, $cp, $ville,$dateEmbauche, $typeConnexion);
         $nbErreurs = nbErreurs();

         if ($nbErreurs == 0 & $nbId[0] == 0) {

            //Modificationdu type de compte 
            if ($typeConnexion == "visiteur") {
               $connexion = 1;
            } elseif ($typeConnexion == "comptable") {
               $connexion = 2;
            } else {
               $connexion = 3;
            }

            if ($nbId[0] == 1) {
               ajouterErreur("ID déjà existant. Il doit être différend de '" . $id . "'");
            }


            $nbLigne = $pdo->ajouterUtilisateur($id, $nom, $prenom, $login, $mdp, $adresse, $cp, $ville,$dateEmbauche, $typeConnexion);
            $nbErreurs = nbErreurs();

            if ($nbLigne != 0) {
               echo "<br>";
               $message = "L'utilisateur " . $nom . " " . $prenom . " du compte " . $typeConnexion . " est crée";
            } else {
               $message = ajouterErreur("!!Echec!! L'utilisateur " . $nom . " " . $prenom . " du compte " . $typeConnexion . " n'a pu être crée");
               include("vues/v_erreurs.php");
            }
         } else {
            $message = ajouterErreur("!!Echec!! L'utilisateur " . $nom . " " . $prenom . " du compte " . $typeConnexion . " n'a pu être crée");
            include("vues/v_erreurs.php");
         }

         include_once ("vues/v_information.php");
         include("vues/v_creationUtilisateur.php");
         break;
      }

   case 'supprimerUtilisateur': {
         // récupération de la liste des utilisateurs(nom + prénom) sous forme de tableau associatifs
         $lesutilisateurs = $pdo->getLesInfosUtilisateurs();
         include("vues/v_supprimerUtilisateur.php");
         break;
      }

   case 'supprimer': {
         $id = $_REQUEST['id'];  
         /*$nom = $_REQUEST['nom'];  
         $prenom = $_REQUEST['prenom'];  */
         $pdo->supprimeIdUtilisateur($id);
         $message = "L'utilisateur a été supprimé !!";
         //$message = "L'utilisateur ".$prenom." ".$nom." a été supprimé !!";
         include_once ("vues/v_information.php");
         header('Location: http://localhost/GSB_FRAIS-master/index.php?uc=utilisateur&action=listeUtilisateur/');
         break;    
}

   case 'modifierUtilisateur': {
         // récupération de la liste des utilisateurs(nom + prénom) sous forme de tableau associatifs
         $lesutilisateurs = $pdo->getLesInfosUtilisateurs();
         include("vues/v_modifierUtilisateur.php");
         break;
      }
      
   case 'editer': {
      
      include("v_editerUtilisateur.php");
      include_once("vues/v_information.php");
      break;
   }

   default : {
         include("vues/v_listeUtilisateurs.php");
         break;
      }
}
?>
