<?php

include("vues/v_sommaireComptable.php");

// récupération de l'action 
$action = $_REQUEST['action'];
$page = "Validation";
$lesFrais;
//$idUtilisateur = $_SESSION['idUtilisateur'];

switch ($action) {
    case 'choixutilisateur_Mois': {
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

        case 'validerFrais': {
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
        
    case 'validerMajFraisForfait':{
           if(isset($_POST['valid'])){ 
           // récupération des informations sur les frais forfaits   
           $lesFrais = $_REQUEST['lesFrais'];
           // récupération de l'id du utilisateur choisi  
           $idUtilisateur = $_REQUEST['idVis'] ;
           // récupération du mois choisi au format mmmmaa  
           $moisAng = $_REQUEST['leMois'] ;         
           // Si les frais forfaits validés respectent les conditions établies   
           if (lesQteFraisValides($lesFrais)) {
            // Mise à jour des éléments forfaitisés      
	  	        $pdo->majFraisForfait($idUtilisateur,$moisAng,$lesFrais);
            } else {
            // ajout d'une erreur au tableau des erreurs      
	       	     ajouterErreur("Les valeurs des frais doivent être numériques");
		           include("vues/v_erreurs.php");
           }
            // récupération de la liste des utilisateurs   
            $lesutilisateurs = $pdo->getLesUtilisateurs();           
            // récupération du nom et du prénom du utilisateur choisi
            $lUtilisateur = $pdo->getNomPrenom($idUtilisateur);  
            // récupération des mois disponibles du utilisateur choisi   
            $lesMois = $pdo->getLesMoisDisponibles($idUtilisateur);
            // récupération de la date choisi au format mmmmaa    
            $moisASelectionner = getMoisFr($moisAng) ;
            // initialisation de la variable $message avec l'information précisant que les modifications effectuées 
            // ont été prise en compte    
            $message = "Les modifications concernant la fiche de frais de " . $lUtilisateur['nom']." ". $lUtilisateur['prenom'] .
            " du mois de " . $moisASelectionner . " ont été prises en compte." ;
            // inclusion de la vues v_information pour l'affichage du message   
            include ("vues/v_information.php");
            $lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($idUtilisateur,$moisAng);
            // variables globales pour connaitre la somme des frais forfaits et hors forfait
            $sommeFF = 0;
            $sommeFHF = 0;
            $lesFraisForfait= $pdo->getLesFraisForfait($idUtilisateur,$moisAng);
            // récupération des informations des frais forfaits du utilisateur en question
            $lesInfosFicheFrais = $pdo->getLesInfosFicheFrais($idUtilisateur,$moisAng); 
            // récupération du nombre de justificatif du mois
            $nbJustificatifs = $lesInfosFicheFrais['nbJustificatifs'];
            if ($pdo->estPremierFraisHorsForfaitMois($idUtilisateur,$moisAng)) {
                $ok = TRUE;
            } else {
                $ok = FALSE;
            }
             include ("vues/v_listeUtilisateur_Mois.php");
            include ("vues/v_validerFrais.php") ;
            break;
      }
}
        
    case 'validerMajFraisForfait':{
           if(isset($_POST['valid'])){ 
           // récupération des informations sur les frais forfaits   
           $lesFrais = $_REQUEST['lesFrais'];
           // récupération de l'id du utilisateur choisi  
           $idUtilisateur = $_REQUEST['idVis'] ;
           // récupération du mois choisi au format mmmmaa  
           $moisAng = $_REQUEST['leMois'] ;         
           // Si les frais forfaits validés respectent les conditions établies   
           if (lesQteFraisValides($lesFrais)) {
            // Mise à jour des éléments forfaitisés      
	  	        $pdo->majFraisForfait($idUtilisateur,$moisAng,$lesFrais);
            } else {
            // ajout d'une erreur au tableau des erreurs      
	       	     ajouterErreur("Les valeurs des frais doivent être numériques");
		           include("vues/v_erreurs.php");
           }
            // récupération de la liste des utilisateurs   
            $lesutilisateurs = $pdo->getLesUtilisateurs();           
            // récupération du nom et du prénom du utilisateur choisi
            $lUtilisateur = $pdo->getNomPrenom($idUtilisateur);  
            // récupération des mois disponibles du utilisateur choisi   
            $lesMois = $pdo->getLesMoisDisponibles($idUtilisateur);
            // récupération de la date choisi au format mmmmaa    
            $moisASelectionner = getMoisFr($moisAng) ;
            // initialisation de la variable $message avec l'information précisant que les modifications effectuées 
            // ont été prise en compte    
            $message = "Les modifications concernant la fiche de frais de " . $lUtilisateur['nom']." ". $lUtilisateur['prenom'] .
            " du mois de " . $moisASelectionner . " ont été prises en compte." ;
            // inclusion de la vues v_information pour l'affichage du message   
            include ("vues/v_information.php");
            $lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($idUtilisateur,$moisAng);
            // variables globales pour connaitre la somme des frais forfaits et hors forfait
            $sommeFF = 0;
            $sommeFHF = 0;
            $lesFraisForfait= $pdo->getLesFraisForfait($idUtilisateur,$moisAng);
            // récupération des informations des frais forfaits du utilisateur en question
            $lesInfosFicheFrais = $pdo->getLesInfosFicheFrais($idUtilisateur,$moisAng); 
            // récupération du nombre de justificatif du mois
            $nbJustificatifs = $lesInfosFicheFrais['nbJustificatifs'];
            if ($pdo->estPremierFraisHorsForfaitMois($idUtilisateur,$moisAng)) {
                $ok = TRUE;
            } else {
                $ok = FALSE;
            }
             include ("vues/v_listeUtilisateur_Mois.php");
            include ("vues/v_validerFrais.php") ;
            break;
      }
}
    case 'supprimerFrais':{
            // récupération de l'id du frais correspondant à la ligne choisie
            $idFrais = $_REQUEST['idFrais'];
            // récupération du mois au format mmmmaa et de l'id du utilisateur
            $moisAng = $_REQUEST['moisAng'] ;
            $idUtilisateur = $_REQUEST['idVis'] ;
            // récupération des informations se trouvant à un tuple dans la base se données
            $infoFrais = $pdo->getLibelleFraisHorsForfait($idUtilisateur , $moisAng , $idFrais) ;
            //récupération de la liste des utilisateur (nom + prénom)
            $lesutilisateurs = $pdo->getLesUtilisateurs();  
            // Teste pour savoir si un fiche existe pour un utilisateur le mois suivant le mois qui a été choisi
            if ($pdo->estPremierFraisMois($idUtilisateur , prochainMois($moisAng))) {
                // Création d'un nouvelle fiche avec pour valeur 0
                $pdo->creeNouvellesLignesFrais($idUtilisateur , prochainMois($moisAng)) ;
                // ajout de la ligne de frais hors forfait refusé au prochain mois
                $pdo->creeNouveauFraisHorsForfait($idUtilisateur,  prochainMois($moisAng), $infoFrais['libelle'] 
                   , dateAnglaisVersFrancais($infoFrais['date']), $infoFrais['montant']);
            } else {
                 $pdo->creeNouveauFraisHorsForfait($idUtilisateur,  prochainMois($moisAng),
                 $infoFrais['libelle'], dateAnglaisVersFrancais($infoFrais['date']) 
                 , $infoFrais['montant']);
            }
            // ajout du texte REFUSE en début de libellé
            $pdo->ajouteRefuse($idFrais);
            // récupération du nom et du prénom du utilisateur choisi
            // récupération du nom + prénom du utilisateur choisi
            $lUtilisateur = $pdo->getNomPrenom($idUtilisateur);
            // récupération des mois disponible du utilisateur pour remplir la comboBox
            $lesMois = $pdo->getLesMoisDisponibles($idUtilisateur); 
            // modification du mois choisi au format aammmm
            $moisASelectionner = getMoisFr($moisAng) ;
            // récupération des informations des fiches de frais hors forfaits du utilisateur en fonction du mois
            $lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($idUtilisateur,$moisAng);
            // initialisation des variables pour le calcul respectif des fraits forfaits et hors forfaits
            $sommeFF = 0;
            $sommeFHF = 0;
            // récupération des informations des frais  forfaits du utilisateur en fonction du mois
            $lesFraisForfait = $pdo->getLesFraisForfait($idUtilisateur,$moisAng);
            $lesInfosFicheFrais = $pdo->getLesInfosFicheFrais($idUtilisateur,$moisAng);
            // récupération du nombre de justificatifs
            $nbJustificatifs = $lesInfosFicheFrais['nbJustificatifs'];
            // condition pour savoir si les frais hors forfaits du utilisateurs sont premières du mois ou pas
            if ($pdo->estPremierFraisHorsForfaitMois($idUtilisateur,$moisAng)) {
                $ok = TRUE;
            } else {
                $ok = FALSE;
            }
             include ("vues/v_listeUtilisateur_Mois.php");
            include ("vues/v_validerFrais.php") ;
            break;
	}
    case 'validerJustificatif':{
            // récupération du nombre de justificatif actuel
            $nb = $_REQUEST['nbJustificatif']; 
            // récupération de l'id du utilisateur choisi et du mois choisi mais au format mmmmaa   
            $idUtilisateur = $_REQUEST['idVis'] ;
            $moisAng = $_REQUEST['leMois'] ;
            // condition pour savoir si le nombre de justificatif est positif ou pas   
            if (estEntierPositif($nb)) {
             // mise à jour du nombre de justificatifs       
                $pdo->majNbJustificatifs($idUtilisateur , $moisAng , $nb) ;            
            } else {
                // ajout d'un erreur au tableau des erreurs     
	             	ajouterErreur("Les valeurs des frais doivent être numériques");
                // affichage de l'erreur      
		            include("vues/v_erreurs.php");
            }
            // récupération de la liste des utilisateurs dans la base de données   
            $lesutilisateurs = $pdo->getLesUtilisateurs();           
            // récupération du nom et du prénom du utilisateur choisi
            $lUtilisateur = $pdo->getNomPrenom($idUtilisateur);
            // récupération des mois disponibles d'un utilisateur   
            $lesMois = $pdo->getLesMoisDisponibles($idUtilisateur);
            // récupération du mois choisi au format aammmm   
            $moisASelectionner = getMoisFr($moisAng) ;
            // affichage du message stipulant qu'une modification a été effectuée   
            $message = "Les modifications concernant la fiche de frais de " . $lUtilisateur['nom']." ". $lUtilisateur['prenom'] . " du mois de " . $moisASelectionner . " ont été prises en compte." ;
            include ("vues/v_information.php");
            // récupération des informations concernant les fiches de frais du utilisateur choisi  
            $infoutilisateur = $pdo->getLesInfosFicheFrais($idUtilisateur , $moisAng);
            $lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($idUtilisateur,$moisAng);
            $sommeFF = 0;
            $sommeFHF = 0;
            $lesFraisForfait= $pdo->getLesFraisForfait($idUtilisateur,$moisAng);
            $lesInfosFicheFrais = $pdo->getLesInfosFicheFrais($idUtilisateur,$moisAng);
            $nbJustificatifs = $lesInfosFicheFrais['nbJustificatifs'];
            if ($pdo->estPremierFraisHorsForfaitMois($idUtilisateur,$moisAng)) {
                $ok = TRUE;
            } else {
                $ok = FALSE;
            }
            include ("vues/v_listeUtilisateur_Mois.php");
            include ("vues/v_validerFrais.php") ;
            break;
    }
    case 'validerFiche':{        
            $idUtilisateur = $_REQUEST['idVis'] ;
            $moisAng = $_REQUEST['leMois'] ;
            $montantV = $_REQUEST['montantValide'] ;
            $etat = "VA" ;
            $pdo->majEtatFicheFrais($idUtilisateur , $moisAng , $etat) ;
            $pdo->majMontantValide($idUtilisateur , $moisAng , $montantV) ;
            $lesutilisateurs = $pdo->getLesUtilisateurs();           
            // récupération du nom et du prénom du utilisateur choisi
            $lUtilisateur = $pdo->getNomPrenom($idUtilisateur);        
            $lesMois = $pdo->getLesMoisDisponibles($idUtilisateur);
            $moisASelectionner = getMoisFr($moisAng) ;
            $message = "Les modifications concernant la fiche de frais de " . $lUtilisateur['nom']." ". $lUtilisateur['prenom'] . " du mois de " . $moisASelectionner . " ont été prises en compte." ;
            include ("vues/v_information.php");
            $infoutilisateur = $pdo->getLesInfosFicheFrais($idUtilisateur , $moisAng);
            $lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($idUtilisateur,$moisAng);
            $sommeFF = 0;
            $sommeFHF = 0;
            $lesFraisForfait= $pdo->getLesFraisForfait($idUtilisateur,$moisAng);
            $lesInfosFicheFrais = $pdo->getLesInfosFicheFrais($idUtilisateur,$moisAng);
            $nbJustificatifs = $lesInfosFicheFrais['nbJustificatifs'];
            if ($pdo->estPremierFraisHorsForfaitMois($idUtilisateur,$moisAng)) {
                $ok = TRUE;
            } else {
                $ok = FALSE;
            }
             include ("vues/v_listeUtilisateur_Mois.php");
             include ("vues/v_validerFrais.php") ;
            break;
    }
    default :
        // récupération de la liste des utilisateurs(nom + prénom) sous forme de tableau associatifs
        $lesutilisateurs = $pdo->getLesUtilisateurs();
        // récupération du premier utilisateur de liste classée par ordre alphabétique
        $idUtilisateur = $pdo->getidUtilisateur();
        // récupération de l'id du premier utilisateur
        $idUtilisateur = $idUtilisateur['id'] ;
        $lesMois = $pdo->getLesMoisDisponibles($idUtilisateur);
        //$k = 1;
        // Afin de sélectionner par défaut le dernier mois dans la zone de liste
        // on demande toutes les clés, et on prend la première,
        // les mois étant triés décroissants
        $lesCle = array_keys( $lesMois );
        // récupération du mois en cours du premier utilisateur 
        $moisASelectionner = $lesCle[0];
        include ("vues/v_listeUtilisateur_Mois.php");
        break;
}

