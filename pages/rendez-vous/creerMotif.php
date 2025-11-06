<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Créer un motif</title>
    <link rel="stylesheet" href="/static/css/formstyle.css">
</head>

<body>
    <?php
    require('../../init.php');
    checkAcl('auth');
    include VIEWS_DIR . '/menu.php'; // Inclusion du menu

    $createSuccessful = false;

    if (isset($_POST['action']) && !empty($_POST['libellemotif']) && !empty($_POST['listepiece'])) {
        $libellemotif = $_POST['libellemotif'];
        $listepiece = $_POST['listepiece'];
        $sql = "INSERT INTO motif (libelleMotif,listePieces) VALUES (?,?)";
        $res = $conn->prepare($sql);
        if ($res->execute([$libellemotif, $listepiece])) {
            $createSuccessful = true;
        }
    }
    // Affiche une alerte si la création a été réussie
    if ($createSuccessful) {
        echo '<script>alert("Le nouveau motif a été créé avec succès.");</script>';
    }
    ?>

    <!-- Formulaire pour la création du motif -->
    <div class="container mt-5" style="max-width: 700px;">
        <form action="creerMotif.php" method="post" name="monForm" class="row g-3 rounded shadow">
            <legend class="text-warning">Les motifs du rdv</legend>
            <div class="form-group">
                <label for="libellemotif" class="form-label">Libellé du motif :</label>
                <input type="text" name="libellemotif" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="listepiece" class="form-label">Liste des pièces à fournir : </label>
                <textarea name="listepiece" class="form-control" required></textarea>
            </div>
            <div class="d-grid gap-2 col-6 mx-auto">
                <button type="submit" name="action" value="Créer" class="btn btn-outline-warning">Créer le motif</button>
            </div>
        </form>
    </div>
</body>

</html>