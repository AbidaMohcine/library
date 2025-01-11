<?php
include "../db.php"; 

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['id_membre'])) {
    echo "<p class='text-danger'>Erreur : Vous devez être connecté pour accéder à cette page.</p>";
    exit;
}

$user_id = $_SESSION['id_membre'];

if (isset($_POST['submit'])) { 
    $id_membre = $_POST['id_membre'];
    $nom = htmlspecialchars($_POST['nom']);
    $telephone = htmlspecialchars($_POST['telephone']);
    $email = htmlspecialchars($_POST['email']);
    $adresse = htmlspecialchars($_POST['adresse']);
    $passwordd = htmlspecialchars($_POST['passwordd']);

    $sql = "UPDATE membres SET nom = ?, telephone = ?, email = ?, adresse = ?, passwordd = ? WHERE id_membre = ?";
    $stmt = $conn->prepare($sql);
    $res = $stmt->execute([$nom, $telephone, $email, $adresse, $passwordd, $id_membre]);

    if ($res) {
        header("location: profil.php?modified=1");
        exit;
    } else {
        echo "<p class='text-danger'>Erreur lors de la modification du profil.</p>";
    }
}

$sql = "SELECT * FROM membres WHERE id_membre = ?";
$stmt = $conn->prepare($sql);
$stmt->execute([$user_id]);
$data = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$data) {
    echo "<p class='text-danger'>Erreur : L'utilisateur n'existe pas.</p>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Utilisateur</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <?php include "header.php"; ?>
    <div class="container mt-5">
        <div class="card mx-auto" style="max-width: 400px;">
            <div class="card-body text-center">
                <img src="https://via.placeholder.com/150" class="rounded-circle mb-3" alt="Photo de profil" width="150" height="150">
                <h3 class="card-title"><strong>Bienvenue,</strong> <?php echo htmlspecialchars($data["nom"]); ?></h3>
                <ul class="list-group list-group-flush text-start">
                    <li class="list-group-item"><strong>Email :</strong> <?php echo htmlspecialchars($data["email"]); ?></li>
                    <li class="list-group-item"><strong>Téléphone :</strong> <?php echo htmlspecialchars($data["telephone"]); ?></li>
                    <li class="list-group-item"><strong>Adresse :</strong> <?php echo htmlspecialchars($data["adresse"]); ?></li>
                    <li class="list-group-item"><strong>Mot de passe :</strong> <?php echo htmlspecialchars($data["passwordd"]); ?></li>
                </ul>
                <div class="mt-4">
                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editProfileModal">
                        Modifier le profil
                    </button>
                </div>
            </div>
        </div>
    </div>

   
    <div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editProfileModalLabel">Modifier le Profil</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="">
                        <input type="hidden" name="id_membre" value="<?php echo $data['id_membre']; ?>">
                        <div class="mb-3">
                            <label for="editNom" class="form-label">Nom</label>
                            <input type="text" class="form-control" name="nom" id="editNom" value="<?php echo htmlspecialchars($data['nom']); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="editEmail" class="form-label">Email</label>
                            <input type="email" class="form-control" name="email" id="editEmail" value="<?php echo htmlspecialchars($data['email']); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="editPhone" class="form-label">Téléphone</label>
                            <input type="tel" class="form-control" name="telephone" id="editPhone" value="<?php echo htmlspecialchars($data['telephone']); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="editAddress" class="form-label">Adresse</label>
                            <input type="text" class="form-control" name="adresse" id="editAddress" value="<?php echo htmlspecialchars($data['adresse']); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="editPassword" class="form-label">Mot de passe</label>
                            <input type="text" class="form-control" name="passwordd" id="editPassword" value="<?php echo htmlspecialchars($data['passwordd']); ?>" >
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                            <button type="submit" name="submit" class="btn btn-primary">Enregistrer les modifications</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <hr>
        <div class="text-center">
            <p><b>Tous droits réservés 2024</b></p>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
