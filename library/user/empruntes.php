<?php
include "../db.php"; 
session_start();


$succ = '';
$err = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_emprunt'])) {
    $id_emprunt = $_POST['id_emprunt'];

    try {
        $sql = "CALL GererRetour(?)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$id_emprunt]);

        $succ = "<div class='alert alert-success'>Le livre a été retourné avec succès et le stock a été mis à jour.</div>";
    } catch (PDOException $e) {
        $err = "<div class='alert alert-danger'>Erreur: " . $e->getMessage() . "</div>";
    }
}
$message = '';

if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    unset($_SESSION['message']); 
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Emprunts</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include "header.php"; ?>

    <div class="container mt-5">
    <div class="mb-3">
        <?php if (!empty($message)) echo "<div class='alert alert-success'>$message</div>"; ?>
    </div>
        <?php echo $succ; ?>
        <?php echo $err; ?>

        <h2 class="text-center mb-4">Emprunts</h2>
        <h2 class="text-center mb-4"><?php
        ?></h2>


        <div class="table-responsive">
            <table id="booksTable" class="table table-bordered">
                <thead>
                    <tr>
                        <th>Titre de livre</th>
                        <th>Date d'emprunt</th>
                        <th>Date de retour</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                $user_id = $_SESSION['id_membre'];
                $sql = "SELECT l.titre, e.date_emprunt, e.date_retour, e.id_emprunt ,e.statut
                        FROM livres l
                        JOIN emprunts e ON l.id_livre = e.id_livre
                        WHERE e.id_membre = $user_id"; 
                $stmt = $conn->prepare($sql);
                $stmt->execute();

                if ($stmt->rowCount() > 0) {
                 
                    while ($emp = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                        <tr class="searchtable">
                            <td><?php echo $emp['titre']; ?></td>
                            <td><?php echo $emp['date_emprunt']; ?></td>
                            <td><?php echo $emp['date_retour'] ?: "Non retourné"; ?></td>
                            <td><?php echo $emp['statut'] ?></td>
                            <td>
                                <form method="post" action="">
                                    <input type="hidden" name="id_emprunt" value="<?php echo $emp['id_emprunt']; ?>">
                                    <button type="submit" class="btn btn-danger">Retour</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile;
                } else {
                    echo "<tr><td colspan='5' class='text-center'>Aucun emprunt trouvé.</td></tr>";
                }
                ?>
                </tbody>
            </table>
         
        </div>
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
            let rows = document.querySelectorAll("#booksTable tbody tr");

            rows.forEach(function(row) {
                let title = row.querySelector("td:first-child").textContent.toLowerCase();
                if (title.includes(input)) {
                    row.style.display = ""; 
                } else {
                    row.style.display = "none"; 
                }
            });
        }
    </script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
