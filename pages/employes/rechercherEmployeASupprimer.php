<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supprimer un employé</title>
    <link rel="stylesheet" href="/static/css/formstyle.css">
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

    <form action="supprimerEmploye.php" method="post">
        <label for="employe">Choisir un employé à supprimer :</label>
        <select name="employe" id="employe">
            <?php foreach ($employes as $employe): ?>
                <option value="<?php echo $employe['numEmploye']; ?>">
                    <?php echo $employe['nom']; ?>
                </option>
            <?php endforeach; ?>
        </select>
        <button type="submit">Supprimer</button>
    </form>

</body>

</html>
