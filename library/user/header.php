<?php 
include "../db.php";
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Vérifiez si l'utilisateur est connecté
if (!isset($_SESSION['id_membre'])) {
    header("Location: ../index.php"); // Redirige vers la page de connexion si non connecté
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réservation - Bibliothèque</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet"> <!-- Import FontAwesome -->
</head>

<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand d-flex align-items-center" href="#">
            <img src="../css/logo.jpg" alt="Logo Bibliothèque" width="50" height="50" class="d-inline-block align-text-top rounded">
            <h3 class="ms-3 mb-0">Bibliothèque</h3>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
    </div>
    <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
        <ul class="navbar-nav">
            <li class="nav-item"><a class="nav-link" href="accueil.php">Accueil</a></li>
            <li class="nav-item"><a class="nav-link" href="empruntes.php">Empruntes</a></li>
            <li class="nav-item"><a class="nav-link" href="avis.php">Avis</a></li>
            <li class="nav-item">
                <a class="nav-link" href="profil.php?id=<?php echo htmlspecialchars($_SESSION['id_membre'], ENT_QUOTES, 'UTF-8'); ?>">Profil</a>
            </li>
            <li class="nav-item">
                <a href="../index.php?logout=true" class="nav-link"> Déconnecter
                </a>
            </li>
        </ul>
    </div>
</nav>

<div class="container-fluid p-5 text-center text-white" style="background-image: url('../css/img.jpg'); background-size: cover; background-position: center; background-repeat: no-repeat; display: flex; flex-direction: column; justify-content: center; align-items: center; text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.7);">
    <h1>Bienvenue à la Bibliothèque en ligne</h1>
    <p>Explorez notre large collection de livres et gérez vos réservations.</p>
    <input type="text" id="searchInput" class="form-control w-50 mt-3" placeholder="Rechercher par titre" onkeyup="searchCards()">
</div>



</body>
</html>
