<?php
include('fpdf1.7/fpdf.php');
include("jpgraph-3.5.0b1/src/jpgraph.php");
include("jpgraph-3.5.0b1/src/jpgraph_bar.php");

$pdf = new FPDF();

   $pdf->AddPage();
   $pdf->SetFont('Courier','',12);

   $pdf->SetDrawColor(0,0,0);
   $pdf->SetFillColor(199,199,199);
   $pdf->SetTextColor(0,0,0);

   try {
      $connexion = new PDO("mysql:host=localhost;dbname=france;port=3306", "root", "");
      $connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      // --- Si on utilise ceci il faut utiliser utf8_decode 
      // --- pour afficher plus bas les caractères accentues
      $connexion->exec("SET NAMES 'UTF8'");

      $query = "SELECT v.id as idVille, d.id as idDepartement, commune, CP, departement, nom, habitant FROM villes as v LEFT JOIN departements as d ON d.id = v.departement";
      $result = $connexion->prepare($query);
      $result->execute();
      
      $donnees = array(); //-- Tableau de données pour la génération du graphique
      $etiquettes = array(); //-- Tableayu de étiquette pour la génération du graphique
      
      $departements = array();
      
      while($row = $result->fetch(PDO::FETCH_OBJ)){
          $departements[$row->idDepartement]['idDepartement'] = $row->idDepartement;
          $departements[$row->idDepartement]['nom'] = $row->nom;
          $departements[$row->idDepartement]['villes'][$row->idVille]['idVille'] = $row->idVille;
          $departements[$row->idDepartement]['villes'][$row->idVille]['commune'] = $row->commune;
          $departements[$row->idDepartement]['villes'][$row->idVille]['CP'] = $row->CP;
          $departements[$row->idDepartement]['villes'][$row->idVille]['habitant'] = $row->habitant;
          if(!isset($departements[$row->idDepartement]['totalHabitant'])){
              $departements[$row->idDepartement]['totalHabitant'] = 0;
          }
          $departements[$row->idDepartement]['totalHabitant'] += $row->habitant;
      }
      
      // --- Cell(largeur, hauteur, texte, bord, placement, alignement, remplissage, lien)
      foreach($departements as $idDepartement => $departement) {
         $pdf->Cell(190, 5, $departement['nom'], 0 , 1, 'C', 0);
         $pdf->Cell(100, 5, "NOM DE LA VILLE", 1, 0, 'C', 1);
         $pdf->Cell(30, 5, "Code postal", 1, 0, 'C', 1);
         $pdf->Cell(30, 5, "Habitants", 1, 1, 'C', 1);
         foreach($departement['villes'] as $idVille => $ville){
            $pdf->Cell(100, 5, utf8_decode($ville['commune']), 1 , 0, 'L', 0);
            $pdf->Cell(30, 5, utf8_decode($ville['CP']), 1 , 0, 'L', 0);
            $pdf->Cell(30, 5,$ville['habitant'], 1 , 1, 'L', 0);
        }
        $pdf->Cell(190, 5, "Total habitants : ".$departement['totalHabitant'], 1, 1, 'R', 1);
        $pdf->Ln(5);
      }
      
      /* Création du graphique */
      foreach($departements as $idDepartement => $departement){
          //var_dump($departement['totalHabitant']);
          array_push($donnees, $departement['totalHabitant']);
          array_push($etiquettes, $departement['nom']);
      }
      
      // --- Cree le conteneur du graphique
      $graphe = new Graph(500,400,"auto");
      $graphe->SetScale("textlin");

      $graphe->SetShadow();
      $graphe->img->SetMargin(70,30,20,100);

      // --- Cree un histogramme
      $histo = new BarPlot($donnees);
      $histo->SetFillColor("orange");

      // --- Les valeurs sur les batons
      $histo->value->SetFormat('%0.2f');
      $histo->value->Show();

      // --- Le titre
      $graphe->title->Set(utf8_decode("Habitants par départements"));
      $graphe->title->SetFont(FF_FONT1,FS_BOLD);

      // --- Les axes
      // --- Toutes les polices ne fonctionnent pas. FF_ARIAL est OK
      $graphe->xaxis->SetFont(FF_ARIAL,FS_NORMAL,8);
      $graphe->xaxis->SetLabelAngle(45);
      $graphe->xaxis->SetTickLabels($etiquettes);
      $graphe->xaxis->SetTitleMargin(50);
      $graphe->xaxis->title->Set(utf8_decode("Départements"));
      $graphe->xaxis->title->SetFont(FF_FONT1,FS_BOLD);

      $graphe->yaxis->SetTitleMargin(50);
      $graphe->yaxis->title->Set(utf8_decode("Habitants par département"));
      $graphe->yaxis->title->SetFont(FF_FONT1,FS_BOLD);

      // --- Ajoute le tout au graphe
      $graphe->Add($histo);

      // --- Enregistrement de limage sur le disque
      $graphe->Stroke('departement.png');
      
      /* fin de création du graphique */
      
      // --- Redirection vers le navigateur
      $pdf->Image("departement.png",50, 150);
      $pdf->Output();
      unlink('departement.png');

      // --- Redirection vers le disque
      //$pdf->Output("villes.pdf");
      //echo "Fichier cr&eacute;&eacute; sur le disque";
   }

   catch(PDOException $e) {
      echo "Echec de l'exécution : " . $e->getMessage();
   }

   $lcn = null;
