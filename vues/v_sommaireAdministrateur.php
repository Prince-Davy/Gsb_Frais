    <!-- Division pour le sommaire -->
<div id="menuGauche">
    <div id="infosUtil">

        <h2>

        </h2>

    </div>  
    <ul id="menuList">
        <li >
            Administrateur :<br>
            <?php echo $_SESSION['prenom'] . "  " . $_SESSION['nom'] ?>
        </li>
        <li class="smenu">
            <a href="index.php?uc=utilisateur&action=listeUtilisateur" title="Consultation liste des utilisateurs">Utilisateurs Enregistrés</a>
        </li>
        <li class="smenu">
            <a href="index.php?uc=utilisateur&action=creationUtilisateur" title="Creation d'un Utilisateur">Creation Utilisateurs</a>
        </li>
        <li class="smenu">
            <a href="index.php?uc=utilisateur&action=supprimerUtilisateur" title="Suppression d'un Utilisateur">Suppression Utilisateurs</a>
        </li>
        <li class="smenu">
            <a href="index.php?uc=connexion&action=deconnexion" title="Se déconnecter">Déconnexion</a>
        </li>
    </ul>
</div>
