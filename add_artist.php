
<?php
include_once 'db/fonctions.php';

if(isset($_POST['add_artist']))
{
    if(!empty($_POST['firstname']) && !empty($_POST['lastname'])){

        $firstname = $_POST['firstname'];
        $lastname = $_POST['lastname'];

        $co = ConnexionBdd();

        $query = $co->prepare("INSERT into artiste(firstname, lastname) VALUES (:firstname, :lastname)");

        $query->bindParam(':firstname', $firstname);
        $query->bindParam(':lastname', $lastname);

        $query->execute();

        $firstname = $lastname = "";

        echo('<meta http-equiv="refresh" content=0>');

    }
}
