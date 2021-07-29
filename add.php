<?php
// On démarre une session
session_start();

if($_POST){
    if(isset($_POST['email']) && !empty($_POST['email'])
    && isset($_POST['password']) && !empty($_POST['password'])
    && isset($_POST['birthDate']) && !empty($_POST['birthDate'])){
        // On inclut la connexion à la base
        require_once('connect.php');

        // On nettoie les données envoyées
        $user = strip_tags($_POST['email']);
        $prix = strip_tags($_POST['prix']);
        $birthdate = strip_tags($_POST['birthDate']);

        $sql = 'INSERT INTO `user` (`email`, `password`, `birthdate`) VALUES (:email, :password, :birthdate);';

        $query = $db->prepare($sql);

        $query->bindValue(':email', $user, PDO::PARAM_STR);
        $query->bindValue(':password', $password, PDO::PARAM_STR);
        $query->bindValue(':birthdate', $birthdate, PDO::PARAM_STR);

        $query->execute();

        $_SESSION['message'] = "Utilisateur ajouté";
        require_once('close.php');

        header('Location: index.php');
    }else{
        $_SESSION['erreur'] = "Le formulaire est incomplet";
    }
}


?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un utilisateur</title>

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
                <h1>Ajouter un utilisateur</h1>
                <form method="post">
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="text" id="email" name="email" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="prix">Password</label>
                        <input type="text" id="password" name="password" class="form-control">

                    </div>
                    <div class="form-group">
                        <label for="birthdate">Date de Naissance</label>
                        <input type="date" id="birthdate" name="birthdate" class="form-control">
                    </div>
                    <button class="btn btn-primary">Envoyer</button>
                </form>
            </section>
        </div>
    </main>
</body>
</html>