<?php
include "../db.php"; 


if (isset($_POST['genres'])) {
    $gnr = $_POST['nom'];
    $sql = "INSERT INTO genre (nom) VALUES ('$gnr')";
    $stmt = $conn->prepare($sql);
    $res = $stmt->execute();
}


if (isset($_POST['submit'])) { 
    $titre = $_POST['titre'];
    $auteur = $_POST['auteur'];
    $genre = $_POST['genre'];
    $annee_publication = $_POST['annee_publication'];
    $stock = $_POST['stock'];
    $imageName = $_FILES['images']['name'];
    $imageTmp = $_FILES['images']['tmp_name'];

    $images = rand(0, 1000) . "_" . $imageName;
    $uploadPath = "../uploads/images/" . $images;

    if (move_uploaded_file($imageTmp, $uploadPath)) {
        $sql = "CALL AjouterLivre('$titre', '$auteur', '$genre', $annee_publication, $stock, '$images')";
        $stmt = $conn->prepare($sql);
        $res = $stmt->execute();
        if ($res) {
            $succ = "Livre ajouté avec succès.";
        } else {
            $error = "Erreur lors de l'ajout du livre.";
        }
    } else {
        echo "Erreur lors du téléchargement de l'image.";
    }
}


if (isset($_GET['supprimer_stock'])) {
    $id_stock = $_GET['supprimer_stock'];
    $req_delete = "DELETE FROM livres WHERE id_livre = $id_stock";
    $stmt = $conn->prepare($req_delete);
    $stmt->execute();
    header("location:livres.php?delete");
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
<?php include "said_bar.php"; ?>
<div class="content">
    <div class="w-25 bg-light rounded p-2">
        <b class="text-center">Ajout de Genre</b>
        <form action="livres.php" method="POST" id="genreForm">
            <div class="position-relative">
                <label for="genre" class="form-label">Nom du Genre:</label>
                <div class="input-group mb-2">
                    <input type="text" class="form-control" id="genre" name="nom" placeholder="Entrez le nom du genre" required>
                </div>
                <button type="submit" name="genres" class="btn btn-success">Ajouter</button>
            </div>
        </form>
    </div>

    <header class="d-flex justify-content-end align-items-center mb-3">
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addModal"><i class="fas fa-plus"></i> Ajouter un Livre</button>
    </header>

    <div class="table-responsive">
        <table id="booksTable" class="table table-bordered">
            <thead>
                <tr>
                    <th>Titre</th>
                    <th>Auteur</th>
                    <th>Genre</th>
                    <th>Année Publication</th>
                    <th>Stock</th>
                    <th>Image</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            <?php
            $sql = "SELECT * FROM livres ";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            if ($stmt->rowCount() > 0) {
                while ($livre = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                    <tr>
                        <td><?php echo $livre['titre'] ?></td>
                        <td><?php echo $livre['auteur'] ?></td>
                        <td><?php echo $livre['genre'] ?></td>
                        <td><?php echo $livre['annee_publication'] ?></td>
                        <td><?php echo $livre['stock'] ?></td>
                        <td><img src="../uploads/images/<?php echo $livre['images']; ?>" alt="Logo" class="img-fluid" style="width: 100%; height: 60px; object-fit: cover;"></td>
                        <td>
                            <a href="modifie.php?modifier=<?php echo $livre['id_livre']; ?>" ><button class="btn btn-warning btn-sm"><i class="fas fa-edit"></i> Modifier</button></a>
                            <a onclick="return confirm('Voulez-vous vraiment supprimer?');" href="livres.php?supprimer_stock=<?php echo $livre['id_livre']; ?>">
                                <button class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i> Supprimer</button>
                            </a>
                        </td>
                    </tr>
                <?php endwhile;
            } ?>
            </tbody>
        </table>
    </div>
</div>


<div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addModalLabel">Ajouter un Livre</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <?php
                if (isset($error)) {
                    echo "<div class='alert alert-danger'>$error</div>";
                } elseif (isset($succ)) {
                    echo "<div class='alert alert-success'>$succ</div>";
                }
                ?>
                <form id="addBookForm" method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="title" class="form-label">Titre</label>
                        <input type="text" class="form-control" name="titre" id="title" required>
                    </div>
                    <div class="mb-3">
                        <label for="author" class="form-label">Auteur</label>
                        <input type="text" class="form-control" name="auteur" id="author" required>
                    </div>
                    <div class="mb-3">
                        <label for="editGenre" class="form-label">Genre</label>
                        <select class="form-select" name="genre" id="editGenre" required>
                            <option value=""></option>
                            <?php
                            $sql = "SELECT * FROM genre";
                            $stmt = $conn->prepare($sql);
                            $stmt->execute();
                            while ($genres = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                                <option value="<?php echo $genres['nom']; ?>"><?php echo $genres['nom']; ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="annee" class="form-label">Année Publication</label>
                        <input type="number" class="form-control" id="annee" name="annee_publication" required>
                    </div>
                    <div class="mb-3">
                        <label for="stock" class="form-label">Stock</label>
                        <input type="number" class="form-control" id="stock" name="stock" required>
                    </div>
                    <div class="mb-3">
                        <label for="image">Choisir une image :</label>
                        <input type="file" id="image" name="images" accept="image/*">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                        <input type="submit" name="submit" class="btn btn-primary" value="Ajouter">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#booksTable').DataTable();
    });
</script>
</body>
</html>
