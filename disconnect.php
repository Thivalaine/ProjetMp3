<!-- il s'agit ici de se deconnecter de sa session, c'est un fichier issu d'un include faite à partir du fichier "landing.php" -->

<?php 
    session_start();
    $_SESSION = array();
    session_destroy();
    unset($_SESSION);
    // on redirige une fois la session fermer, l'utilisateur vers la page de connexion "connexion.php"
    header('Location:index.php');
    die();
