<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Créer un rrendez-vous</title>
    <link rel="stylesheet" href="/static/css/formstyle.css">
</head>
<body>
<?php
require('../../init.php');
checkAcl('auth');
include VIEWS_DIR . '/menu.php';
?>
    <div class="container mt-5" style="max-width: 700px;">
        <form action="/rendez-vous/sauvegarderRdv.php" method="post" class="row g-3 rounded shadow">
            <legend class="text-warning">Créer un Nouveau RDV</legend>
            <div class="form-group">
                <label for="dateRdv" class="form-label">Date du RDV:</label>
                <input type="date" class="form-control" id="dateRdv" name="dateRdv" required>
            </div>
            <div class="form-group">
                <label for="heureRdv" class="form-label">Heure de RDV :</label>
                <input type="number" class="form-control" id="heureRdv" name="heureRdv" min="7" max="19" required>
            </div>
            <div class="form-group">
                <label for="numEmploye" class="form-label">Numero du Conseiller :</label>
                <select name="numEmploye" class="form-control" id="numEmploye">
                    <?php
                    $sql = "SELECT numEmploye, nom FROM employe WHERE categorie = 'Conseiller' AND actif=1";
                    $stmt = $conn->prepare($sql);
                    $stmt->execute();
                    $conseillers = $stmt->fetchAll();
                    ?>
                    <?php foreach ($conseillers as $conseiller) : ?>
                        <option value="<?php echo $conseiller['numEmploye']; ?>">
                            <?php echo $conseiller['numEmploye'] . ' ' . $conseiller['nom'] ?>
                        </option>

                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="numClient" class="form-label">Numero du Client :</label>
                <select name="numClient" class="form-control" id="numClient">
                    <?php
                    $sql = "SELECT numClient, nom, prenom FROM client";
                    $stmt = $conn->prepare($sql);
                    $stmt->execute();
                    $clients = $stmt->fetchAll();
                    ?>
                    <?php foreach ($clients as $client) : ?>
                        <option value="<?php echo $client['numClient']; ?>">
                            <?php echo $client['numClient'] . ' ' . $client['nom'] . ' ' . $client['prenom'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="idMotif" class="form-label">Motif du RDV:</label>
                <select class="form-control" id="idMotif" name="idMotif" required>
                    <option value="">Sélectionner un motif</option>
                    <?php $sql = "SELECT * FROM motif";
                    $stmt = $conn->prepare($sql);
                    $stmt->execute();
                    $motifs = $stmt->fetchAll();
                    ?>
                    <?php foreach ($motifs as $motif) : ?>
                        <option value="<?php echo $motif['idMotif']; ?>">
                            <?php echo $motif['libelleMotif'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="d-grid gap-2 col-6 mx-auto">
                <button type="submit" class="btn btn-outline-warning">Planifier RDV</button>
            </div>
        </form>
    </div>
</body>

</html>