<?php
include "../db.php";
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_GET['supprimer'])) {
    $id = $_GET['supprimer'];
    if (filter_var($id, FILTER_VALIDATE_INT)) {
        $req_delete = "DELETE FROM membres WHERE id_membre = ?";
        $stmt = $conn->prepare($req_delete);
        if ($stmt->execute([$id])) {
            header("location:membres.php?delete=success");
            exit;
        } else {
            echo "<p class='text-danger'>Erreur lors de la suppression.</p>";
        }
    } else {
        echo "<p class='text-danger'>ID non valide.</p>";
    }
}

if (isset($_POST['submit'])) {
    $id_membre = $_POST['id_membre'];
    $nom = htmlspecialchars($_POST['nom']);
    $telephone = htmlspecialchars($_POST['telephone']);
    $email = htmlspecialchars($_POST['email']);
    $adresse = htmlspecialchars($_POST['adresse']);
    $passwordd =htmlspecialchars($_POST['passwordd']);

    $sql = "UPDATE membres SET nom = ?, telephone = ?, email = ?, adresse = ?, passwordd = ? WHERE id_membre = ?";
    $stmt = $conn->prepare($sql);
    $res = $stmt->execute([$nom, $telephone, $email, $adresse, $passwordd, $id_membre]);

    if ($res) {
        header("location:membres.php?modified=1");
        exit;
    } else {
        echo "<p class='text-danger'>Erreur lors de la modification du profil.</p>";
    }
}

$sql = "SELECT * FROM membres";
$stmt = $conn->prepare($sql);
$stmt->execute();
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (!$data) {
    echo "<p class='text-danger'>Aucun utilisateur trouvé.</p>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Membres</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
</head>

<body>
    <?php include "said_bar.php"; ?>
    <div class="content">
    <div class="container mt-5">
        <div class="table-responsive">
            <table id="membersTable" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Email</th>
                        <th>Téléphone</th>
                        <th>Adresse</th>
                        <th>Mot de passe</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($data as $row) { ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row["nom"]); ?></td>
                            <td><?php echo htmlspecialchars($row["email"]); ?></td>
                            <td><?php echo htmlspecialchars($row["telephone"]); ?></td>
                            <td><?php echo htmlspecialchars($row["adresse"]); ?></td>
                            <td><?php echo htmlspecialchars($row["passwordd"]); ?></td>
                            <td>
                                <button class="btn btn-warning btn-sm" data-toggle="modal" data-target="#editProfileModal-<?php echo $row['id_membre']; ?>">Modifier</button>
                                <a onclick="return confirm('Voulez-vous vraiment supprimer ?');" href="membres.php?supprimer=<?php echo $row['id_membre']; ?>">
                                    <button class="btn btn-danger btn-sm">Supprimer</button>
                                </a>
                            </td>
                        </tr>

                        <!-- Modal pour modifier -->
                        <div class="modal fade" id="editProfileModal-<?php echo $row['id_membre']; ?>" tabindex="-1" aria-labelledby="editProfileModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="editProfileModalLabel">Modifier le Profil</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">&times;</button>
                                    </div>
                                    <div class="modal-body">
                                        <form method="POST" action="">
                                            <input type="hidden" name="id_membre" value="<?php echo $row['id_membre']; ?>">
                                            <div class="form-group">
                                                <label>Nom</label>
                                                <input type="text" class="form-control" name="nom" value="<?php echo htmlspecialchars($row['nom']); ?>" required>
                                            </div>
                                            <div class="form-group">
                                                <label>Email</label>
                                                <input type="email" class="form-control" name="email" value="<?php echo htmlspecialchars($row['email']); ?>" required>
                                            </div>
                                            <div class="form-group">
                                                <label>Téléphone</label>
                                                <input type="tel" class="form-control" name="telephone" value="<?php echo htmlspecialchars($row['telephone']); ?>" required>
                                            </div>
                                            <div class="form-group">
                                                <label>Adresse</label>
                                                <input type="text" class="form-control" name="adresse" value="<?php echo htmlspecialchars($row['adresse']); ?>" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="editPassword" class="form-label">Mot de passe</label>
                                                <input type="text" class="form-control" name="passwordd" id="editPassword" value="<?php echo htmlspecialchars($row['passwordd']); ?>" >
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                                                <button type="submit" name="submit" class="btn btn-primary">Enregistrer</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#membersTable').DataTable();
        });
    </script>
</body>

</html>
