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
         $ville = $_POST['ville'];
         $dateEmbauche = $_POST['dateEmbauche'];
         $typeconnexion = $_POST['typeconnexion'];
         $connexion;
         $nbId = $pdo->getIdExist($id);
         
         valideInfosUtilisateur($id, $nom, $prenom, $login, $mdp, $adresse, $cp, $ville,$dateEmbauche, $typeconnexion);
         $nbErreurs = nbErreurs();

         if ($nbErreurs == 0 & $nbId[0] == 0) {

            //Modificationdu type de compte 
            if ($typeconnexion == "visiteur") {
               $connexion = 1;
            } elseif ($typeconnexion == "comptable") {
               $connexion = 2;
            } else {
               $connexion = 3;
            }

            if ($nbId[0] == 1) {
               ajouterErreur("ID déjà existant. Il doit être différend de '" . $id . "'");
            }

            $nbLignes = $pdo->ajouterUtilisateur($id, $nom, $prenom, $login, $mdp, $adresse, $cp, $ville, $dateEmbauche, $typeconnexion);
            $nbErreurs = nbErreurs();

            if ($nbLignes != 0) {
               echo "<br>";
               switch ($connexion) {
                  case 1:
                     $message = "Le Visiteur " . $nom . " " . $prenom . " est crée";
                     break;
                  
                  case 2:
                     $message = "Le Comptable " . $nom . " " . $prenom . " est crée";
                     break;
                  
                  case 3:
                     $message = "L'Administrateur " . $nom . " " . $prenom . " est crée";
                     break;

                  default:
                     $message = "Aucun utilisateur n'est crée";
                     break;
               }
               
            } else {
               $message = ajouterErreur("!!Echec!! L'utilisateur " . $nom . " " . $prenom . " du compte " . $typeconnexion . " n'a pu être crée");
               include("vues/v_erreurs.php");
            }
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
         $pdo->supprimeIdUtilisateur($id);
         
         $message = "L'utilisateur a été supprimé !!";
         include_once ("vues/v_information.php");
        
         break;
      }

   default : {
          include("vues/v_sommaireAdministrateur.php");
         break;
      }
}
?>
