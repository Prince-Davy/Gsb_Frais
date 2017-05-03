<?php

session_start();
require('fpdf.php');
require('../include/fct.inc.php');

class PDF extends FPDF {

   //Entete de Page
   function Header() {
      // ___________Logo____________
      $this->Image('../images/LOGO-GSB.png', 90, 6, 30);
      // Police Arial gras 15
      $this->SetFont('Arial', 'B', 15);
      // Décalage à droite
      $this->Cell(80);
      // Saut de ligne
      $this->Ln(20);
   }

   // Pied de page
   function Footer() {
      // Positionnement à 1,5 cm du bas
      $this->SetY(-15);
      // Police Arial italique 8
      $this->SetFont('Arial', 'I', 8);
      // Numéro de page
      $this->Cell(0, 10, 'Page ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
   }

   // ___________ En-tête Fiche Frais (Titre, Visiteur et Mois)___________
   function enteteFicheFrais() {
      // Couleur du texte: Bleu et police: Times gras "B" de taille 15
      $this->SetTextColor(31, 73, 125);
      $this->SetFont('Times', 'B', 15);

      // requete pour récupérer les valeurs du libelle, quantite de la table LigneFraisForfait
      // ainsi que le montant, le total (quantite*montant) grace à la jointure avec la table FraisForfait
      //Connexion à la base de données Gsb_Frais
      $connexion = new PDO("mysql:host=localhost;dbname=gsb_frais", "root", "root");
      $connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      // --- Si on utilise ceci il faut utiliser utf8_decode 
      // --- pour afficher plus bas les caractères accentues
      $connexion->exec("SET NAMES 'UTF8'");

      //Requete Sql
      $query = "select libelle, quantite, montant, (quantite*montant) as total from LigneFraisForfait"
              . " inner join FraisForfait on FraisForfait.id = LigneFraisForfait.idFraisForfait"
              . " where idUtilisateur='" . $_SESSION['idUtilisateur'] . "'  and mois='" . 201702 . "' ";
      $result = $connexion->prepare($query);
      $result->execute();


      // Saut de ligne "Ln" + Décalage à droite de la cellule "Cell(10)" + Texte centré "C" + Saut de ligne
      $this->Ln(30);
      $this->Cell(10);
      $this->Cell(170, 10, utf8_decode('REMBOURSEMENT DE FRAIS ENGAGÉS'), 0, 0, 'C');
      $this->Ln(15);
      $this->Cell(10);
      $this->SetTextColor(0, 0, 0);
      $this->SetFont('Times', '', 12);

      // Infos visiteur id + nom et prenom
      $this->Cell(50, 7, "Visiteur", 0);
      $this->Cell(40, 7, $_SESSION['idUtilisateur'], 0);
      $this->Cell(80, 7, strtoupper($_SESSION['prenom']) . "          " . strtoupper($_SESSION['nom']), 0);
      $this->Ln(10);
      $this->Cell(10);
      // Infos Mois 
      $this->Cell(50, 7, "Mois", 0);
   }

   function tabFraisForfaits() {
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
      // Données 
      $this->Ln();
      $this->SetTextColor(0, 0, 0);
      $this->SetFont('Times', '', 12);

      // requete pour récupérer les valeurs du libelle, quantite de la table LigneFraisForfait
      // ainsi que le montant, le total (quantite*montant) grace à la jointure avec la table FraisForfait
      //Connexion à la base de données Gsb_Frais
      $connexion = new PDO("mysql:host=localhost;dbname=gsb_frais", "root", "root");
      $connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      // --- Si on utilise ceci il faut utiliser utf8_decode 
      // --- pour afficher plus bas les caractères accentues
      $connexion->exec("SET NAMES 'UTF8'");

      //Requete Sql
      $query = "select libelle, quantite, montant, (quantite*montant) as total from LigneFraisForfait"
              . " inner join FraisForfait on FraisForfait.id = LigneFraisForfait.idFraisForfait"
              . " where idUtilisateur='" . $_SESSION['idUtilisateur'] . "'  and mois='" . 201702 . "' ";
      $result = $connexion->prepare($query);
      $result->execute();

      while ($données = $result->fetch()) {
         $this->Cell(10);
         $this->Cell(50, 7, $données['libelle'], 1, 0, 'L', true); //"1" pour tracé un cadre
         $this->Cell(40, 7, $données['quantite'], 1, 0, 'R', true);
         $this->Cell(40, 7, $données['montant'], 1, 0, 'R', true);
         $this->Cell(40, 7, $données['total'], 1, 0, 'R', true);
         $this->Ln();
      }
      //Fermeture de la connexion
      $connexion = null;
   }

   // ____________Tableau Frais Hors Forfait___________
   function tabFraisHorsForfaits() {
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
      $connexion = new PDO("mysql:host=localhost;dbname=gsb_frais", "root", "root");
      $connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      // --- Si on utilise ceci il faut utiliser utf8_decode 
      // --- pour afficher plus bas les caractères accentues
      $connexion->exec("SET NAMES 'UTF8'");

      //Requete Sql
      $query = "select id, date, libelle, montant from LigneFraisHorsForfait where idUtilisateur='" . $_SESSION['idUtilisateur'] . "'  and mois='" . 201702 . "'";
      $result = $connexion->prepare($query);
      $result->execute();

      while ($données = $result->fetch()) {
         $this->Cell(10);
         // la fonction "convertirDateAnglaisVersFrancais()" dans "include/_utilitairesEtGestionErreurs.lib.php"
         // permet de modifier une date de type Anglais(annee/mois/jour) en type Français(jour/mois/annee)
         $this->Cell(50, 7, dateAnglaisVersFrancais(201702), 1, 0, 'L', true);
         $this->Cell(80, 7, $données['libelle'], 1, 0, 'L', true);
         $this->Cell(40, 7, $données['montant'], 1, 0, 'R', true);
         $this->Ln();
      }
      //Fermeture de la connexion
      $connexion = null;
   }

   // ___________Afficher Total Fiche Frais + Date____________
   function afficheTotal() {
      $this->Ln();
      $this->Cell(100);

      // la requete récupère le montant total de la fiche de frais dans la table FicheFrais
      // requete pour récupérer les valeurs de id, date, libelle, montant de la table LigneFraisHorsForfait
      $connexion = new PDO("mysql:host=localhost;dbname=gsb_frais", "root", "root");
      $connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      // --- Si on utilise ceci il faut utiliser utf8_decode 
      // --- pour afficher plus bas les caractères accentues
      $connexion->exec("SET NAMES 'UTF8'");

      //Requete Sql
      $query = "select montantValide from FicheFrais where idUtilisateur='" . $_SESSION['idUtilisateur'] . "'  and mois='" . 201702 . "'";
      $result = $connexion->prepare($query);
      $result->execute();
      $données = $result->fetch();

      //$mois = intval(substr($choixMois, 4, 2));
      //$annee = intval(substr($choixMois, 0, 4));
      $this->Cell(40, 7, 'TOTAL ', 1, 0, 'L', true);
      $this->Cell(40, 7, 1, 0, 'R', true);
   }

   // ____________Afficher Signature + Date____________
   function afficheSignature() {
      $this->Ln(20);
      $this->Cell(100);

      //$this->Cell(80, 7, utf8_decode('Fait à LYON, le ' . '$jour '. ' ' . obtenirLibelleMois($mois) . ' ' . '$annee)'', 0, 0, 'L', true);
      $this->Ln(10);
      $this->Cell(100);
      $this->Cell(50, 7, 'Vu par l\'agent comptable', 0, 0, 'L', true);
      $this->Cell(0, 7, "Prince-Davy N'kakou", 0, 0, 'L', true);
      $this->Ln(10);
      $this->Cell(100);
      $this->Image('../images/signatureComptable.png', null, null, 70);
   }

}

// Instanciation de la classe dérivée -- Creation PDF
$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->enteteFicheFrais();
$pdf->tabFraisForfaits();
$pdf->tabFraisHorsForfaits();
$pdf->afficheTotal();
$pdf->afficheSignature();
$pdf->SetFont('Times', '', 12);

$pdf->Output();
?>

