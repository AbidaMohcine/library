<?php 
include "../db.php";
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion de Bibliothèque</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
   
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

    <link href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>
    <div class="sidebar" aria-label="Navigation">
        <div class="d-flex justify-content-center align-items-center mb-5">
            <img src="../css/logo.jpg" class="rounded" width="50px" height="50px" alt="Bibliothèque Logo">
            <h3 class="text-center text-white m-2">Bibliothèque</h3>
        </div>
        <a href="dashbord.php" class="text-white"><i class="fas fa-tachometer-alt"></i>  dashbord</a>
        <a href="livres.php" class="text-white"><i class="fas fa-book"></i> Gestion des Livres</a>
        <a href="membres.php" class="text-white" ><i class="fas fa-users"></i> Gestion des Membres</a>
        <a href="emprunts.php" class="text-white"><i class="fas fa-bookmark"></i> Gestion des Emprunts</a>
        <a href="../admin.php?logout=true" class="text-white"><i class="fas fa-sign-out-alt"></i> Déconnecter</a>
    </div>

    <div class="header">
        <h3> Gestion des  <?php
        echo ucfirst(str_replace(".php","", basename($_SERVER['PHP_SELF'])));
        ?></h3>
        <h5>Bienvenus, <span><?php 
            if (isset($_SESSION['username'])) {
                echo htmlspecialchars($_SESSION['username']); 
            } 
            ?></span>
        </h5>
    </div>

    <div class="content">
        
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
</body>

</html>
