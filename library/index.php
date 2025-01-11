<?php
include "db.php";
session_start();

if (isset($_GET['logout']) && $_GET['logout'] === 'true') {

    session_destroy(); 
    
    header("Location: index.php");
    exit();
}

if (isset($_POST['submit'])) {
    $email = $_POST['emaill']; 
    $password = $_POST['password']; 

    if (empty($email) || empty($password)) {
        $error = "Merci de remplir tous les champs.";
    } else {

        $sql = "SELECT * FROM membres WHERE email = ? AND passwordd = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$email, $password]);
        $donne = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($donne) {
            $_SESSION['id_membre'] = $donne['id_membre'];
            header("Location: user/accueil.php");
            exit(); 
        } else {
            $error_message = "Identifiants incorrects, veuillez réessayer.";
        }
    }
}
?>



<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Login Form</title>
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="./css/styles.css">
</head>

<body>
    <div class="container login-container">
        <h2 class="mb-5">Gestion de Bibliothèque</h2>
            
        <div class="login-form-3 bg-light">
            <div class="text-center">
                <img src="./css/logo.jpg" class="rounded" width="50px" height="50px">
            </div>
            <h3 class="text-center mb-5">Utilisateur</h3>
            
            <?php
            if (isset($error_message)) {
                echo "<div class='alert alert-danger'>$error_message</div>";
            }
            ?>
              <?php
            if (isset($error)) {
                echo "<div class='alert alert-danger'>$error</div>";
            }
            ?>
            <form action="" method="post">
                <div class="form-group">
                    <input type="email" class="form-control" name="emaill" placeholder="Email d'utilisateur *" value="" />
                </div>
                <div class="form-group">
                    <input type="password" class="form-control" name="password" placeholder="Mot de passe *" value="" />
                </div>
                <div class="form-group">
                    <input type="submit" name="submit" class="btnSubmit btn btn-info" value="Se connecter" />
                </div>
                <p class="mt-3">Pas encore inscrit ? <a href="user/register.php">S'inscrire</a></p>
                <div class="text-center">
                    <a href="admin.php" class="link">Identification Admin</a>
                </div>
            </form>
          
        </div>
    </div>
</body>
</html>
