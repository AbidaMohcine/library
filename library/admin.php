<?php
include "db.php"; 

session_start();

if (isset($_GET['logout']) && $_GET['logout'] === 'true') {
    session_unset(); 
    session_destroy(); 
    
    header("Location: index.php");
    exit();
}


if (isset($_POST['submit'])) {
    $user = $_POST['username'];
    $password = $_POST['password'];

    if (empty($user) || empty($password)) {
        $error = "Merci de remplir tous les champs.";
    } else {
            $sql = "SELECT * FROM admins WHERE username = ? AND password = ?";
            $stmt = $conn->prepare($sql);
            
            $stmt->execute([$user, $password]);
            $donne = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($donne) {
                header("Location: admi/dashbord.php");
                $_SESSION['username']=$donne['username'];
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

    <link rel="stylesheet" href="./css/styles.css">
</head>

<body>
    <div class="container login-container">
        <h2 class="mb-5"> Gestion de Bibliothèque</h2>
        
            <div class=" login-form-3 bg-light">
                <div class="text-center">
                    <img src="./css/logo.jpg" class="rounded" width="50px" height="50px" >
                </div>
                <h3 class="text-center mb-5">Administrateur</h3>
                <?php
            if (isset($error_message)) {
                echo "<div class='alert alert-danger'>$error_message</div>";
            }
            elseif (isset($error)) {
                echo "<div class='alert alert-danger'>$error</div>";
            }
            ?>
                <form  method="post">
                    <div class="form-group">
                        <input type="text" class="form-control" name="username" placeholder="Nom d'administrateur *" value="" />
                    </div>
                    <div class="form-group">
                        <input type="password" class="form-control" name="password" placeholder="Mot de passe *" value="" />
                    </div>
                    <div class="form-group">
                        <input type="submit" name="submit" class="btnSubmit btn btn-info" value="Se connecter" />
                    </div>
                </form>
                <div class="text-center">
                    <a href="index.php" class="link">Page d'Acceuil</a>
                </div>
            </div>
    </div> 
</body>
</html>
