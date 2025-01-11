<?php
include "../db.php"; 
session_start();

$sql_users = "SELECT COUNT(*) as total_users FROM membres";
$sql_books = "SELECT COUNT(*) as total_books FROM livres";
$sql_reviews = "SELECT COUNT(*) as total_reviews FROM avis";
$sql_emprunt = "SELECT COUNT(*) as total_emprunt FROM emprunts";

$stmt_users = $conn->query($sql_users);
$stmt_books = $conn->query($sql_books);
$stmt_reviews = $conn->query($sql_reviews);
$stmt_emprunt = $conn->query($sql_emprunt);

$total_users = $stmt_users->fetch(PDO::FETCH_ASSOC)['total_users'];
$total_books = $stmt_books->fetch(PDO::FETCH_ASSOC)['total_books'];
$total_reviews = $stmt_reviews->fetch(PDO::FETCH_ASSOC)['total_reviews'];
$total_emprunt = $stmt_emprunt->fetch(PDO::FETCH_ASSOC)['total_emprunt'];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de Bord Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include "said_bar.php"; ?>
<div class="content">
<div class="container mt-5">
    <div class="row">
        <div class="col-md-4">
            <div class="card text-white bg-primary mb-4">
                <div class="card-body">
                    <h1 class="text-center"><i class="fas fa-users"></i></h1>
                    <h5 class="card-title">Membres</h5>
                    <p class="card-text"><strong><?php echo $total_users; ?></strong> membres enregistrés.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-success mb-4">
                <div class="card-body">
                    <h1 class="text-center "><i class="fas fa-book"></i></h1>
                    <h5 class="card-title">Livres</h5>
                    <p class="card-text"><strong><?php echo $total_books; ?> </strong>livres disponibles.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-warning mb-4">
                <div class="card-body">
                    <h1 class="text-center"><i class="fas fa-comment"></i></h1>
                    <h5 class="card-title">Avis</h5>
                    <p class="card-text"><strong><?php echo $total_reviews; ?> </strong>avis donnés.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-secondary mb-4">
                <div class="card-body">
                    <h1 class="text-center"><i class="fas fa-bookmark"></i></h1>
                    <h5 class="card-title">Emprunts</h5>
                    <p class="card-text"><strong><?php echo $total_emprunt; ?> </strong>emprunts enregistrés.</p>
                </div>
            </div>
        </div>
    </div>

</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
