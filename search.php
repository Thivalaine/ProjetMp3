<?php
include_once 'db/fonctions.php';
$co = ConnexionBdd();

$keyword = "%".$_GET['data']."%";

$query = $co->prepare("SELECT * FROM artiste WHERE firstname LIKE :libelle OR lastname LIKE :libelle");

$query->bindParam(':libelle', $keyword);

$query->execute();

$result = $query->fetchAll();

foreach($result as $row) {

    echo "<option value=".$row['id'].">". $row['firstname'] . ' ' . $row['lastname'] . "</option>";
}
