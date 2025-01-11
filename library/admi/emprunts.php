<?php 
include "../db.php";

if(isset($_POST['accepter'])){
    $id_emprunt = $_POST['id_emprunt'];

    $stmt = $conn->prepare("UPDATE `emprunts` SET `statut`='accepte' WHERE `id_emprunt` = $id_emprunt");
  
    $stmt->execute();
}

$succ = '';
$err = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_emprunt'])&&isset($_POST['annuler'])) {
    $id_emprunt = $_POST['id_emprunt'];

    try {
        $sql = "CALL GererRetour(:id_emprunt)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id_emprunt', $id_emprunt, PDO::PARAM_INT);
        $stmt->execute();
        $succ = "<div class='alert alert-success'>Le livre a été annulé avec succès et le stock a été mis à jour.</div>";
    
    } catch (PDOException $e) {
        $err = "<div class='alert alert-danger'>Erreur: " . $e->getMessage() . "</div>";
    }
}

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Membres</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <?php include "./said_bar.php" ?>
    <div class="content">
    <?php echo $succ; ?>


    <?php echo $err; ?>
        <div class="table-responsive">
            <table id="booksTable" class="table table-bordered">
                <thead>
                    <tr>
                        <th>Nom de l'emprunteur</th>
                        <th>Titre de livre</th>
                        <th>Date d'emprunt</th>
                        <th>Date de retour</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php
               
                $sql = "SELECT l.titre, e.date_emprunt, e.date_retour, e.id_emprunt, m.nom,e.statut
                    FROM livres l
                    JOIN emprunts e ON l.id_livre = e.id_livre
                    JOIN membres m ON e.id_membre = m.id_membre"; 
                $stmt = $conn->prepare($sql);
                $stmt->execute();

                
                if ($stmt->rowCount() > 0) {
                   
                    
                    while ($emp = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                    <tr>
                        <td><?php echo $emp['nom']; ?></td>
                        <td><?php echo $emp['titre']; ?></td>
                        <td><?php echo $emp['date_emprunt']; ?></td>
                        <td><?php echo $emp['date_retour'] ?: "Non retourné"; ?></td>
                        <td ><?php echo $emp['statut']; ?></td>
                        <td>
                        <form method="post" action="">
                            <input type="hidden" name="id_emprunt" value="<?php echo $emp['id_emprunt']; ?>">
                            <div class="d-flex gap-2">
                                <button type="submit" name="accepter" class="btn btn-info">Accepter</button>
                                <button type="submit" name="annuler" class="btn btn-danger">Annuler</button>
                            </div>
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

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>

    <script>
    $(document).ready(function() {
        $('#booksTable').DataTable();
    });
</script>

</body>

</html>
