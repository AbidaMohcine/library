<?php 
include "../db.php";
session_start(); 

if (!isset($_SESSION['id_membre'])) {
    die("Erreur : Vous devez être connecté pour effectuer une réservation.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $id_livre = $_POST['id_livre'];
        $id_membre = $_SESSION['id_membre']; 
        $date_emprunt = $_POST['date_emprunt'];
        $date_retour = $_POST['date_retour'];
        if (empty($date_emprunt)) {
            throw new Exception("La date d'emprunt est obligatoire.");
        }
        $sql = "CALL ReserverLivre(?, ?, ?, ?,?)";
        $stmt = $conn->prepare($sql);
        $result = $stmt->execute([$id_livre, $id_membre, $date_emprunt,$date_retour,"en réserve"]);

        if ($result) {
            $_SESSION['message'] = "Réservation effectuée avec succès.";
            header("Location: empruntes.php");
            exit();
        } else {
            throw new Exception("Erreur lors de la réservation.");
        }
    } catch (PDOException $e) {
        $error = "Erreur PDO : " . $e->getMessage();
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}
?>


<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réservation - Bibliothèque</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <?php include "header.php"; ?>

    <div class="container my-5">
        <h2 class="text-center mb-4">Les listes des livres</h2>
        
    
        <div class="row">
            <?php
            $sql = "SELECT DISTINCT livres.* 
                    FROM livres
                    ORDER BY livres.id_livre DESC";
            $stmt = $conn->prepare($sql);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                while ($livre = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                    <div class="col-md-4 mb-5">
                        <div class="card">
                            <img src="../uploads/images/<?php echo $livre['images']; ?>" class="card-img-top" style="width: 100%; height: 200px; object-fit: cover;" alt="image">
                            <div class="card-body">
                                <h5 class="card-title"><b>Titre de livre: </b><?php echo $livre['titre']; ?></h5>
                                <p class="card-text"><b>Nom de auteur: </b><?php echo $livre['auteur']; ?></p>
                                <p class="card-text"><b>Genre de livre: </b><?php echo $livre['genre']; ?></p>
                                <p class="card-text"><b>Totale de livre: </b><?php echo $livre['stock']; ?></p>
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#reservationModal<?php echo $livre['id_livre']; ?>">
                                    Réserver
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="modal fade" id="reservationModal<?php echo $livre['id_livre']; ?>" tabindex="-1" aria-labelledby="reservationModalLabel<?php echo $livre['id_livre']; ?>" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="reservationModalLabel<?php echo $livre['id_livre']; ?>">Formulaire de réservation</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">

                                <form method="POST" onsubmit="return validateDates()">
                                    <input type="hidden" name="id_livre" value="<?php echo $livre['id_livre']; ?>"> 
                                    <input type="hidden" name="id_membre" value="<?php echo htmlspecialchars($_SESSION['id_membre']); ?>">

                                    <div class="mb-3">
                                        <label for="titre" class="form-label">Titre de livre</label>
                                        <input type="text" class="form-control" name="titre" id="titre" value="<?php echo $livre['titre']; ?>" disabled required>
                                    </div>

                                    <div class="mb-3">
                                        <label for="borrowDate<?php echo $livre['id_livre']; ?>" class="form-label">Date d'emprunt</label>
                                        <input type="date" class="form-control" name="date_emprunt" id="borrowDate<?php echo $livre['id_livre']; ?>" required>
                                    </div>

                                    <div class="mb-3">
                                        <label for="returnDate<?php echo $livre['id_livre']; ?>" class="form-label">Date de retour</label>
                                        <input type="date" class="form-control" name="date_retour" id="returnDate<?php echo $livre['id_livre']; ?>" required>
                                    </div>

                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                                        <button type="submit" class="btn btn-primary">Confirmer la réservation</button>
                                    </div>
                                </form>

                                <script>
                                    function validateDates() {
                                        var borrowDate = document.getElementById('borrowDate<?php echo $livre['id_livre']; ?>').value;
                                        var returnDate = document.getElementById('returnDate<?php echo $livre['id_livre']; ?>').value;

                                        if (borrowDate && returnDate) {
                                            var borrowDateObj = new Date(borrowDate);
                                            var returnDateObj = new Date(returnDate);

                                           
                                            if (borrowDateObj >= returnDateObj) {
                                                alert('La date d\'emprunt doit être antérieure à la date de retour.');
                                                return false; 
                                            }

                                            
                                            var diffTime = returnDateObj - borrowDateObj;
                                            var diffDays = diffTime / (1000 * 3600 * 24); 

                                            if (diffDays > 30) {
                                                alert('La date de retour ne peut pas dépasser 30 jours après la date d\'emprunt.');
                                                return false; 
                                            }
                                        }
                                        return true; 
                                    }
                                </script>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endwhile;
            } ?> 
        </div>

 <hr>
 <h2 class="text-center m-5">Avis</h2>
<div class="row">
    <?php
        $sql = "SELECT a.*, l.titre, e.date_emprunt 
                FROM avis a
                JOIN livres l ON a.id_livre = l.id_livre
                LEFT JOIN emprunts e ON a.id_livre = e.id_livre AND a.id_membre = e.id_membre";
        $stmt = $conn->prepare($sql);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            while ($avs = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                <div class="col-md-2">
                    <div class="card review-card">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($avs['titre']); ?></h5>
                            <p class="card-text"><?php echo htmlspecialchars($avs['commentaire']); ?></p>
                            <div class="d-flex justify-content-between">
                                <small class="text-muted "><?php echo htmlspecialchars($avs['note']); ?></small>
                                <small class="text-muted"><?php echo htmlspecialchars($avs['date_emprunt'] ); ?></small>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endwhile;
        } else {
            echo "<p>Aucun avis trouvé.</p>";
        }
    ?>
</div>


    <div class="container">
        <hr>
        <div class="text-center">
            <p><b>Tous droits réservés 2024</b></p>
        </div>
    </div>

    <script>
    function searchCards() {
        let input = document.getElementById("searchInput").value.toLowerCase();
        let cards = document.querySelectorAll(".col-md-4");
        
        cards.forEach(function(card) {
            let title = card.querySelector(".card-title").textContent.toLowerCase();
            if (title.includes(input)) {
                card.style.display = ""; 
            } else {
                card.style.display = "none"; 
            }
        });
    }
    </script>
 <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
