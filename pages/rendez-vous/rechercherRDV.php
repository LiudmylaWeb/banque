<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Modifier un rendez-vous</title>
    <link rel="stylesheet" href="/static/css/formstyle.css">
</head>

<body>
    <?php
    require('../../init.php');
    checkAcl('auth');
    include VIEWS_DIR . '/menu.php';

    // Récupération de la liste des rendez-vous avec les détails clients
    $sql = "SELECT rdv.numRdv, rdv.dateRdv, rdv.heureRdv, client.nom AS client_nom, client.prenom AS client_prenom
            FROM rdv
            INNER JOIN client ON rdv.numclient = client.numClient";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $Rdv = $stmt->fetchAll();
    ?>
    <div class="container mt-5" style="max-width: 700px;">
        <form action="modifierRDV.php" method="post" class="row g-3 rounded shadow">
            <legend class="text-warning">Les rdv avec nos conseillers</legend>
            <div class="form-group">
                <label for="rdv" class="form-label">Sélectionnez le rendez-vous à modifier :</label>
                <select name="numRdv" id="numRdv" class="form-control">
                    <?php foreach ($Rdv as $RDV) : ?>
                        <option value="<?php echo $RDV['numRdv']; ?>">
                            <?php echo "RDV du " . $RDV['dateRdv'] . " à " . $RDV['heureRdv'] . "h avec " . $RDV['client_nom'] . " " . $RDV['client_prenom']; ?>
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