<?php
if (!isset($_REQUEST['action'])) {
    $_REQUEST['action'] = 'demandeConnexion';
}
$action = $_REQUEST['action'];
switch ($action) {
    case 'demandeConnexion': {
            include("vues/v_connexion.php");
            break;
        }
    /* Connexion en fonction du Statut de l'utilisateur */
    case 'valideConnexion': {
            $login = $_REQUEST['login'];
            $mdp = $_REQUEST['mdp'];
            
            //La variable password contient le mdp Hashé
            //$password = $pdo->encrypt($login,$mdp);
            
            $utilisateur = $pdo->getInfosUtilisateur($login, $mdp);
            if (!is_array($utilisateur)) {
                ajouterErreur("Login ou mot de passe incorrect");
                include("vues/v_erreurs.php");
                include("vues/v_connexion.php");
            } else {
                $id = $utilisateur['id'];
                $nom = $utilisateur['nom'];
                $prenom = $utilisateur['prenom'];
                $compte = $utilisateur['compte'];
                $typeconnexion = $utilisateur['typeconnexion'];

                connecter($id, $nom, $prenom, $typeconnexion, $compte);

                //Affichage du sommaire en fonction du compte Utilisateur / Comptable / Administrateur               
                if ($typeconnexion == 2) {
                    include("vues/v_sommaireComptable.php");
                } else if ($typeconnexion == 3){
                    include("vues/v_sommaireAdministrateur.php");
                }else
                   include("vues/v_sommaireUtilisateur.php");
            }
            break;
        }
    default : {
            include("vues/v_connexion.php");
            break;
        }
}
?>
