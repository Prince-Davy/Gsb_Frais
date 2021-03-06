﻿<?php

/**
 * Classe d'accès aux données. 

 * Utilise les services de la classe PDO
 * pour l'application GSB
 * Les attributs sont tous statiques,
 * les 4 premiers pour la connexion
 * $monPdo de type PDO 
 * $monPdoGsb qui contiendra l'unique instance de la classe

 * @package default
 * @author Cheri Bibi
 * @version    1.0
 * @link       http://www.php.net/manual/fr/book.pdo.php
 */
class PdoGsb {

   private static $serveur = 'mysql:host=localhost';
   private static $bdd = 'dbname=gsb_frais';
   private static $user = 'root';
   private static $mdp = 'root';
   private static $monPdo;
   private static $monPdoGsb = null;
/*
   private static $serveur = 'db681615291.db.1and1.com	';
   private static $bdd = 'dbname=db681615291';
   private static $user = 'dbo681615291';
   private static $mdp = 'Root.85';
   private static $monPdo;
   private static $monPdoGsb = null;
  /* 
   
   
   
   /**
    * Constructeur privé, crée l'instance de PDO qui sera sollicitée
    * pour toutes les méthodes de la classe
    */
   private function __construct() {
      PdoGsb::$monPdo = new PDO(PdoGsb::$serveur . ';' . PdoGsb::$bdd, PdoGsb::$user, PdoGsb::$mdp);
      PdoGsb::$monPdo->query("SET CHARACTER SET utf8");
   }

   public function _destruct() {
      PdoGsb::$monPdo = null;
   }

   /**
    * Fonction statique qui crée l'unique instance de la classe

    * Appel : $instancePdoGsb = PdoGsb::getPdoGsb();

    * @return l'unique objet de la classe PdoGsb
    */
   public static function getPdoGsb() {
      if (PdoGsb::$monPdoGsb == null) {
         PdoGsb::$monPdoGsb = new PdoGsb();
      }
      return PdoGsb::$monPdoGsb;
   }

   /**
    * Hashe le mot de passe dans la BDD lors du login
    * retourne un champ crypté
    * 
    * @param type $login
    * @param type $mdp
    * @return type $crypt
    */
   public static function encrypt($login, $mdp) {
      $crypt = password_hash($mdp, PASSWORD_DEFAULT);
      $req = "UPDATE `utilisateur` SET `mdp` = '$crypt'
            WHERE `utilisateur`.`login` = '$login'";
      PdoGsb::$monPdo->exec($req);
      return $crypt;
   }

   /**
    * Retourne les informations d'un utilisateur

    * @param $login 
    * @param $mdp
    * @return l'id, le nom et le prénom sous la forme d'un tableau associatif 
    */
   public function getInfosUtilisateur($login, $mdp) {

      $req = "select utilisateur.id as id, utilisateur.nom as nom, utilisateur.prenom as prenom, utilisateur.typeconnexion as typeconnexion , typeconnexion.compte as compte from utilisateur 
                INNER JOIN typeconnexion ON typeconnexion.id = utilisateur.typeconnexion
		where utilisateur.login='$login' and utilisateur.mdp='$mdp'";
      $rs = PdoGsb::$monPdo->query($req);
      $ligne = $rs->fetch();
      return $ligne;
   }

   /**
    * Creation d'un Utilisateur
    */
   public function modifierUtilisateur($id, $login, $nom, $prenom, $mdp, $adresse, $cp, $ateEmbauche) {
      $req = "UPDATE utilisateur set id ='$id', login='$login', nom ='$nom', prenom ='$prenom', mdp ='$mdp' , adresse='$adresse', cp='$cp' , dateEmbauche='$dateEmbauche' ";
      PdoGsb::$monPdo->exec($req);
   }

   /**
    * Creation d'un Utilisateur
    */
   public function creationUtilisateur($id, $login, $nom, $prenom, $mdp, $adresse, $cp, $ateEmbauche) {
      $req = "INSERT INTO utilisateur (id ='$id', login='$login', nom ='$nom', prenom ='$prenom', mdp ='$mdp' , adresse='$adresse', cp='$cp' , dateEmbauche='$dateEmbauche') ";
      PdoGsb::$monPdo->exec($req);
   }

    /**
    * Suppression d'un Utilisateur en partant de son Id
    */
   public function supprimerUtilisateur($id) {
      $req = "DELETE FROM utilisateur WHERE(id ='$id'";
      PdoGsb::$monPdo->exec($req);
   }
   
   
   /**
    * Retourne le nom et le prenom d'un utilisateur en fonction de l'id passé en paramètre
    * @param $idUtilisateur
    * @return string le nom et prénom de l'utilisateur concerné
    */
   public function getNomPrenom($idUtilisateur) {
      $req = "select nom , prenom from utilisateur where id = '$idUtilisateur'";
      $res = PdoGsb::$monPdo->query($req);
      $laLigne = $res->fetch();
      return $laLigne;
   }

   /**
    * Retourne les informations du produit passé en argument sous la forme d'un tableau associatif
    * @return un tableau associatif d'une ligne
    */
   public function getIdExist($id) {
      $req = "select count(id) from utilisateur where id='$id'";   //la requête
      $res = PdoGsb::$monPdo->query($req);
      $laLigne = $res->fetch();  //rempli le tableau à l'aide de la methode fecthAll
      return $laLigne;
   }

   /**
    * Retourne sous forme d'un tableau associatif toutes les lignes de frais hors forfait
    * concernées par les deux arguments

    * La boucle foreach ne peut être utilisée ici car on procède
    * à une modification de la structure itérée - transformation du champ date-

    * @param $idUtilisateur 
    * @param $mois sous la forme aaaamm
    * @return tous les champs des lignes de frais hors forfait sous la forme d'un tableau associatif 
    */
   public function getLesFraisHorsForfait($idUtilisateur, $mois) {
      $req = "select * from lignefraishorsforfait where lignefraishorsforfait.idUtilisateur ='$idUtilisateur' 
		and lignefraishorsforfait.mois = '$mois' ";
      $res = PdoGsb::$monPdo->query($req);
      $lesLignes = $res->fetchAll();
      $nbLignes = count($lesLignes);
      for ($i = 0; $i < $nbLignes; $i++) {
         $date = $lesLignes[$i]['date'];
         $lesLignes[$i]['date'] = dateAnglaisVersFrancais($date);
      }
      return $lesLignes;
   }

   /**
    * Retourne le nombre de justificatif d'un utilisateur pour un mois donné

    * @param $idUtilisateur 
    * @param $mois sous la forme aaaamm
    * @return le nombre entier de justificatifs 
    */
   public function getNbjustificatifs($idUtilisateur, $mois) {
      $req = "select fichefrais.nbjustificatifs as nb from  fichefrais where fichefrais.idUtilisateur ='$idUtilisateur' and fichefrais.mois = '$mois'";
      $res = PdoGsb::$monPdo->query($req);
      $laLigne = $res->fetch();
      return $laLigne['nb'];
   }

   /**
    * Retourne sous forme d'un tableau associatif toutes les lignes de frais au forfait
    * concernées par les deux arguments

    * @param $idUtilisateur 
    * @param $mois sous la forme aaaamm
    * @return l'id, le libelle et la quantité sous la forme d'un tableau associatif 
    */
   public function getLesFraisForfait($idUtilisateur, $mois) {
      $req = "select fraisforfait.id as idfrais, fraisforfait.libelle as libelle, 
		lignefraisforfait.quantite as quantite from lignefraisforfait inner join fraisforfait 
		on fraisforfait.id = lignefraisforfait.idfraisforfait
		where lignefraisforfait.idUtilisateur ='$idUtilisateur' and lignefraisforfait.mois='$mois' 
		order by lignefraisforfait.idfraisforfait";
      $res = PdoGsb::$monPdo->query($req);
      $lesLignes = $res->fetchAll();
      return $lesLignes;
   }

   /**
    * Retourne les informations d'une ligne de frais hors forfaits

    * @param $idUtilisateur 
    * @param $mois sous la forme aaaamm
    * @param $idFrais
    * @return tous les champs des lignes de frais hors forfait sous la forme d'un tableau associatif 
    */
   public function getLibelleFraisHorsForfait($idUtilisateur, $mois, $idFrais) {
      $req = "select * from lignefraishorsforfait where lignefraishorsforfait.idUtilisateur ='$idUtilisateur' 
		and lignefraishorsforfait.mois = '$mois' and lignefraishorsforfait.id = '$idFrais'";
      $res = PdoGsb::$monPdo->query($req);
      $laLigne = $res->fetch();
      return $laLigne;
   }

   /**
    * Retourne le nom , prenom des utilisateurs
    * @return un tableau associatif
    */
   public function getLesUtilisateurs() {
      $req = "select id,nom,prenom from utilisateur where typeconnexion='1' order by nom asc";
      $res = PdoGsb::$monPdo->query($req);
      $lignes = $res->fetchAll();
      return $lignes;
   }

   /**
    * Retourne les utilisateurs enregistrés dans la base 
    * @return un tableau associatif
    */
   public function getLesInfosUtilisateurs() {
      $req = "select utilisateur.id as id, utilisateur.login as login , utilisateur.nom as nom, utilisateur.prenom as prenom, utilisateur.adresse as adresse ,"
              . " utilisateur.cp as cp, utilisateur.ville as ville, utilisateur.dateEmbauche as dateEmbauche, typeconnexion.compte as compte from utilisateur "
              . "INNER JOIN typeconnexion ON typeconnexion.id = utilisateur.typeconnexion order by nom ASC";
      $res = PdoGsb::$monPdo->query($req);
      $lignes = $res->fetchAll();
      return $lignes;
   }

   /**
    * Retourne l'id du premier utilisateur classé pas ordre alphabétique
    * @return un tableau associatif
    */
   public function getIdUtilisateur() {
      $req = "select id from utilisateur where typeconnexion='1' order by nom asc";
      $res = PdoGsb::$monPdo->query($req);
      $lignes = $res->fetch();
      return $lignes;
   }
   
   /**
    * Retourne l'id d'un Utilisateur
    * @return un tableau associatif
    */
   public function idUtilisateur($id) {
      $req = "select id from utilisateur where id ='$id";
      $res = PdoGsb::$monPdo->query($req);
      $lignes = $res->fetch();
      return $lignes;
   }
   
   /**
    * Supprime l'id d'un Utilisateur
    * @return un tableau associatif
    */
   public function supprimeIdUtilisateur($id) {
      $req = "delete from utilisateur where id= '$id'";
      $res = PdoGsb::$monPdo->query($req);
      $lignes = $res->fetch();
      return $lignes;
   }
   

   /**
    * Met à jour la table ligneFraisForfait

    * Met à jour la table ligneFraisForfait pour un utilisateur et
    * un mois donné en enregistrant les nouveaux montants

    * @param $idUtilisateur 
    * @param $mois sous la forme aaaamm
    * @param $lesFrais tableau associatif de clé idFrais et de valeur la quantité pour ce frais
    * @return un tableau associatif 
    */
   public function majFraisForfait($idUtilisateur, $mois, $lesFrais) {
      $lesCles = array_keys($lesFrais);
      foreach ($lesCles as $unIdFrais) {
         $qte = $lesFrais[$unIdFrais];
         $req = "update lignefraisforfait set lignefraisforfait.quantite = $qte
			where lignefraisforfait.idUtilisateur = '$idUtilisateur' and lignefraisforfait.mois = '$mois'
			and lignefraisforfait.idfraisforfait = '$unIdFrais'";
         PdoGsb::$monPdo->exec($req);
      }
   }

   /**
    * met à jour le nombre de justificatifs de la table ficheFrais
    * pour le mois et le utilisateur concerné

    * @param $idUtilisateur 
    * @param $mois sous la forme aaaamm
    * @param $nbJustificatifs
    */
   public function majNbJustificatifs($idUtilisateur, $mois, $nbJustificatifs) {
      $req = "update fichefrais set nbjustificatifs = $nbJustificatifs 
		where fichefrais.idUtilisateur = '$idUtilisateur' and fichefrais.mois = '$mois'";
      PdoGsb::$monPdo->exec($req);
   }

   /**
    * Teste si un utilisateur possède une fiche de frais pour le mois passé en argument

    * @param $idUtilisateur 
    * @param $mois sous la forme aaaamm
    * @return vrai ou faux 
    */
   public function estPremierFraisMois($idUtilisateur, $mois) {
      $ok = false;
      $req = "select count(*) as nblignesfrais from fichefrais 
		where fichefrais.mois = '$mois' and fichefrais.idUtilisateur = '$idUtilisateur'";
      $res = PdoGsb::$monPdo->query($req);
      $laLigne = $res->fetch();
      if ($laLigne['nblignesfrais'] == 0) {
         $ok = true;
      }
      return $ok;
   }
 
   /**
    * Ajoute un utilisateur dans la base de données
    * @param  $id
    * @param  $prenom
    * @param  $nom
    * @param  $adresse
    * @param  $cp
    * @param  $ville
    * @return le nombre de ligne affectée par la requête
    */
   public function ajouterUtilisateur($id, $nom, $prenom, $login, $mdp, $adresse, $cp, $ville,$dateEmbauche, $typeconnexion){
      
      $dateFr = dateFrancaisVersAnglais($dateEmbauche);
      $req = "INSERT INTO `utilisateur` (`id`, `nom`, `prenom`, `login`, `mdp`, `adresse`, `cp`, `ville`, `dateEmbauche`, `typeconnexion`)"
              . " VALUES ('$id','$nom', '$prenom', '$login', '$mdp', '$adresse', '$cp', '$ville', '$dateFr ', '$typeconnexion')";
      $nbLigne = PdoGsb::$monPdo->exec($req);
      return $nbLigne;
   }
   
    /**
    * Mise à jour des champs saisit par l'administrateur
    * 
    * @param type $id
    * @param type $nom
    * @param type $prenom
    * @param type $login
    * @param type $mdp
    * @param type $adresse
    * @param type $cp
    * @param type $ville
    * @param type $dateEmbauche
    * @param type $typeconnexion
    */
   public function majUtilisateur($id, $nom, $prenom, $login, $mdp, $adresse, $cp, $ville,$dateEmbauche, $typeconnexion){
      
      $req = "UPDATE utilisateur set  nom = $nom , prenom = $prenom , login = $login, mdp = $mdp,"
              . "adresse = $adresse, cp =$cp, ville = $ville, dateEmbauche = $dateEmbauche, typeconnexion = $typeconnexion where id = $id";
      PdoGsb::$monPdo->exec($req);
   }
   
   /**
    * Retourne le dernier mois en cours d'un utilisateur

    * @param $idUtilisateur 
    * @return le mois sous la forme aaaamm
    */
   public function dernierMoisSaisi($idUtilisateur) {
      $req = "select max(mois) as dernierMois from fichefrais where fichefrais.idUtilisateur = '$idUtilisateur'";
      $res = PdoGsb::$monPdo->query($req);
      $laLigne = $res->fetch();
      $dernierMois = $laLigne['dernierMois'];
      return $dernierMois;
   }

   /**
    * Teste si un utilisateur possède une fiche de frais hors forfait pour le mois passé en paramètre
    * @param string $idUtilisateur 
    * @param $mois sous la forme aaaamm
    * @return vrai ou faux 
    */
   public function estPremierFraisHorsForfaitMois($idUtilisateur, $moisAng) {
      $ok = false;
      $req = "select count(*) as nblignesfrais from lignefraishorsforfait 
		where lignefraishorsforfait.mois = '$moisAng' and lignefraishorsforfait.idUtilisateur = '$idUtilisateur'";
      $res = PdoGsb::$monPdo->query($req);
      $laLigne = $res->fetch();
      if ($laLigne['nblignesfrais'] == 0) {
         $ok = true;
      }
      return $ok;
   }

   /**
    * Crée une nouvelle fiche de frais et les lignes de frais au forfait pour un utilisateur et un mois donnés

    * récupère le dernier mois en cours de traitement, met à 'CL' son champs idEtat, crée une nouvelle fiche de frais
    * avec un idEtat à 'CR' et crée les lignes de frais forfait de quantités nulles 
    * @param $idUtilisateur 
    * @param $mois sous la forme aaaamm
    */
   public function creeNouvellesLignesFrais($idUtilisateur, $mois) {
      $dernierMois = $this->dernierMoisSaisi($idUtilisateur);
      $laDerniereFiche = $this->getLesInfosFicheFrais($idUtilisateur, $dernierMois);
      if ($laDerniereFiche['idEtat'] == 'CR') {
         $this->majEtatFicheFrais($idUtilisateur, $dernierMois, 'CL');
      }
      $req = "insert into fichefrais(idUtilisateur,mois,nbJustificatifs,montantValide,dateModif,idEtat) 
		values('$idUtilisateur','$mois',0,0,now(),'CR')";
      PdoGsb::$monPdo->exec($req);
      $lesIdFrais = $this->getLesIdFrais();
      foreach ($lesIdFrais as $uneLigneIdFrais) {
         $unIdFrais = $uneLigneIdFrais['idfrais'];
         $req = "insert into lignefraisforfait(idUtilisateur,mois,idFraisForfait,quantite) 
			values('$idUtilisateur','$mois','$unIdFrais',0)";
         PdoGsb::$monPdo->exec($req);
      }
   }

   /**
    * Crée un nouveau frais hors forfait pour un utilisateur un mois donné
    * à partir des informations fournies en paramètre

    * @param $idUtilisateur 
    * @param $mois sous la forme aaaamm
    * @param $libelle : le libelle du frais
    * @param $date : la date du frais au format français jj//mm/aaaa
    * @param $montant : le montant
    */
   public function creeNouveauFraisHorsForfait($idUtilisateur, $mois, $libelle, $date, $montant) {
      $dateFr = dateFrancaisVersAnglais($date);
      $req = "insert into lignefraishorsforfait (idUtilisateur, mois,libelle, date, montant)
		values('$idUtilisateur','$mois','$libelle','$dateFr','$montant')";
      PdoGsb::$monPdo->exec($req);
   }

   /**
    * Supprime le frais hors forfait dont l'id est passé en argument

    * @param $idFrais 
    */
   public function supprimerFraisHorsForfait($idFrais) {
      $req = "delete from lignefraishorsforfait where lignefraishorsforfait.id =$idFrais ";
      PdoGsb::$monPdo->exec($req);
   }

   /**
    * Retourne les mois pour lesquel un utilisateur a une fiche de frais

    * @param $idUtilisateur 
    * @return un tableau associatif de clé un mois -aaaamm- et de valeurs l'année et le mois correspondant 
    */
   public function getLesMoisDisponibles($idUtilisateur) {
      $req = "select fichefrais.mois as mois from  fichefrais where fichefrais.idUtilisateur ='$idUtilisateur' 
		order by fichefrais.mois desc ";
      $res = PdoGsb::$monPdo->query($req);
      $lesMois = array();
      $laLigne = $res->fetch();
      while ($laLigne != null) {
         $mois = $laLigne['mois'];
         $numAnnee = substr($mois, 0, 4);
         $numMois = substr($mois, 4, 2);
         $lesMois["$mois"] = array(
             "mois" => "$mois",
             "numAnnee" => "$numAnnee",
             "numMois" => "$numMois"
         );
         $laLigne = $res->fetch();
      }
      return $lesMois;
   }

   public function getLesMoisValide($idUtilisateur) {
      $req = "select fichefrais.mois as mois from  fichefrais where fichefrais.idUtilisateur ='$idUtilisateur' 
		and idEtat = 'VA' order by fichefrais.mois desc ";
      $res = PdoGsb::$monPdo->query($req);
      $lesMois = array();
      $laLigne = $res->fetch();
      while ($laLigne != null) {
         $mois = $laLigne['mois'];
         $numAnnee = substr($mois, 0, 4);
         $numMois = substr($mois, 4, 2);
         $lesMois["$mois"] = array(
             "mois" => "$mois",
             "numAnnee" => "$numAnnee",
             "numMois" => "$numMois"
         );
         $laLigne = $res->fetch();
      }
      return $lesMois;
   }

   /**
    * Retourne les informations d'une fiche de frais d'un utilisateur pour un mois donné

    * @param $idUtilisateur 
    * @param $mois sous la forme aaaamm
    * @return un tableau avec des champs de jointure entre une fiche de frais et la ligne d'état 
    */
   public function getLesInfosFicheFrais($idUtilisateur, $mois) {
      $req = "select ficheFrais.idEtat as idEtat, ficheFrais.dateModif as dateModif, ficheFrais.nbJustificatifs as nbJustificatifs, 
			ficheFrais.montantValide as montantValide, etat.libelle as libEtat from  fichefrais inner join Etat on ficheFrais.idEtat = Etat.id 
			where fichefrais.idUtilisateur ='$idUtilisateur' and fichefrais.mois = '$mois'";
      $res = PdoGsb::$monPdo->query($req);
      $laLigne = $res->fetch();
      return $laLigne;
   }

   /**
    *  Ajoute le texte REFUSE en début de libelle d'un frais hors forfait
    * @param $idFrais
    */
   public function ajouteRefuse($idFrais) {
      $requ = "select libelle from lignefraishorsforfait where lignefraishorsforfait.id = $idFrais";
      $res = PdoGsb::$monPdo->query($requ);
      $Ligne = $res->fetch();
      $Ligne = "REFUSE : " . $Ligne['libelle'] . "";
      $Ligne = substr($Ligne, 0, 100);
      $req = "update lignefraishorsforfait set libelle ='$Ligne' where id = $idFrais ";
      PdoGsb::$monPdo->exec($req);
   }

   /**
    * Modifie l'état et la date de modification d'une fiche de frais
    * Modifie le champ idEtat et met la date de modif à aujourd'hui
    * @param $idUtilisateur 
    * @param $mois sous la forme aaaamm
    */
   public function majEtatFicheFrais($idUtilisateur, $mois, $etat) {
      $req = "update ficheFrais set idEtat = '$etat', dateModif = now() 
		where fichefrais.idUtilisateur ='$idUtilisateur' and fichefrais.mois = '$mois'";
      PdoGsb::$monPdo->exec($req);
   }

   /**
    * Modifie le montant validé et la date de modification d'une fiche de frais
    * Modifie le champ montantValide et met la date de modif à aujourd'hui
    * @param $idUtilisateur 
    * @param $mois sous la forme aaaamm
    * @param $montant
    */
   public function majMontantValide($idUtilisateur, $mois, $montant) {
      $req = "update fichefrais set  dateModif = now(), montantValide='$montant' 
		where fichefrais.idUtilisateur ='$idUtilisateur' and fichefrais.mois = '$mois'";
      PdoGsb::$monPdo->exec($req);
   }
   
   
   
   
   
  

}
?>
