<?php
include "../db.php"; 

if (isset($_POST['submit'])) { 
    $id_livre = $_POST['id_livre'];
    $titre = $_POST['titre'];
    $auteur = $_POST['auteur'];
    $genre = $_POST['genre'];
    $annee_publication = $_POST['annee_publication'];
    $stock = $_POST['stock'];
    $imageName = $_FILES['images']['name'];
    $imageTmp = $_FILES['images']['tmp_name'];

    if (!empty($imageName)) {
        $images = rand(0, 1000) . "_" . $imageName;
        $uploadPath = "../uploads/images/" . $images;

        if (move_uploaded_file($imageTmp, $uploadPath)) {
            $sql = "UPDATE livres SET titre = ?, auteur = ?, genre = ?, annee_publication = ?, stock = ?, images = ? WHERE id_livre = ?";
            $stmt = $conn->prepare($sql);
            $res = $stmt->execute([$titre, $auteur, $genre, $annee_publication, $stock, $images, $id_livre]);
        }
    } else {
        $sql = "UPDATE livres SET titre = ?, auteur = ?, genre = ?, annee_publication = ?, stock = ? WHERE id_livre = ?";
        $stmt = $conn->prepare($sql);
        $res = $stmt->execute([$titre, $auteur, $genre, $annee_publication, $stock, $id_livre]);
    }

    if ($res) {
        $succ = "Livre modifié avec succès.";
        header("location: livres.php?modified");
    } else {
        $error = "Erreur lors de la modification du livre.";
    }
}

if (isset($_GET['modifier'])) {
    $id_livre = $_GET['modifier'];
    $sql = "SELECT * FROM livres WHERE id_livre = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$id_livre]);
    $livre = $stmt->fetch(PDO::FETCH_ASSOC);
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier un Livre</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include "said_bar.php"; ?>

<div class="content">
    <div class="w-50 bg-light rounded p-2 mx-auto">
        <h5 class="text-center">Modifier un Livre</h5><hr>

        <?php
        if (isset($error)) {
            echo "<div class='alert alert-danger'>$error</div>";
        } elseif (isset($succ)) {
            echo "<div class='alert alert-success'>$succ</div>";
        }
        ?>

        <form action="modifie.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id_livre" value="<?php echo $livre['id_livre']; ?>">

            <div class="mb-3">
                <label for="title" class="form-label">Titre</label>
                <input type="text" class="form-control" name="titre" value="<?php echo $livre['titre']; ?>" id="title" required>
            </div>
            <div class="mb-3">
                <label for="author" class="form-label">Auteur</label>
                <input type="text" class="form-control" name="auteur" value="<?php echo $livre['auteur']; ?>" id="author" required>
            </div>
            <div class="mb-3">
                <label for="editGenre" class="form-label">Genre</label>
                <select class="form-select" name="genre" id="editGenre" required>
                    <option value="<?php echo $livre['genre']; ?>" selected><?php echo $livre['genre']; ?></option>
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
                <input type="number" class="form-control" id="annee" name="annee_publication" value="<?php echo $livre['annee_publication']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="stock" class="form-label">Stock</label>
                <input type="number" class="form-control" id="stock" name="stock" value="<?php echo $livre['stock']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="image">Choisir une image (si vous souhaitez modifier l'image):</label>
                <input type="file" id="image" name="images" accept="image/*">
            </div>
            <div class="modal-footer">
                <input type="submit" name="submit" class="btn btn-warning" value="Modifier">
            </div>
        </form>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
