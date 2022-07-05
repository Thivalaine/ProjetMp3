<?php
    $admin = "Administrateur";
    session_start();
    if(!isset($_SESSION['user']))
    {
        header('Location:index.php');
    }

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Menu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="Virtual_Select/virtual-select.min.css">
    <script src="Virtual_Select/virtual-select.min.js"></script>
    <link rel="stylesheet" href="Virtual_Select/tooltip.min.css">
    <script src="Virtual_Select/tooltip.min.js"></script>
<body>
<header>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Vous êtes : <?php echo $_SESSION['libelle']; ?></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="accueil.php?count=&filter=ASC&search=">Accueil</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="disconnect.php">Déconnexion</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Options
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="#">Playlist</a></li>
                            <li><a class="dropdown-item" href="#">Ajouter un son à sa playlist</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <?php
                                if($_SESSION['libelle'] == $admin)
                                {
                                    ?>
                                    <li><!-- Button trigger modal -->
                                        <a class="dropdown-item" href="" data-bs-toggle="modal" data-bs-target="#ajout_son">
                                            Ajouter un nouveau son
                                        </a>
                                    </li>
                            <?php
                                }
                            ?>
                        </ul>
                    </li>
                </ul>
                <form class="d-flex">
                    <?php

                    include 'db/fonctions.php';

                    $co = Connexionbdd();


                    if($_GET['search'])
                    {
                        $query = $co->prepare("SELECT DISTINCT id_son_fusion, id_son, duree, libelle_alb, libelle, fichier FROM album INNER JOIN son ON album.id = son.album INNER JOIN artiste_son ON son.id_son = artiste_son.id_son_fusion INNER JOIN artiste ON artiste_son.id_artiste = artiste.id WHERE libelle = :libelle OR libelle_alb = :libelle OR firstname = :libelle OR lastname = :libelle");

                        $query->bindParam(':libelle', $_GET['search']);

                        $query->execute();

                    }
                    else
                    {
                        $query = $co->prepare("SELECT * FROM son");

                        $query->execute();
                    }

                    $result = $query->fetchAll();
                    ?>
                    <input type="number" name="count" class="form-control" value="<?php if(empty($_POST['count'])) { echo $_GET['count']; } if(empty($_GET['count']) && $_GET['count'] > 0 || $_GET['count'] == "") {echo count($result); } ?>">
                    <select name="filter" class="form-select">
                        <option value="ASC" <?php if($_GET['filter'] == "ASC") { echo "selected"; } ?>>Ordre croissant</option>
                        <option value="DESC" <?php if($_GET['filter'] == "DESC") { echo "selected"; } ?>>Ordre décroissant</option>
                    </select>
                    <input class="form-control me-2" type="search" name="search" placeholder="Rechercher un son" aria-label="Search" value="<?php if(!empty($_POST['search'])) { echo ""; } else { echo $_GET['search']; } ?>">
                    <button class="btn btn-outline-success" type="submit">Rechercher</button>
                </form>
            </div>
        </div>
    </nav>
</header>
<div class="container">
    <h3>Résultat(s) trouvé(s) <?php if($_GET['search'] && $_GET['count']) { ?> pour "<?php echo $_GET['search']; ?>" <?php } else { echo "en globalité"; } ?> : <?php echo count($result) ?></h3>
<div class="text-center">
    <?php
    include_once 'db/fonctions.php';

    $co = Connexionbdd();


        $query = $co->prepare("SELECT DISTINCT id_son_fusion, id_son, duree, libelle_alb, libelle, fichier FROM album INNER JOIN son ON album.id = son.album INNER JOIN artiste_son ON son.id_son = artiste_son.id_son_fusion INNER JOIN artiste ON artiste_son.id_artiste = artiste.id WHERE libelle = :libelle OR libelle_alb = :libelle OR firstname = :libelle OR lastname = :libelle");

        $query->bindParam(':libelle', $_GET['search']);

        $query->execute();

        if($query->rowCount() == 0) {

            $search = htmlspecialchars($_GET['search']);

            if($_GET['filter'] == "ASC" || $_GET['filter'] == "DESC")
            {
                if($_GET['count'] == "")
                {
                    $keyword = "%".$search."%";
                    $query = $co->prepare("SELECT DISTINCT id_son_fusion, id_son, duree, libelle_alb, libelle, fichier FROM album INNER JOIN son ON album.id = son.album INNER JOIN artiste_son ON son.id_son = artiste_son.id_son_fusion INNER JOIN artiste ON artiste_son.id_artiste = artiste.id WHERE CONCAT(libelle, libelle_alb, firstname, lastname) LIKE :like ORDER BY id_son ".$_GET['filter']."");
                    $query->bindParam(':like', $keyword, PDO::PARAM_STR);

                    $query->execute();
                }
                else
                {
                    $keyword = "%".$search."%";
                    $query = $co->prepare("SELECT DISTINCT id_son_fusion, id_son, duree, libelle_alb, libelle, fichier FROM album INNER JOIN son ON album.id = son.album INNER JOIN artiste_son ON son.id_son = artiste_son.id_son_fusion INNER JOIN artiste ON artiste_son.id_artiste = artiste.id WHERE CONCAT(libelle, libelle_alb, firstname, lastname) LIKE :like ORDER BY id_son ".$_GET['filter']." LIMIT ".$_GET['count']."");
                    $query->bindParam(':like', $keyword, PDO::PARAM_STR);

                    $query->execute();
                }
            }
            /* si c'est différent de ASC et DESC alors on affiche normalement (exemple si l'utilisateur modifie le value d'un des deux et mettent un autre truc */
            else
            {
                $query = $co->prepare("SELECT * FROM son");

                $query->execute();
            }

    }

    $result = $query->fetchAll();



    ?>
    <table class="table">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Libellé</th>
                <th scope="col">Album</th>
                <th scope="col">Auteur</th>
                <th scope="col">Durée</th>
                <th scope="col">Action</th>
            </tr>
        </thead>
        <tbody>
        <?php

        foreach ($result as $row)
        {
            ?>
            <tr>
                <th scope="row"><?php echo $row['id_son']; ?></th>
                <td><?php echo $row['libelle']; ?></td>
                <td>
                    <?php
                        $query3 = $co->prepare("SELECT libelle_alb FROM album INNER JOIN son ON album.id = son.album WHERE id_son = :id");

                        $query3->bindParam(':id', $row['id_son']);

                        $query3->execute();

                        $result = $query3->fetchAll();

                        foreach($result as $row3)
                        {
                            echo $row3['libelle_alb'];
                        }
                    ?>
                </td>
                <td>
                    <?php
                    $query2 = $co->prepare("SELECT firstname, lastname FROM artiste INNER JOIN artiste_son ON artiste.id = artiste_son.id_artiste WHERE id_son_fusion = :id_son;");

                    $query2->bindParam(':id_son', $row['id_son_fusion']);

                    $query2->execute();

                    $result = $query2->fetchAll();

                    foreach($result as $row2)
                    {
                        echo $row2['firstname'] . " " . $row2['lastname'] . ", ";
                    }

                    ?>

                </td>
                <td><?php echo $row['duree']; ?></td>
                <td><div class="accordion" id="accordionPanelsStayOpenExample">
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="panelsStayOpen-headingTwo">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#id_son_<?php echo $row['id_son']; ?>" aria-expanded="false" aria-controls="<?php echo $row['libelle']; ?><?php echo $row['id_son']; ?>">
                                    <?php echo $row['libelle']; ?>
                                </button>
                            </h2>
                            <div id="id_son_<?php echo $row['id_son']; ?>" class="accordion-collapse collapse" aria-labelledby="<?php echo $row['libelle']; ?><?php echo $row['id_son']; ?>">
                                <div class="accordion-body">
                                    <audio controls src="fichiers/<?php echo $row['fichier']; ?>" style="width: 100%;">
                                        Your browser does not support the
                                        <code>audio</code> element.
                                    </audio>
                                    <?php
                                    if($_SESSION['libelle'] == $admin)
                                    {
                                        ?>
                                        <a class="btn btn-primary">Modifier</a>
                                        <a class="btn btn-danger" onclick="if(!confirm('Êtes-vous sûr de vouloir supprimer ce son ?')) { return false; }" href="delete_music.php?id_son=<?php echo $row['id_son']; ?>">Supprimer</a>
                                    <?php
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                        </div></td>
            </tr>
            <?php
        }

        ?>
        </tbody>
    </table>
</div>
</div>

<!-- Modal -->
<div class="modal fade" id="ajout_son" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Ajout d'un son</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body">
                <form action="accueil.php" method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="libelle" class="form-label">Libellé</label>
                        <input type="text" name="libelle" class="form-control" id="libelle">
                    </div>
                    <div class="mb-3">
                        <label for="artiste" class="form-label" style="transform: translate(0, 6px);">Artiste(s)</label>
                    </div>
                    <div class="mb-3">
                        <select multiple name="artiste" id="content" placeholder="Rechercher un artiste" data-search="true" data-silent-initial-value-set="true">
                            <?php
                            $query = $co->prepare("SELECT * FROM artiste");

                            $query->execute();

                            $result = $query->fetchAll();

                            foreach($result as $row) {
                                ?>
                            <option value="<?php echo $row['id']; ?>"><?php echo $row['firstname'] . " " . $row['lastname'] ?></option>
                            <?php
                            }

                            ?>
                        </select>
                        <script>
                            VirtualSelect.init({
                                ele: '#content',
                                multiple: true,
                                showValueAsTags: true,
                            });
                        </script>
                        <a href="">Ajouter un(e) artiste</a>
                    </div>
                    <div class="mb-3">
                        <label for="album" class="form-label">Album</label>
                        <select name="album[]" class="form-control">
                            <?php
                                $query = $co->prepare("SELECT * FROM album");

                                $query->execute();

                                $result = $query->fetchAll();

                                foreach($result as $row)
                                {
                                    ?>
                                    <option value="<?php echo $row['id']; ?>"><?php echo $row['libelle_alb'] ?></option>
                            <?php
                                }
                            ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="duree" class="form-label">Durée</label>
                        <input type="time" name="duree" class="form-control" id="duree" step="1">
                    </div>
                    <div class="mb-3">
                        <label for="fichier" class="form_label">Fichier</label>
                        <input id="fichier" type="file" name="fichier" class="form-control">
                    </div>
                    <button type="submit" class="btn btn-primary" name="add">Ajouter</button>
                </form>
                <?php
                include 'add_music.php';
                ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.5/dist/umd/popper.min.js" integrity="sha384-Xe+8cL9oJa6tN/veChSP7q+mnSPaj5Bcu9mPX5F5xIGE0DVittaqT5lorf0EI7Vk" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.min.js" integrity="sha384-kjU+l4N0Yf4ZOJErLsIcvOU2qSb74wXpOhqTvwVx3OElZRweTnQ6d31fXEoRD1Jy" crossorigin="anonymous"></script>
</body>
</html>
