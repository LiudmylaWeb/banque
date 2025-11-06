<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier Motif</title>
    <link rel="stylesheet" href="/static/css/formstyle.css">
</head>

<body>
    <?php
    require('../../init.php'); // Assurez-vous que init.php inclut la configuration de la base de données ($conn)
    checkAcl('auth');
    include VIEWS_DIR . '/menu.php';

    $updateSuccessful = false;
    $deleteSuccessful = false;

    if (isset($_POST['idMotif'])) {
        $idMotif = $_POST['idMotif'];

        if (isset($_POST['action']) && $_POST['action'] === 'modifier') {
            $libelleMotif = $_POST['libelleMotif'];
            $listePieces = $_POST['listePieces'];

            $sql = "UPDATE motif SET libelleMotif = ?, listePieces = ? WHERE idMotif = ?";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$libelleMotif, $listePieces, $idMotif]);

            $_SESSION['updateSuccess'] = true;
            $updateSuccessful = true;
        } elseif (isset($_POST['action']) && $_POST['action'] === 'supprimer') {
            //transfert des motifs sur les anciens RDV
            $idtransfert = $_POST['idtransfert'];
            $sql = "UPDATE rdv SET idMotif= ? WHERE idMotif = ?";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$idtransfert, $idMotif]);

            $sql = "DELETE FROM motif WHERE idMotif = ?";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$idMotif]);

            $_SESSION['deleteSuccess'] = true;
            $deleteSuccessful = true;
        }

        // Sélectionner à nouveau le motif après la mise à jour ou la suppression
        $sql = "SELECT * FROM motif WHERE idMotif = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$idMotif]);
        $motif = $stmt->fetch();
    }

    // Affiche une alerte si la mise à jour a été réussie
    if ($updateSuccessful) {
        echo '<script>alert("Les informations du motif ont été mises à jour avec succès.");</script>';
    }

    // Affiche une alerte si la suppression a été réussie
    if ($deleteSuccessful) {
        echo '<script>alert("Le transfert de motifs des rendez-vous et la suppression du motif a été effectuée avec succès.");</script>';
    }
    ?>
    <!-- Formulaire pour la mise à jour et la suppression des informations du motif -->
    <div class="container mt-5" style="max-width: 700px;">
        <form action="modifierPiece.php" method="post" name='monForm' class="row g-3 rounded shadow">
            <legend class="text-warning">MOTIF DE RDV ET PIECES CORRESPONDANTES</legend>
            <!-- Champs du formulaire avec les informations à jour du motif -->
            <div class="form-group">
                <input type="hidden" name="idMotif" value="<?php echo isset($motif['idMotif']) ? htmlspecialchars($motif['idMotif']) : ''; ?>">
            </div>

            <div class="form-group">
                <label for="libelleMotif" class="form-label">Libellé du Motif:</label>
                <input type="text" class="form-control" id="libelleMotif" name="libelleMotif" value="<?php echo isset($motif['libelleMotif']) ? htmlspecialchars($motif['libelleMotif']) : ''; ?>">
            </div>
            <div class="form-group">
                <label for="listePieces" class="form-label">Liste des Pièces:</label>
                <input type="text" class="form-control" id="listePieces" name="listePieces" value="<?php echo isset($motif['listePieces']) ? htmlspecialchars($motif['listePieces']) : ''; ?>">
            </div>
            <?php
            // Récupération de la liste des motifs sauf le motif actuel
            $sql = "SELECT idMotif, libelleMotif FROM motif WHERE idMotif<>?";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$idMotif]);
            $idtransfert = $stmt->fetchAll();
            //liste déroulante du nouveau motif à afficher pour les rendez-vous archiver
            ?>
            <div class="form-group">
                <label for="idtransfert" class="form-label">Sélectionnez le nouveau motif à afficher (pour la suppression du motif actuel) :</label>
                <select name="idtransfert" id="idtransfert" class="form-control">
                    <?php foreach ($idtransfert as $idtransferts) : ?>
                        <option value="<?php echo $idtransferts['idMotif']; ?>">
                            <?php echo $idtransferts['libelleMotif']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="d-grid gap-2 col-6 mx-auto">
                <button type="submit" class="btn btn-outline-warning" name="action" value="modifier">Mettre à jour</button>
            </div>
            <div class="d-grid gap-2 col-6 mx-auto">
                <button type="submit" class="btn btn-outline-warning" name="action" value="supprimer" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce motif ?')">Supprimer</button>
            </div>
        </form>
    </div>
</body>

</html>