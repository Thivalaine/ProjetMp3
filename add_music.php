
<?php
include_once 'db/fonctions.php';

if(isset($_POST['add']))
{
    if(!empty($_POST['libelle']) && !empty($_POST['artiste']) && !empty($_POST['album']) && !empty($_POST['duree'])){

        $libelle = $_POST['libelle'];
        $album = $_POST['album'];
        $duree = $_POST['duree'];
        $artiste = $_POST['artiste'];

        // il s'agit du nom de l'image
        $img_name = $_FILES['fichier']['name'];
        // ici il s'agit de la taille de l'image
        $img_size = $_FILES['fichier']['size'];
        // ici il s'agit des données qui composent l'image
        $tmp_name = $_FILES['fichier']['tmp_name'];
        // et la c'est pour voir s'il y a un problème avec l'image
        $error = $_FILES['fichier']['error'];

        if ($error === 0) {
            // si l'image est plus grande que 10Mo ou 10485760 d'octets
            if ($img_size > 10485760)
            {
                $em = "Désolé, votre fichier est trop lourd, elle doit être inférieur à 10Mo";
                echo $em;
            }
            else
            {
                // ici on retourne l'extension de l'image ($img_name) avec PATHINFO_EXTENSION
                $img_ex = pathinfo($img_name, PATHINFO_EXTENSION);

                // strtolower permet dans tous les cas de renvoyer ce qu'il contient en minuscule
                $img_ex_lc = strtolower($img_ex);

                // ici on donne les extensions autorisées dans un tableau

                $allowed_exs = array("mp3", "wav");

                // ici on vérifie que ce que retourne pathinfo_extension correspond aux extensions autorisées de la variable $allowed_exs
                if (in_array($img_ex_lc, $allowed_exs))
                {
                    // si oui, on lui donne le nom du projet à l'image et suivi de son extension dans la variable $img_ex_lc
                    $new_img_name = $libelle.'.'.$img_ex_lc;

                    // ici on déclare une variable dans lequel on y met le chemin et l'endroit de destination de l'image, ici ce sera dans un fichier "files"
                    $img_upload_path = 'fichiers/'.$new_img_name;

                    // cette fonction permet de déplacer un fichier télécharger d'un endroit temporaire à un endroit précis
                    move_uploaded_file($tmp_name, $img_upload_path);

                    // ici on insère toutes les données remplis pour ajouter un projet dans la table "works"
                    $co = ConnexionBdd();

                    $album = implode('', $album);

                    $query = $co->prepare("INSERT into son(libelle, album, duree, fichier) VALUES (:libelle, :album, :duree, :fichier)");

                    $query->bindParam(':libelle', $libelle);
                    $query->bindParam(':album', $album);
                    $query->bindParam(':duree', $duree);
                    $query->bindParam(':fichier', $new_img_name);

                    $query->execute();

                    $last_id = $co->lastInsertId();

                    foreach ($artiste as $value)
                    {
                        $query = $co->prepare("INSERT into artiste_son(id_artiste, id_son_fusion) VALUES (:id_artiste, :id_son)");

                        $query->bindParam(':id_artiste', $value);
                        $query->bindParam(':id_son', $last_id);

                        $query->execute();
                    }
                    $libelle = $duree = $album = $artiste = $new_img_name = "";


                }
                else
                {
                    // si l'image n'est pas de la bonne extension
                    $em = "Vous ne pouvez pas envoyer des fichiers avec cette extension";
                    echo $em;
                }
            }
        }
        else
        {
            // s'il y a une erreur sur l'image qui n'est pas connue alors
            $em = "Une erreur inconnue s'est produite !";
            echo $em;
        }

    }
}
