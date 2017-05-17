<?php
/**
 * Fonctions pour l'application GSB

 * @package default
 * @author Cheri Bibi
 * @version    1.0
 */

/**
 * Teste si un quelconque utilisateur est connecté
 * @return vrai ou faux 
 */
function estConnecte() {
   return isset($_SESSION['idUtilisateur']);
}

/**
 * Enregistre dans une variable session les infos d'un utilisateur
 * @param $id 
 * @param $nom
 * @param $prenom
 * @param $typeconnexion
 * @param compte
 */
function connecter($id, $nom, $prenom, $typeconnexion, $compte) {
   $_SESSION['idUtilisateur'] = $id;
   $_SESSION['nom'] = $nom;
   $_SESSION['prenom'] = $prenom;
   $_SESSION['typeconnexion'] = $typeconnexion;
   $_SESSION['compte'] = $compte;
}

/**
 * Enregistre dans une variable session les infos d'un utilisateur
 * @param $id 
 * @param $nom
 * @param $prenom
 * @param $typeconnexion
 * @param compte
 * @param $mdp 
 * @param $adresse
 * @param $cp
 * @param $dateEmbauche
 */
function session($id, $login, $nom, $prenom, $mdp, $adresse, $cp, $dateEmbauche) {
   $_SESSION['idUtilisateur'] = $id;
   $_SESSION['nom'] = $nom;
   $_SESSION['prenom'] = $prenom;
   $_SESSION['login'] = $login;
   $_SESSION['adresse'] = $adresse;
   $_SESSION['dateEmbauche'] = $dateEmbauche;
   $_SESSION['mdp'] = $mdp;
   $_SESSION['cp'] = $cp;
}

/**
 * Détruit la session active
 */
function deconnecter() {
   session_destroy();
}

/**
 * Transforme une date au format français jj/mm/aaaa vers le format anglais aaaa-mm-jj

 * @param $madate au format  jj/mm/aaaa
 * @return la date au format anglais aaaa-mm-jj
 */
function dateFrancaisVersAnglais($maDate) {
   @list($jour, $mois, $annee) = explode('/', $maDate);
   return date('Y-m-d', mktime(0, 0, 0, $mois, $jour, $annee));
}

/**
 * Transforme une date au format format anglais aaaa-mm-jj vers le format français jj/mm/aaaa 

 * @param $madate au format  aaaa-mm-jj
 * @return la date au format format français jj/mm/aaaa
 */
function dateAnglaisVersFrancais($maDate) {
   @list($annee, $mois, $jour) = explode('-', $maDate);
   $date = "$jour" . "/" . $mois . "/" . $annee;
   return $date;
}

/**
 * retourne le mois au format aaaamm selon le jour dans le mois

 * @param $date au format  jj/mm/aaaa
 * @return le mois au format aaaamm
 */
function getMois($date) {
   @list($jour, $mois, $annee) = explode('/', $date);
   if (strlen($mois) == 1) {
      $mois = "0" . $mois;
   }
   return $annee . $mois;
}

/**
 * retourne le mois au format aaaamm en fonction du mois au format mm/aaaa
 * @author Prince-Davy Nkakou
 * @param $date au format mmaaaa
 * @return String le mois au format aaaamm
 */
function getMoisAng($date) {
   $chaine = substr($date, 0, 2);
   $chaine2 = substr($date, 3, 7);
   return $chaine2 . "" . $chaine;
}

/* gestion des erreurs */

/**
 * retourne le mois au format mmaaaa en fonction du mois au format aaaamm
 * @param $date au format aaaamm
 * @return String le mois au format mmaaaa
 */
function getMoisFr($date) {
   $chaine = substr($date, 0, 4);
   $chaine2 = substr($date, 4, 7);
   return $chaine2 . "/" . $chaine;
}

/**
 * Indique si une valeur est un entier positif ou nul

 * @param $valeur
 * @return vrai ou faux
 */
function estEntierPositif($valeur) {
   return preg_match("/[^0-9]/", $valeur) == 0;
}

/**
 * Indique si un tableau de valeurs est constitué d'entiers positifs ou nuls

 * @param $tabEntiers : le tableau
 * @return vrai ou faux
 */
function estTableauEntiers($tabEntiers) {
   $ok = true;
   foreach ($tabEntiers as $unEntier) {
      if (!estEntierPositif($unEntier)) {
         $ok = false;
      }
   }
   return $ok;
}

/**
 * Vérifie si une date est inférieure d'un an à la date actuelle

 * @param $dateTestee 
 * @return vrai ou faux
 */
function estDateDepassee($dateTestee) {
   $dateActuelle = date("d/m/Y");
   @list($jour, $mois, $annee) = explode('/', $dateActuelle);
   $annee--;
   $AnPasse = $annee . $mois . $jour;
   @list($jourTeste, $moisTeste, $anneeTeste) = explode('/', $dateTestee);
   return ($anneeTeste . $moisTeste . $jourTeste < $AnPasse);
}

/**
 * Vérifie la validité du format d'une date française jj/mm/aaaa 
 * @param $date 
 * @return vrai ou faux
 */
function estDateValide($date) {
   $tabDate = explode('/', $date);
   $dateOK = true;
   if (count($tabDate) != 3) {
      $dateOK = false;
   } else {
      if (!estTableauEntiers($tabDate)) {
         $dateOK = false;
      } else {
         if (!checkdate($tabDate[1], $tabDate[0], $tabDate[2])) {
            $dateOK = false;
         }
      }
   }
   return $dateOK;
}

/**
 * Vérifie que le tableau de frais ne contient que des valeurs numériques 

 * @param $lesFrais 
 * @return vrai ou faux
 */
function lesQteFraisValides($lesFrais) {
   return estTableauEntiers($lesFrais);
}

/**
 * Vérifie la validité des trois arguments : la date, le libellé du frais et le montant 

 * des message d'erreurs sont ajoutés au tableau des erreurs

 * @param $dateFrais 
 * @param $libelle 
 * @param $montant
 */
function valideInfosFrais($dateFrais, $libelle, $montant) {
   if ($dateFrais == "") {
      ajouterErreur("Le champ date ne doit pas être vide");
   } else {
      if (!estDatevalide($dateFrais)) {
         ajouterErreur("Date invalide");
      } else {
         if (estDateDepassee($dateFrais)) {
            ajouterErreur("date d'enregistrement du frais dépassé, plus de 1 an");
         }
      }
   }
   if ($libelle == "") {
      ajouterErreur("Le champ description ne peut pas être vide");
   }
   if ($montant == "") {
      ajouterErreur("Le champ montant ne peut pas être vide");
   } else
   if (!is_numeric($montant)) {
      ajouterErreur("Le champ montant doit être numérique");
   }
}

/**
 * Ajoute le libellé d'une erreur au tableau des erreurs 

 * @param $msg : le libellé de l'erreur 
 */
function ajouterErreur($msg) {
   if (!isset($_REQUEST['erreurs'])) {
      $_REQUEST['erreurs'] = array();
   }
   $_REQUEST['erreurs'][] = $msg;
}

/**
 * Retoune le nombre de lignes du tableau des erreurs 

 * @return le nombre d'erreurs
 */
function nbErreurs() {
   if (!isset($_REQUEST['erreurs'])) {
      return 0;
   } else {
      return count($_REQUEST['erreurs']);
   }
}

/**
 * Affichage d'espaces
 * @param  $nbEspaces nombre d'espaces à afficher
 */
function espace($nbEspaces = 1) {
   for ($k = 0; $k < $nbEspaces; $k++) {
      echo "&nbsp;";
   }
}

/**
 * Retourne le mois suivant en fonction du mois passé en paramètre au format mmmmaa
 * @author Prince-Davy Nkakou
 * @param  $mois  
 * @return $mois
 */
function prochainMois($mois) {
   $numMois = (int) substr($mois, 4, 7);
   $numAnnee = (int) substr($mois, 0, 4);
   if (($numMois < 10) && ($numMois < 9)) {
      $numMois += 1;
      $numMois = "0" . $numMois;
   } elseif (($numMois >= 10) && ($numMois < 12)) {
      $numMois += 1;
   } elseif ($numMois == 9) {
      $numMois += 1;
   } elseif ($numMois == 12) {
      $numMois = "01";
      $numAnnee += 1;
   }
   return $numAnnee . "" . $numMois;
}

/**
 * Fournit le libellé en français correspondant à un numéro de mois.                     
 *
 * Fournit le libellé français du mois de numéro $unNoMois.
 * Retourne une chaîne vide si le numéro n'est pas compris dans l'intervalle [1,12].
 * @param int numéro de mois
 * @return string identifiant de connexion
 */
function obtenirLibelleMois($unNoMois) {
   $tabLibelles = array(1 => "Janvier",
       "Février", "Mars", "Avril", "Mai", "Juin", "Juillet",
       "Août", "Septembre", "Octobre", "Novembre", "Décembre");
   $libelle = "";
   if ($unNoMois >= 1 && $unNoMois <= 12) {
      $libelle = $tabLibelles[$unNoMois];
   }
   return $libelle;
}

/**
 * Fournit la valeur d'une donnée transmise par la méthode post 
 *  (corps de la requête HTTP).                    
 * 
 * Retourne la valeur de la donnée portant le nom $nomDonnee reçue dans le corps de la requête http, 
 * $valDefaut si aucune donnée de nom $nomDonnee dans le corps de requête
 * @param string nom de la donnée
 * @param string valeur par défaut 
 * @return string valeur de la donnée
 */
function lireDonneePost($nomDonnee, $valDefaut = "") {
   if (isset($_POST[$nomDonnee])) {
      $val = $_POST[$nomDonnee];
   } else {
      $val = $valDefaut;
   }
   return $val;
}

/**
 * Vérifie la validité des 8 arguments : l'id, le prenom, le nom, le login, le mot de passe, l'adresse, le cp, la ville 
 * des message d'erreurs sont ajoutés au tableau des erreurs
 * @param $id
 * @param $login
 * @param $prenom 
 * @param $nom
 * @param $login
 * @param $mdp
 * @param $adresse
 * @param $cp
 * @param $ville
 */
function valideInfosUtilisateur($id, $nom, $prenom, $login, $mdp, $adresse, $cp, $ville,$dateEmbauche, $typeconnexion) {

   if ($id == "") {
      ajouterErreur("Le champ id ne peut pas être vide");
   }

   if ($login == "") {
      ajouterErreur("Le champ login ne peut pas être vide");
   }

   if ($prenom == "") {
      ajouterErreur("Le champ prenom ne peut pas être vide");
   }
   if ($nom == "") {
      ajouterErreur("Le champ nom ne peut pas être vide");
   }

   if ($adresse == "") {
      ajouterErreur("Le champ adresse ne peut pas être vide");
   }

   if ($mdp == "") {
      ajouterErreur("Le champ adresse ne peut pas être vide");
   }

   if ($ville == "") {
      ajouterErreur("Le champ ville ne peut pas être vide");
   }
   
   if(!estDateValide($dateEmbauche)){
            ajouterErreur("Le format de date n'est pas valide");
	}

   if (!is_numeric($cp)) {
      ajouterErreur("Veuillez saisir des valeurs numeriques");
   } else {
      if ($cp == "") {
         ajouterErreur("Le champ ville ne peut pas être vide");
      }
   }

   if ($typeconnexion == "") {
      ajouterErreur("Le champ typeconnexion ne peut pas être vide");
   }
}


/**
 * Mise à jour des données d'un utilisateur
 * @param type $nom
 * @param type $prenom
 * @param type $adresse
 * @param type $cp
 * @param type $ville
 */
function valideModifUtilisateur($nom, $prenom, $adresse, $cp, $ville) {
   if ($nom == "") {
      ajouterErreur("Le champ nom ne peut pas être vide");
   }

   if ($prenom == "") {
      ajouterErreur("Le champ prenom ne peut pas être vide");
   }
   if ($adresse == "") {
      ajouterErreur("Le champ adresse ne peut pas être vide");
   }

   if ($cp == "") {
      ajouterErreur("Le champ code postale ne peut pas être vide");
   }
   if ($ville == "") {
      ajouterErreur("Le champ ville ne peut pas être vide");
   }
}
?>
