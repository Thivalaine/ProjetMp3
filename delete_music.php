<?php

$admin = "Administrateur";

session_start();
if($_SESSION['libelle'] == $admin)
{
    include "db/fonctions.php";

// on récupère la colonne "id_projet" et on vérifie si elle est déclarée
    if (isset($_GET['id_son'])) {
        // et on l'a met dans une variable
        $id_son = $_GET['id_son'];

        $co = ConnexionBdd();

        $queryold = $co->prepare("SELECT * FROM artiste_son WHERE id_son_fusion = :id_son");

        $queryold->bindParam(':id_son', $id_son);

        $queryold->execute();

        $resultold = $queryold->fetchAll(PDO::FETCH_ASSOC);

        foreach ($resultold as $value)
        {
            $queryadd = $co->prepare("DELETE FROM artiste_son WHERE id_son_fusion = :id_son AND id_artiste = :id_artiste");

            $queryadd->bindParam(':id_son', $id_son);
            $queryadd->bindParam(':id_artiste', $value['id_artiste']);


            $queryadd->execute();
        }

        $queryf = $co->prepare("SELECT fichier FROM son WHERE id_son = :id_son");

        $queryf->bindParam(':id_son', $id_son);

        $queryf->execute();

        $resultf = $queryf->fetchAll();

        foreach ($resultf as $value)
        {
            $path = 'fichiers/' . $value['fichier'];
            unlink($path);
        }

        // on supprime les données de la table "works" dans lequel "id_projet" est égale à ce que l'on a récupérer
        $query = $co->prepare("DELETE FROM son WHERE id_son = :id_son");
        $query->bindParam(':id_son', $id_son, PDO::PARAM_INT);
        $query->execute();



        if ($query == TRUE)
        {
            echo "Son supprimé !";
        }
        else
        {
            echo "Erreur :" . $query . "<br>" . $co->error;
        }
    }
}
else
{
    header('Location:accueil.php');
}

?>
