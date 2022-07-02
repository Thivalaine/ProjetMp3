<?php

session_start();
include "db/fonctions.php";


if(isset($_POST['submit'])) {

    if (!empty($_POST['email']) && !empty($_POST['password'])) {

        $email = htmlspecialchars($_POST['email']);
        $password = htmlspecialchars($_POST['password']);

        $co = connexionBdd();
        $check = $co->prepare('SELECT * FROM role INNER JOIN utilisateur ON role.id = utilisateur.role WHERE email = ?');

        $check->execute(array($email));
        // on recupère les données qu'on place dans une variable
        $data = $check->fetch();
        // on met un compteur
        $row = $check->rowCount();

        if ($row == 1) {

            // on filtre les différentes adresses mail pour qu'elle corresponde au mot de passe
            if(filter_var($email, FILTER_VALIDATE_EMAIL)) {
                if (password_verify($password, $data['password'])) {
                    // si l'utilisateur correspond bien à l'email
                    $_SESSION['user'] = $data['email'];
                    $_SESSION['login'] = $data['login'];
                    $_SESSION['libelle'] = $data['libelle'];

                    // on le redirige à la page principale
                    header('Location: accueil.php?count=&filter=&search=');
                    die();
                }
            }
        }
    }
}
