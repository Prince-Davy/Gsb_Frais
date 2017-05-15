<?php

$id = $unutilisateur['id'];
$login = $unutilisateur['login'];
$nom = $unutilisateur['nom'];
$prenom = $unutilisateur['prenom'];
$compte = $unutilisateur['compte'];


$requete = $pdo->prepare("INSERT INTO employe(NOM , PRENOM , EMAIL, PHOTO) VALUES(?,?,?,?)");
$params = array($nom, $prenom, $email, $nomPhoto);
$requete->execute($params);

header("location:employe.php");
?>