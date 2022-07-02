<!-- il s'agit ici du traitement lors que l'inscription de l'utilsateur -->
<?php
include_once 'db/fonctions.php';

// lorsque le pseudo, l'email, le mot de passe et la réécriture du mot de passe n'est pas vide
if(!empty($_POST['login']) && !empty($_POST['email']) && !empty($_POST['password']) && !empty($_POST['password_retype']) && !empty($_POST['firstname']) && !empty($_POST['lastname']) && !empty($_POST['dateofbirth']) && !empty($_POST['country'])) {
    // sécurité
    $login = htmlspecialchars($_POST['login']);
    $email = htmlspecialchars($_POST['email']);
    $password = htmlspecialchars($_POST['password']);
    $password_retype = htmlspecialchars($_POST['password_retype']);
    $firstname = htmlspecialchars($_POST['firstname']);
    $lastname = htmlspecialchars($_POST['lastname']);
    $dateofbirth = htmlspecialchars($_POST['dateofbirth']);
    $country = htmlspecialchars($_POST['country']);
    $role = $_POST['role'];

    // on vérifie si le compte n'existe pas dans la base de données
    $co = ConnexionBdd();

    $check = $co->prepare('SELECT login, email, password FROM utilisateur WHERE email = ?');
    $check->execute(array($email));
    $data = $check->fetch();
    $row = $check->rowCount();

    if ($row == 0) {

        // voir si c'est le bon format d'email
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            // si le mot de passe est bien égale à la réécriture du mot de passe
            if($password == $password_retype) {

                // système de hachage "BCRYPT" de mot de passe
                $password = password_hash($password, PASSWORD_DEFAULT);

                $role = implode("", $role);

                // on insère le pseudo, l'email, le mot de passe et l'ip dans la base de données
                $insert = $co->prepare('INSERT INTO utilisateur (login, password, email, firstname, lastname, dateofbirth, country, role) VALUES(:login, :password, :email, :firstname, :lastname, :dateofbirth, :country, :role)');
                // et on les définis dans des variables
                $insert->execute(array(
                    'login' => $login,
                    'email' => $email,
                    'password' => $password,
                    'firstname' => $firstname,
                    'lastname' => $lastname,
                    'dateofbirth' => $dateofbirth,
                    'country' => $country,
                    'role' => $role,
                ));


                echo "Données insérées !";
                die();
            } else {
                echo "mot de passe différent";
            }
        }
        else
        {
            echo "Mauvais format d'email !";
        }
    }
    else
    {
        echo "L'adresse mail est déjà utilisée !";
    }
}
else
{
    echo "des champs doivent être rempli !";
}

