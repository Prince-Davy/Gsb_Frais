<?php
// Démarrage de la gestion des sessions PHP
session_start();

// Inclure les fonctions (fct.inc.php) et les classes (class.pdogsb.inc.php)
require_once("include/fct.inc.php");
require_once ("include/class.pdogsb.inc.php");

include("vues/v_entete.php") ;

// Connexion à la base de données GSB
$pdo = PdoGsb::getPdoGsb();

// L'utilisateur est-il connecté au site ?
$estConnecte = estConnecte();

// Si l'utilisateur n'est pas connecté , affiche la vue connexion
if(!isset($_REQUEST['uc']) || !$estConnecte){
     $_REQUEST['uc'] = 'connexion';
}	

$uc = $_REQUEST['uc'];
switch($uc){
	case 'connexion':{
		include("controleurs/c_connexion.php");break;
	}
	case 'gererFrais' :{
		include("controleurs/c_gererFrais.php");break;
	}
	case 'etatFrais' :{
		include("controleurs/c_etatFrais.php");break; 
	}
}
include("vues/v_pied.php") ;
?>

