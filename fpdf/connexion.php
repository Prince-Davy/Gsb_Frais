<?php

session_start();




/*
  $serveur = 'mysql:host=localhost';
  $bdd = 'dbname=gsb_frais';
  $user = 'root';
  $mdp = 'root';
 */

//Connexion à la base de données Gsb_Frais
$connexion = new PDO("mysql:host=localhost;dbname=gsb_frais", "root", "root");
$connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// --- Si on utilise ceci il faut utiliser utf8_decode 
// --- pour afficher plus bas les caractères accentues
$connexion->exec("SET NAMES 'UTF8'");

//Requete Sql
$query = "SELECT * from utilisateur";
$result = $connexion->prepare($query);
$result->execute();


  //traitement
  $i = 0;
  echo $_SESSION['idUtilisateur']."</br>";
  
  while ($données = $result->fetch()) {
  echo "Colonne n°:" . $i . "</br>";
  echo "id : " . $données['id'] . ' ';
  ;
  echo "nom : " . $données['nom'] . ' ';
  ;
  echo "prenom : " . $données['prenom'] . ' ';
  ;
  echo "login : " . $données['login'] . ' ';
  ;
  echo "</br></br>";
  $i++;
  }
   
 $connexion = null;
?>