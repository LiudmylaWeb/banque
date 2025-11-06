<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier un employé</title>
    <link rel="stylesheet" href="/static/css/formstyle.css">
</head>
</head>

<body>
    <?php
    require('../../init.php');
    checkAcl('auth');
    include VIEWS_DIR . '/menu.php';

    // Récupération de la liste des employés
    $sql = "SELECT numEmploye, nom, categorie FROM employe";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $employes = $stmt->fetchAll();
    ?>
    <div class="container mt-5" style="max-width: 700px;">
        <form action="modifierEmploye.php" method="post" class="row g-3 rounded shadow">
            <legend class="text-warning">Les employes de la banque</legend>
            <div class="form-group">
                <label for="employe" class="form-label">Choisir un employé à modifier :</label>
                <select name="numEmploye" id="employe" class="form-control">
                    <?php foreach ($employes as $employe) : ?>
                        <option value="<?php echo $employe['numEmploye']; ?>">
                            <?php echo $employe['nom']; ?>
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