<?php

include("vues/v_sommaireComptable.php");
// récupération de l'action 
$action = $_REQUEST['action'];
$page = "Suivi" ;

switch($action){
    case 'choixutilisateur': {
            // récupération de la liste des utilisateurs(nom + prénom) sous forme de tableau associatifs
            $lesutilisateurs = $pdo->getLesUtilisateurs();
            // récupération du premier utilisateur de liste classée par ordre alphabétique
            $idUtilisateur = $pdo->getIdUtilisateur();
            // récupération de l'id du premier utilisateur
            $idUtilisateur = $idUtilisateur['id'];

            $lesMois = $pdo->getLesMoisDisponibles($idUtilisateur);
            // Afin de sélectionner par défaut le dernier mois dans la zone de liste
            // on demande toutes les clés, et on prend la première,
            // les mois étant triés décroissants
            $lesCles = array_keys($lesMois);
            $moisASelectionner = $lesCles[0];

            include ("vues/v_listeUtilisateur_Mois.php");
            break;
        }

        case 'miseEnPaiement': {
            // récupération de l'id du utilisateur qui a été choisi
            $idUtilisateur = $_REQUEST['lstutilisateur'];
            //récupération du mois sélectionné
            $leMois = $_REQUEST['lstMois'];
            // récupération de l'information permettant de savoir si le comptable a changé de utilisateur
            //dans la liste déroulante des utilisateurs
            $modifListeUtilisateur = $_REQUEST['modifListeUtilisateur'];
            $lesutilisateurs = $pdo->getLesUtilisateurs();
            // récupération du nom et du prénom du utilisateur choisi
            $lUtilisateur = $pdo->getNomPrenom($idUtilisateur);
            $lesMois = $pdo->getLesMoisDisponibles($idUtilisateur);
            $moisASelectionner = $leMois;
            
            // transformation du mois choisi en format aaaamm
            $moisAng = getMoisAng($moisASelectionner);
            // récupération des informations concernant la fiche du utilisateur choisi en fonction
            //du mois sélectionné
            $infoutilisateur = $pdo->getLesInfosFicheFrais($idUtilisateur , $moisAng);
            // récupération des informations concernant la fiche du utilisateur choisi en fonction
            //du mois sélectionné
            // récupération des informations de la fiche des frais hors forfaits du utilisateur choisi en fonction du mois
            // variables globales pour connaitre la somme des frais forfaits et hors forfait
            $sommeFF = 0;
            $sommeFHF = 0;
            $lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($idUtilisateur, $moisAng);
            $lesFraisForfait = $pdo->getLesFraisForfait($idUtilisateur, $moisAng);
            // récupération des informations des frais forfaits du utilisateur en question
            $lesInfosFicheFrais = $pdo->getLesInfosFicheFrais($idUtilisateur, $moisAng);
            // récupération du nombre de justificatif du mois
            $nbJustificatifs = $lesInfosFicheFrais['nbJustificatifs'];
            if ($pdo->estPremierFraisHorsForfaitMois($idUtilisateur, $moisAng)) {
                $ok = TRUE;
            } else {
                $ok = FALSE;
            }
            if ($modifListeUtilisateur === "Oui") {
                include ("vues/v_listeUtilisateur_Mois.php");
            } else {
                // test pour savoir si la fiche en question est la première du mois
                if (!$pdo->estPremierFraisMois($idUtilisateur, $moisAng)) {
                    include ("vues/v_listeUtilisateur_Mois.php");
                    include ("vues/v_suiviFrais.php");
                } else {
                    // inclusion de la vue pour l'affichage de l'information
                    $message = $lUtilisateur['nom'] . " " . $lUtilisateur['prenom'] . " n'a pas de fiche de frais pour la période de " . $moisASelectionner;
                    include_once ("vues/v_information.php");
                    include_once ("vues/v_listeUtilisateur_Mois.php");
                }
            }
            break;
        }
    
    case 'ficheFais': {
    // récupération de l'id du utilisateur qui a été choisi
            $idUtilisateur = $_REQUEST['lstUtilisateur'];
            //récupération du mois sélectionné
            $leMois = $_REQUEST['lstMois'];
            // récupération de l'information permettant de savoir si le comptable a changé de utilisateur
            //dans la liste déroulante des utilisateurs
            $modifListeUtilisateur = $_REQUEST['modifListeUtilisateur'];
            $lesutilisateurs = $pdo->getLesUtilisateurs();
            // récupération du nom et du prénom du utilisateur choisi
            $lUtilisateur = $pdo->getNomPrenom($idUtilisateur);
            $lesMois = $pdo->getLesMoisDisponibles($idUtilisateur);
            $moisASelectionner = $leMois;
            // transformation du mois choisi en format aaaamm
            $moisAng = getMoisAng($moisASelectionner);
            // récupération des informations concernant la fiche du utilisateur choisi en fonction
            //du mois sélectionné
            // récupération des informations de la fiche des frais hors forfaits du utilisateur choisi en fonction du mois
            // variables globales pour connaitre la somme des frais forfaits et hors forfait
            $sommeFF = 0;
            $sommeFHF = 0;
            $lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($idUtilisateur, $moisAng);
            $lesFraisForfait = $pdo->getLesFraisForfait($idUtilisateur, $moisAng);
            // récupération des informations des frais forfaits du utilisateur en question
            $lesInfosFicheFrais = $pdo->getLesInfosFicheFrais($idUtilisateur, $moisAng);
            // récupération du nombre de justificatif du mois
            $nbJustificatifs = $lesInfosFicheFrais['nbJustificatifs'];
            if ($pdo->estPremierFraisHorsForfaitMois($idUtilisateur, $moisAng)) {
                $ok = TRUE;
            } else {
                $ok = FALSE;
            }
            if ($modifListeUtilisateur === "Oui") {
                include ("vues/v_listeUtilisateur_Mois.php");
            } else {
                // test pour savoir si la fiche en question est la première du mois
                if (!$pdo->estPremierFraisMois($idUtilisateur, $moisAng)) {
                    include ("vues/v_listeUtilisateur_Mois.php");
                    include ("vues/v_validerFrais.php");
                } else {
                    // inclusion de la vue pour l'affichage de l'information
                    $message = $lUtilisateur['nom'] . " " . $lUtilisateur['prenom'] . " n'a pas de fiche de frais pour la période de " . $moisASelectionner;
                    include_once ("vues/v_information.php");
                    include_once ("vues/v_listeUtilisateur_Mois.php");
                }
            }
            break;
        }
    
    case 'paiementFiche':{
            // récupération de l'id du utilisateur qui a été choisi
            $idUtilisateur = $_REQUEST['idUtilisateur'] ;
            // récupération du mois en format mmmmaa choisi.   
            $moisAng = $_REQUEST['mois'] ;  
            // déclaration d'une variable $etat    
            $etat = "VA" ;
            //mise à jour de la fiche de frais du utilisateur choisi en fonction du mois   
            $pdo->majEtatFicheFrais($idUtilisateur , $moisAng , $etat ) ; 
            // Récupération de la liste des utilisateurs   
            $lesutilisateurs = $pdo->getLesUtilisateurs();           
            // récupération du nom et du prénom du utilisateur choisi
            $lUtilisateur = $pdo->getNomPrenom($idUtilisateur);
             // récupération des mois disponibles    
            $lesMois = $pdo->getLesMoisDisponibles($idUtilisateur);
            // Récupération du mois en format aammmm   
            $moisASelectionner = getMoisFr($moisAng) ;
            // récupération des informations des frais forfait du utilisateur en question
            $infoutilisateur = $pdo->getLesInfosFicheFrais($idUtilisateur , $moisAng);
            // récupération des informations des frais hors forfait du utilisateur en question    
            $lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($idUtilisateur,$moisAng);
            // variables globales pour connaitre la somme des frais forfaits et hors forfait   
            $sommeFF = 0;
            $sommeFHF = 0;
            $lesFraisForfait= $pdo->getLesFraisForfait($idUtilisateur,$moisAng);
            // test pour savoir si la fiche en suestion est la première du mois
            if ($pdo->estPremierFraisHorsForfaitMois($idUtilisateur,$moisAng)) {
                $ok = TRUE;
            } else {
                $ok = FALSE;
            }
             include ("vues/v_listeUtilisateur_Mois.php");
            include ("vues/v_suiviFrais.php") ;
            break;
    }
    case 'rembourserFiche':{        
            // récupération de l'id du utilisateur qui a été choisi
            $idUtilisateur = $_REQUEST['idUtilisateur'] ;
            // récupération du mois en format mmmmaa choisi.   
            $moisAng = $_REQUEST['mois'] ;  
            // déclaration d'une variable $etat         
            $etat = "RB" ;
            //mise à jour de la fiche de frais du utilisateur choisi en fonction du mois   
            $pdo->majEtatFicheFrais($idUtilisateur , $moisAng , $etat ) ; 
            // Récupération de la liste des utilisateurs   
            $lesutilisateurs = $pdo->getLesUtilisateurs();           
            // récupération du nom et du prénom du utilisateur choisi
            $lUtilisateur = $pdo->getNomPrenom($idUtilisateur);
            // récupération des mois disponibles    
            $lesMois = $pdo->getLesMoisDisponibles($idUtilisateur);
            // Récupération du mois en format aammmm   
            $moisASelectionner = getMoisFr($moisAng) ;
            // récupération des informations des frais forfait du utilisateur en question
            $infoutilisateur = $pdo->getLesInfosFicheFrais($idUtilisateur , $moisAng);
            // récupération des informations des frais hors forfait du utilisateur en question    
            $lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($idUtilisateur,$moisAng);
            // variables globales pour connaitre la somme des frais forfaits et hors forfait   
            $sommeFF = 0;
            $sommeFHF = 0;
            $lesFraisForfait= $pdo->getLesFraisForfait($idUtilisateur,$moisAng);
            // test pour savoir si la fiche en suestion est la première du mois
            if ($pdo->estPremierFraisHorsForfaitMois($idUtilisateur,$moisAng)) {
                $ok = TRUE;
            } else {
                $ok = FALSE;
            }
             include ("vues/v_listeUtilisateur_Mois.php");
            include ("vues/v_suiviFrais.php") ;
            break;
    }
    default :
            // récupération du nom + du prénom des utilisateurs
            $lesutilisateurs = $pdo->getLesUtilisateurs();
            // récupération du premier utilisateur dans la liste classé par ordre aplphabétique
            $idUtilisateur = $pdo->getidUtilisateur();
            $idUtilisateur = $idUtilisateur['id'] ;
            $lesMois = $pdo->getLesMoisDisponibles($idUtilisateur);
            // Afin de sélectionner par défaut le dernier mois dans la zone de liste
            // on demande toutes les clés, et on prend la première,
            // les mois étant triés décroissants
            $lesCle = array_keys( $lesMois );
            $moisASelectionner = $lesCle[0];
             include ("vues/v_listeUtilisateur_Mois.php");
            break;
} 
?>
