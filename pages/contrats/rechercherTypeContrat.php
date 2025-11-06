<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rechercher un type de contrat</title>
    <link rel="stylesheet" href="/static/css/formstyle.css">
</head>

<body>
    <?php
    require('../../init.php');
    checkAcl('auth');
    include VIEWS_DIR . '/menu.php';
    // Récupération de la liste des contrats
    $sql = "SELECT numContrat, nomTypeContrat FROM contrat";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $contrats = $stmt->fetchAll();
    ?>
    <div class="container mt-5" style="max-width: 700px;">
        <form action="modifierTypeContrat.php" method="post">
            <legend class="text-warning">Les contrats de la banque</legend>
            <div class="form-group">
                <label for="contrat">Choisir un contrat à modifier :</label>
                <select name="numContrat" id="numContrat" class="form-control">
                    <?php foreach ($contrats as $contrat) : ?>
                        <option value="<?php echo $contrat['numContrat']; ?>">
                            <?php echo $contrat['nomTypeContrat']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="d-grid gap-2 col-6 mx-auto">
                <button type="submit" class="btn btn-outline-warning">Modifier</button>
            </div>
        </form>
</body>

</html>