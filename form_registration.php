<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Menu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
<div class="container">
    <form action="registration.php" method="post">
        <h1>Inscription à l'application</h1>
        <div class="mb-3">
            <label for="login" class="form-label">Pseudonyme</label>
            <input name="login" type="text" class="form-control" id="login">
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input name="email" type="email" class="form-control" id="email" >
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Mot de passe</label>
            <input type="password" name="password" class="form-control" id="password">
        </div>
        <div class="mb-3">
            <label for="password_retype" class="form-label">Confirmation Mot de passe</label>
            <input type="password" name="password_retype" class="form-control" id="password_retype">
        </div>
        <div class="mb-3">
            <label for="firstname" class="form-label">Prénom</label>
            <input type="text" name="firstname" class="form-control" id="firstname">
        </div>
        <div class="mb-3">
            <label for="lastname" class="form-label">Nom</label>
            <input type="text" name="lastname" class="form-control" id="lastname">
        </div>
        <div class="mb-3">
            <label for="dateofbirth" class="form-label">Date de naissance</label>
            <input type="date" name="dateofbirth" class="form-control" id="dateofbirth">
        </div>
        <div class="mb-3">
            <label for="country" class="form-label">Ville</label>
            <input type="text" name="country" class="form-control" id="country">
        </div>
        <div class="mb-3">
            <?php

            include 'db/fonctions.php';
            $co = Connexionbdd();

            $query = $co->prepare("SELECT * FROM role");

            $query->execute();

            $result = $query->fetchAll();
            ?>
            <div class="mb-3">
                <label for="role" class="form-label">Rôle</label>
                <select id="role" name="role[]" class="form-control">
                    <?php
                    foreach($result as $row)
                    {
                    ?>
                    <option value="<?php echo $row['id']; ?>" name="<?php echo $row['id']; ?>"><?php echo $row['libelle']; ?></option>
                <?php
                }

                ?>
                </select>
            </div>
        </div>
        <button type="submit" name="submit" class="btn btn-primary">Submit</button>
        <?php
            include "registration.php";
        ?>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.5/dist/umd/popper.min.js" integrity="sha384-Xe+8cL9oJa6tN/veChSP7q+mnSPaj5Bcu9mPX5F5xIGE0DVittaqT5lorf0EI7Vk" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.min.js" integrity="sha384-kjU+l4N0Yf4ZOJErLsIcvOU2qSb74wXpOhqTvwVx3OElZRweTnQ6d31fXEoRD1Jy" crossorigin="anonymous"></script>
</body>
</html>