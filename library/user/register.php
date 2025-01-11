
<?php
include "../db.php";


if(isset($_POST['submit'])) {
    
    $nom = $_POST['nom'];
    $email =  $_POST['email'];
    $telephone =  $_POST['telephone'];
    $adresse =  $_POST['adresse'];
    $password =  $_POST['passwordd'];
    
        $sql = "INSERT INTO membres (nom, email, telephone, adresse, passwordd) 
                VALUES ('$nom', '$email', '$telephone', '$adresse', '$password')";
      $stmt = $conn->prepare($sql);
      $res = $stmt->execute();

      // 
      if ($res) {
          $succ= "membre ajouté avec succès.";
          header("location: ../index.php");
      } else {
          $error= "Erreur lors de l'ajout du membre.";
      }
    
}
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/styles.css">
</head>

<body class="d-flex justify-content-center align-items-center m-4">
    <div class="container bg-white p-4 rounded" style="max-width: 500px; width: 100%;">
        <h2 class="text-center">Inscription</h2>
        <form method="POST">
        <?php
                        if (isset($error)) {
                            echo "<div class='alert alert-danger'>$error</div>";
                        }
                        elseif (isset($succ)) {
                            echo "<div class='alert alert-success'>$succ</div>";
                        }
                        ?>
            <div class="mb-3">
                <label for="nom" class="form-label">Nom</label>
                <input type="text" class="form-control" id="nom" name="nom" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-3">
                <label for="telephone" class="form-label">Téléphone</label>
                <input type="text" class="form-control" id="telephone" name="telephone">
            </div>
            <div class="mb-3">
                <label for="adresse" class="form-label">Adresse</label>
                <textarea class="form-control" id="adresse" name="adresse"></textarea>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Mot de passe</label>
                <input type="password" class="form-control" id="password" name="passwordd" required>
            </div>
            <button type="submit" name="submit" class="btn btn-primary">S'inscrire</button>
        </form>
    </div>
</body>
</html>
