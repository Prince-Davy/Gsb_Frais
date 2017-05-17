 Programme d'actualisation des lignes des tables,  
cette mise à jour peut prendre plusieurs minutes...
<?php
include("include/fct.inc.php");

/* Modification des paramètres de connexion */

$serveur = 'mysql:host=localhost';
$bdd = 'dbname=gsb_frais';
$user = 'root';
$mdp = 'root';

/*
$serveur = 'mysql:host=db681615291.db.1and1.com	';
$bdd = 'dbname=db681615291';
$user = 'dbo681615291';
$mdp = 'Root.85';
*/
/* fin paramètres */

try {
   $pdo = new PDO($serveur . ';' . $bdd, $user, $mdp);
   $pdo->query("SET CHARACTER SET utf8");
} catch (PDOException $e) {
   echo "Connection failed: " . $e->getMessage();
}
set_time_limit(0);
creationFichesFrais($pdo);
creationFraisForfait($pdo);
creationFraisHorsForfait($pdo);
majFicheFrais($pdo);
?>