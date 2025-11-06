<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Modifier un motif</title>
    <link rel="stylesheet" href="/static/css/formstyle.css">
</head>

<body>
    <?php
    require('../../init.php');
    checkAcl('auth');
    include VIEWS_DIR . '/menu.php';
    // Récupération de la liste des motifs
    $sql = "SELECT idMotif, libelleMotif FROM motif";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $motifs = $stmt->fetchAll();
    ?>
    <div class="container mt-5" style="max-width: 700px;">
        <form action="modifierPiece.php" method="post" class="row g-3 rounded shadow">
            <legend class="text-warning">Les motifs du rdv</legend>
            <label for="motif">Sélectionnez le motif à modifier :</label>
            <div class="form-group">
                <select name="idMotif" id="motif" class="form-control">
                    <?php foreach ($motifs as $motif) : ?>
                        <option value="<?php echo $motif['idMotif']; ?>">
                            <?php echo $motif['libelleMotif']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="d-grid gap-2 col-6 mx-auto">
                <button type="submit" class="btn btn-outline-warning">Modifier</button>
            </div>
        </form>
    </div>
</body>
</html>