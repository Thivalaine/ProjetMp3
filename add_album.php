
<?php
include_once 'db/fonctions.php';

if(isset($_POST['add_album']))
{
    if(!empty($_POST['libelle_alb']) && !empty($_POST['date_de_parution'])){

        $libelle_alb = $_POST['libelle_alb'];
        $date_de_parution = $_POST['date_de_parution'];

        $co = ConnexionBdd();

        $query = $co->prepare("INSERT into album(libelle_alb, date_de_parution) VALUES (:libelle_alb, :date_de_parution)");

        $query->bindParam(':libelle_alb', $libelle_alb);
        $query->bindParam(':date_de_parution', $date_de_parution);

        $query->execute();

        $libelle_alb = $date_de_parution = "";

        echo('<meta http-equiv="refresh" content=0>');

    }
}
