    <!-- Division pour le sommaire -->
<div id="menuGauche">
    <div id="infosUtil">

        <h2>

        </h2>

    </div>  
    <ul id="menuList">
        <li >
            Comptable :<br>
            <?php echo $_SESSION['prenom'] . "  " . $_SESSION['nom'] ?>
        </li>
        <li class="smenu">
            <a href="index.php?uc=listeUtilisateurs&action=liste" title="Consultation liste des utilisateurs">Utilisateurs Inscrits</a>
        </li>
        <li class="smenu">
            <a href="index.php?uc=listeUtilisateurs=creationUtilisateur" title="Creation d'un Utilisateur">Creation Utilisateurs</a>
        </li>
        <li class="smenu">
            <a href="index.php?uc=listeUtilisateurs=supprimerUtilisateur" title="Suppression d'un Utilisateur">Suppression Utilisateurs</a>
        </li>
        <li class="smenu">
            <a href="index.php?uc=connexion&action=deconnexion" title="Se déconnecter">Déconnexion</a>
        </li>
    </ul>
</div>
