<?php

require('parametres.php');
require('fpdf.php');

class PDF extends FPDF {

   function Header() {
      /*
        $this->SetMargins(10, 10,10);
        // En-tête
        // Logo
        $this->Image('LOGO-GSB.png', 10, 6, 60);
        // Police Arial gras 15
        $this->SetFont('Arial', 'B', 15);
        // Décalage à droite
        $this->Cell(100);
        // Titre
        $this->Cell(30, 30, 'ETAT DE FRAIS ENGAGES', 0, 0, 'C');
        $this->SetDrawColor(119, 181, 254);
        // Saut de ligne
        $this->Ln(5);
       */
      $this->Image('LOGO-GSB.png', 90, 6, 30);
   }

   // ___________ En-tête Fiche Frais (Titre, Visiteur et Mois)___________
   function enteteFicheFrais($connect, $choixMois, $choixVisiteur) {
      try {

         $connect = new PDO('mysql:host=localhost' . ';' . 'dbname=gsb_frais', 'root', 'root');
      } catch (PDOException $e) {
         die('Erreur de connection : ' . $e->getMessage());
      }
      // Couleur du texte: Bleu et police: Times gras "B" de taille 15
      $this->SetTextColor(31, 73, 125);
      $this->SetFont('Times', 'B', 15);
      // Saut de ligne "Ln" + Décalage à droite de la cellule "Cell(10)" + Texte centré "C" + Saut de ligne
      $this->Ln(30);
      $this->Cell(10);
      $this->Cell(170, 10, utf8_decode('REMBOURSEMENT DE FRAIS ENGAGÉS'), 0, 0, 'C');
      
      // Requete pour récupérer le nom prénom du visiteur dans la table Utilisateur
      // et les valeurs de la fiche de frais en joignant la table FicheFrais 
      $idJeuFicheDeFrais = $connect->query(
              'select nom, prenom from Utilisateur join FicheFrais on id = idUtilisateur where id="' . $choixVisiteur . '" and mois="' . $choixMois . '";');
      $lgFicheFrais = $idJeuFicheDeFrais->fetch(); // $lgFicheFrais = $mysql_fetch_array($idJeuFicheDeFrais)
      $idJeuFicheDeFrais->closeCursor(); // ferme le curseur precedent
      $this->Ln(15);
      $this->Cell(10);
      $this->SetTextColor(0, 0, 0);
      $this->SetFont('Times', '', 12);

      // Infos visiteur id + nom et prenom
      $this->Cell(50, 7, "Visiteur", 0);
      $this->Cell(40, 7, $choixVisiteur, 0);
      $this->Cell(80, 7, utf8_decode($lgFicheFrais['prenom']) . " " . strtoupper(utf8_decode($lgFicheFrais['nom'])), 0);
      $this->Ln(10);
      $this->Cell(10);
      // Infos Mois 
      $this->Cell(50, 7, "Mois", 0);
      $mois = intval(substr($choixMois, 4, 2));
      $annee = intval(substr($choixMois, 0, 4));
      
      // la fonction obtenirLibelleMois() dans "include/_utilitairesEtGestionErreurs.lib.php"
      //$this->Cell(40, 7, obtenirLibelleMois($mois) . ' ' . $annee, 0);
   }

   // ____________Tableau Frais Forfait___________
   function tabFraisForfaits($connect, $choixMois, $choixVisiteur) {
      // Entêtes de colonnes
      $this->Ln(15);
      $this->Cell(10);
      $this->SetTextColor(31, 73, 125);
      $this->SetFont('Times', 'BI', 12); // "BI" gras et italique
      $this->SetFillColor(255, 255, 255); // SetFillColor: définit la couleur de remplissage
      $this->Cell(50, 7, 'Frais forfaitaires', 'LTB', 0, 'C', true); //"LTB" bords tracés sur le coté gauche, haut, bas
      $this->Cell(40, 7, utf8_decode('Quantité'), 'TB', 0, 'C', true); //"0" indique que le texte suivant doit etre à droite après celui ci
      $this->Cell(40, 7, 'Montant unitaire', 'TB', 0, 'C', true); //"true": fond coloré; "false": fond transparent
      $this->Cell(40, 7, 'Total', 'TRB', 0, 'C', true); //"TRB" bords tracés sur le coté haut, droite, bas 
      //
      // Données 
      $this->Ln();
      $this->SetTextColor(0, 0, 0);
      $this->SetFont('Times', '', 12);

      // requete pour récupérer les valeurs du libelle, quantite de la table LigneFraisForfait
      // ainsi que le montant, le total (quantite*montant) grace à la jointure avec la table FraisForfait
      $idJeuFraisForfait = $connect->query("select libelle, quantite, montant,"
              . " (quantite*montant) as total from LigneFraisForfait inner join FraisForfait on FraisForfait.id = LigneFraisForfait.idFraisForfait where idUtilisateur='"
              . $choixVisiteur . "' and mois='" . $choixMois . "'");

      while ($lgFraisForfait = $idJeuFraisForfait->fetch()) {
         $this->Cell(10);
         $this->Cell(50, 7, $lgFraisForfait['libelle'], 1, 0, 'L', true); //"1" pour tracé un cadre
         $this->Cell(40, 7, $lgFraisForfait['quantite'], 1, 0, 'R', true);
         $this->Cell(40, 7, $lgFraisForfait['montant'], 1, 0, 'R', true);
         $this->Cell(40, 7, $lgFraisForfait['total'], 1, 0, 'R', true);
         $this->Ln();
      }
      $idJeuFraisForfait->closeCursor();
   }

   function Footer() {
      // Positionnement à 1,5 cm du bas
      $this->SetY(-15);
      $this->SetFont('Times', 'I', 8);
      // Numérotation des pages
      $this->Cell(0, 10, 'Page ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
   }

   // ____________Tableau Frais Hors Forfait___________
   function tabFraisHorsForfaits($connect, $choixMois, $choixVisiteur) {
      $this->Ln(5);
      $this->Cell(10);
      $this->SetTextColor(31, 73, 125);
      $this->SetFont('Times', 'BI', 12);
      $this->Cell(170, 10, 'Autres frais', 0, 0, 'C');
      // Entêtes de colonnes
      $this->Ln(10);
      $this->Cell(10);
      $this->SetTextColor(31, 73, 125);
      $this->SetFont('Times', 'BI', 12);
      $this->SetFillColor(255, 255, 255);
      $this->Cell(50, 7, 'Date', 'LTB', 0, 'C', true);
      $this->Cell(80, 7, utf8_decode('Libellé'), 'TB', 0, 'C', true);
      $this->Cell(40, 7, 'Montant', 'TRB', 0, 'C', true);
      // Données
      $this->Ln();
      $this->SetTextColor(0, 0, 0);
      $this->SetFont('Times', '', 12);
      // requete pour récupérer les valeurs de id, date, libelle, montant de la table LigneFraisHorsForfait
      $idJeuFraisHorsForfait = $connect->query("select id, date, libelle, montant from LigneFraisHorsForfait where idUtilisateur='" .
              $choixVisiteur . "' and mois='" . $choixMois . "'");
      
      
      while ($lgFraisHorsForfait = $idJeuFraisHorsForfait->fetch()) {
         $this->Cell(10);
         // la fonction "convertirDateAnglaisVersFrancais()" dans "include/_utilitairesEtGestionErreurs.lib.php"
         // permet de modifier une date de type Anglais(annee/mois/jour) en type Français(jour/mois/annee)
         $this->Cell(50, 7, convertirDateAnglaisVersFrancais($lgFraisHorsForfait['date']), 1, 0, 'L', true);
         $this->Cell(80, 7, $lgFraisHorsForfait['libelle'], 1, 0, 'L', true);
         $this->Cell(40, 7, $lgFraisHorsForfait['montant'], 1, 0, 'R', true);
         $this->Ln();
      }
      $idJeuFraisHorsForfait->closeCursor();
   }

   // ___________Afficher Total Fiche Frais + Date____________
   function afficheTotal($connect, $choixMois, $choixVisiteur) {
      $this->Ln();
      $this->Cell(100);
      // la requete récupère le montant total de la fiche de frais dans la table FicheFrais
      $idJeuFicheFrais = $connect->query("select montantValide from FicheFrais where idUtilisateur='" . $choixVisiteur . "' and mois='" . $choixMois . "'");
      $lgFicheFrais = $idJeuFicheFrais->fetch();
      $idJeuFicheFrais->closeCursor();
      $mois = intval(substr($choixMois, 4, 2));
      $annee = intval(substr($choixMois, 0, 4));
      $this->Cell(40, 7, 'TOTAL ' . $mois . '/' . $annee, 1, 0, 'L', true);
      $this->Cell(40, 7, $lgFicheFrais['montantValide'], 1, 0, 'R', true);
   }

   // ____________Afficher Signature + Date____________
   function afficheSignature($connect, $choixMois, $choixVisiteur) {
      $this->Ln(20);
      $this->Cell(100);
      $idJeuFicheFrais = $connect->query("select dateModif from FicheFrais where idUtilisateur='" . $choixVisiteur . "' and mois='" . $choixMois . "'");
      $lgFicheFrais = $idJeuFicheFrais->fetch();
      $idJeuFicheFrais->closeCursor();
      $jour = intval(substr($lgFicheFrais['dateModif'], 8, 2));
      $mois = intval(substr($lgFicheFrais['dateModif'], 5, 2));
      $annee = intval(substr($lgFicheFrais['dateModif'], 0, 4));
   //   $this->Cell(80, 7, utf8_decode('Fait à LYON, le ' . $jour . ' ' . obtenirLibelleMois($mois) . ' ' . $annee), 0, 0, 'L', true);
      $this->Ln(10);
      $this->Cell(100);
      $this->Cell(80, 7, 'Vu l\'agent comptable', 0, 0, 'L', true);
      $this->Ln(10);
      $this->Cell(100);
      $this->Image('../images/signatureComptable.png', null, null, 70);
   }

   // ______________Affiche Les differents éléments de la fiche de frais___________
   function afficheFicheFrais($choixMois, $choixVisiteur) {
      $this->AliasNbPages(); //définit un alias pour le nombre de pages "Page ../.."
      $this->AddPage(); //ajoute une nouvelle page "Page 1/1"
      $this->SetFont('Times', '', 12);
      

      // Connexion à la BDD en PDO
      try {

         $connect = new PDO('mysql:host=localhost' . ';' . 'dbname=gsb_frais', 'root', 'root');
      } catch (PDOException $e) {
         die('Erreur de connection : ' . $e->getMessage());
      }

      // Affichage de l'entête de la fiche de frais
      $this->enteteFicheFrais($connect, $choixMois, $choixVisiteur);
      // Affichage des frais forfaitisés
      $this->tabFraisForfaits($connect, $choixMois, $choixVisiteur);
      // Affichage des frais hors forfaits
      $this->tabFraisHorsForfaits($connect, $choixMois, $choixVisiteur);
      // Affichage du total
      $this->afficheTotal($connect, $choixMois, $choixVisiteur);
      // Affichage de la date et de la signature du document
      $this->afficheSignature($connect, $choixMois, $choixVisiteur);
   }

}

//________Récupération des valeurs des formulaires de cValideFichesFrais.php________ 
$choixMois = lireDonneePost("lstMois", "");
$choixVisiteur = lireDonneePost("lstVisiteur", "");

  
//__________Création du fichier PDF___________
// Si le fichier PDF n'existe pas encore, on le génère
if (!file_exists($fichier)) {
   // Instanciation de la classe dérivée
   $pdf = new PDF();
   $pdf->afficheFicheFrais($choixMois, $choixVisiteur);
   $pdf->Output($fichier); //le pdf est mis dans le fichier "pdf" du serveur
}

//_________Téléchargement du PDF____________
$idJeuFicheFrais = $connect->query('select nom, prenom from Utilisateur join FicheFrais on id = idUtilisateur where id="' . $choixVisiteur . '" and mois="' . $choixMois . '";');
$lgFicheFrais = $idJeuFicheFrais->fetch();
$idJeuFicheFrais->closeCursor();
$mois = intval(substr($choixMois, 4, 2));
$annee = intval(substr($choixMois, 0, 4));
header('Content-Type: application/x-download');
header('Content-Disposition: inline; filename="Fiche_de_frais_' . utf8_decode($lgFicheFrais['prenom']) . '_' . strtoupper(utf8_decode($lgFicheFrais['nom'])) . '_' . obtenirLibelleMois($mois) . '_' . $annee . '.pdf');
header('Cache-Control: private, max-age=0, must-revalidate');
header('Pragma: public');
readfile($fichier);

$pdf->Output("Facture.pdf");
$connect = null;

?>

/*
  // Chargement des données
  function LoadData($file) {
  // Lecture des lignes du fichier
  $lines = file($file);
  $data = array();
  foreach ($lines as $line)
  $data[] = explode(';', trim($line));
  return $data;
  }

  // Tableau simple
  function BasicTable($header, $data) {
  // En-tête
  foreach ($header as $col)
  $this->Cell(40, 6, $col, 1);
  $this->Ln();

  // Données
  foreach ($data as $row) {
  foreach ($row as $col)
  $this->Cell(40, 6, $col, 1);
  $this->Ln();
  }
  }


  }

  $pdf = new PDF();





  // Sous-Titre1
  $pdf->AddPage();
  $pdf->Cell(1);
  $pdf->SetFont('Arial', 'B', 10);
  $pdf->SetTextColor(0, 51, 102);
  $pdf->Cell(180, 250, 'Autres frais', 0, 0, 'C');



  // Titres des colonnes
  $header = array('Frais Forfaitaires', 'Quantité', ' Montant unitaire', 'Total');
  // Chargement des données
  $data = $pdf->LoadData('forfait.txt');
  $pdf->AddPage();
  $pdf->SetFont('Arial', '', 14);
  $pdf->SetTextColor(0, 51, 102);



  $pdf->BasicTable($header, $data);
  $pdf->Cell(0, 0, 'A retourner accompagné des justificatifs', 0, 0, 'C');
  $pdf->Cell(0, 10, 'au plus tard le 10 du mois qui suit l’engagement des frais', 0, 0, 'C');
  $pdf->Ln(40);

  $data = $pdf->LoadData('horsforfait.txt');
  $pdf->SetFont('Arial', '', 14);
  $pdf->BasicTable($header, $data);
  $pdf->Ln(40);



  $pdf->Output("Facture", "I");
 */
//fermeture de la connexion

