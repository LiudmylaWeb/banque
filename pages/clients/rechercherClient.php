<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modification du client</title>
    <link rel="stylesheet" href="/static/css/formstyle.css">
</head>

<body>

    <?php
    require('../../init.php');
    checkAcl('auth');
    include VIEWS_DIR . '/menu.php';
    // Récupération de la liste des clients
    $sql = "SELECT numClient, nom, prenom, adresse, mail, numtel, idSituation, dateNaissance FROM client";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $clients = $stmt->fetchAll();
    ?>

    <div class="container mt-5" style="max-width: 700px;">
        <form action="modifierClient.php" method="post" class="row g-3 rounded shadow">
            <legend class="text-warning">Les clients de la banque</legend>
            <div class="form-group">
                <label for="client" class="form-label">Sélectionnez le client à modifier :</label>
                <select name="numClient" id="client" class="form-control">
                    <?php foreach ($clients as $client) : ?>
                        <option value="<?php echo $client['numClient']; ?>">
                            <?php echo htmlspecialchars($client['nom']) . ' ' . htmlspecialchars($client['prenom']) . ' ' . htmlspecialchars($client['dateNaissance']); ?>
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