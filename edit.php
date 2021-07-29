<?php
// On démarre une session
session_start();

if($_POST){
    if(isset($_POST['id']) && !empty($_POST['id'])
    && isset($_POST['email']) && !empty($_POST['email'])
    && isset($_POST['password']) && !empty($_POST['password'])
    && isset($_POST['birthdate']) && !empty($_POST['birthdate'])){
        // On inclut la connexion à la base
        require_once('connect.php');

        // On nettoie les données envoyées
        $id = strip_tags($_POST['id']);
        $user = strip_tags($_POST['email']);
        $password = strip_tags($_POST['password']);
        $birthdate = strip_tags($_POST['birthdate']);

        $sql = 'UPDATE `user` SET `email`=:email, `password`=:password, `birthdate`=:birthdate WHERE `id`=:id;';

        $query = $db->prepare($sql);

        $query->bindValue(':id', $id, PDO::PARAM_INT);
        $query->bindValue(':email', $user, PDO::PARAM_STR);
        $query->bindValue(':password', $password, PDO::PARAM_STR);
        $query->bindValue(':birthdate', $birthdate, PDO::PARAM_INT);

        $query->execute();

        $_SESSION['message'] = "Utilisateur modifié";
        require_once('close.php');

        header('Location: index.php');
    }else{
        $_SESSION['erreur'] = "Le formulaire est incomplet";
    }
}

// Est-ce que l'id existe et n'est pas vide dans l'URL
if(isset($_GET['id']) && !empty($_GET['id'])){
    require_once('connect.php');

    // On nettoie l'id envoyé
    $id = strip_tags($_GET['id']);

    $sql = 'SELECT * FROM `user` WHERE `id` = :id;';

    // On prépare la requête
    $query = $db->prepare($sql);

    // On "accroche" les paramètre (id)
    $query->bindValue(':id', $id, PDO::PARAM_INT);

    // On exécute la requête
    $query->execute();

    // On récupère l'email de l'utilisateur
    $user = $query->fetch();

    // On vérifie si l'utilisateur existe
    if(!$user){
        $_SESSION['erreur'] = "Cet id n'existe pas";
        header('Location: index.php');
    }
}else{
    $_SESSION['erreur'] = "URL invalide";
    header('Location: index.php');
}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier un utilisateur</title>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
</head>
<body>
    <main class="container">
        <div class="row">
            <section class="col-12">
                <?php
                    if(!empty($_SESSION['erreur'])){
                        echo '<div class="alert alert-danger" role="alert">
                                '. $_SESSION['erreur'].'
                            </div>';
                        $_SESSION['erreur'] = "";
                    }
                ?>
                <h1>Modifier un utilisateur</h1>
                <form method="post">
                    <div class="form-group">
                        <label for="utilisateur">Email</label>
                        <input type="text" id="utilisateur" name="utilisateur" class="form-control" value="<?= $user['email']?>">
                    </div>
                    <div class="form-group">
                        <label for="prix">Password</label>
                        <input type="text" id="password" name="prix" class="form-control" value="<?= $password['password']?>">

                    </div>
                    <div class="form-group">
                        <label for="nombre">Date de naissance</label>
                        <input type="date" id="birthdate" name="birthdate" class="form-control" value="<?= $birthdate['birthdate']?>">
                    </div>
                    <input type="hidden" value="<?= $user['id']?>" name="id">
                    <button class="btn btn-primary">Envoyer</button>
                </form>
            </section>
        </div>
    </main>
</body>
</html>