<?php
include "../db.php";
session_start(); 

if (!isset($_SESSION['id_membre'])) {
    header("Location: accueil.php");
    exit();
}

if (isset($_POST['submit'])) {
    $id_livre = $_POST['id_livre'];
    $id_membre = $_SESSION['id_membre']; 
    $commentaire = htmlspecialchars($_POST['commentaire']);
    $note = $_POST['note'];

    if ($id_livre == "" || $commentaire == "" || $note == "") {
        $_SESSION['error'] = "Tous les champs sont obligatoires.";
        header("Location: avis.php");
        exit();
    }

    if ($note < 1 || $note > 5) {
        $_SESSION['error'] = "La note doit être entre 1 et 5.";
        header("Location: avis.php");
        exit();
    }

   
    $sql = "INSERT INTO avis (id_livre, id_membre, commentaire, note) 
            VALUES (:id_livre, :id_membre, :commentaire, :note)";
    $stmt = $conn->prepare($sql);
    $res = $stmt->execute([
        ':id_livre' => $id_livre,
        ':id_membre' => $id_membre,
        ':commentaire' => $commentaire,
        ':note' => $note
    ]);

    if ($res) {
        $_SESSION['message'] = "Avis ajouté avec succès.";
        header("Location: accueil.php");
        exit();
    } else {
        $_SESSION['error'] = "Erreur lors de l'ajout de l'avis.";
        header("Location: avis.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Commentaires et Notes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include "header.php"; ?>
<div class="container mt-5">
    <h2 class="text-center mb-4">Commentaires et Notes</h2>
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
    <?php endif; ?>
    <?php if (isset($_SESSION['message'])): ?>
        <div class="alert alert-success"><?php echo $_SESSION['message']; unset($_SESSION['message']); ?></div>
    <?php endif; ?>
    <div class="card">
        <div class="card-body">
            <form method="POST">
                <div class="mb-3">
                    <label for="bookTitle" class="form-label">Titre du livre</label>
                    <select class="form-select" name="id_livre" id="bookTitle" required>
                        <option value="">Sélectionnez un livre</option>
                        <?php
                        $user_id = $_SESSION['id_membre'];
                        $sql = "SELECT l.id_livre, l.titre 
                                FROM livres l
                                JOIN emprunts e ON l.id_livre = e.id_livre
                                WHERE e.id_membre = :user_id"; 
                        $stmt = $conn->prepare($sql);
                        $stmt->execute([':user_id' => $user_id]);

                        if ($stmt->rowCount() > 0) {
                            while ($emp = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                                <option value="<?php echo $emp['id_livre']; ?>"><?php echo htmlspecialchars($emp['titre']); ?></option>
                            <?php endwhile;
                        } else {
                            echo '<option value="">Aucun livre emprunté.</option>';
                        }
                        ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="rating" class="form-label">Note</label>
                    <input class="form-control" name="note" id="rating" type="number" min="1" max="5" required>
                </div>

                <div class="mb-3">
                    <label for="comment" class="form-label">Commentaire</label>
                    <textarea class="form-control" id="comment" rows="4" name="commentaire" required></textarea>
                </div>

                <div class="text-center">
                    <button type="submit" name="submit" class="btn btn-primary">Envoyer</button>
                </div>
            </form>
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
