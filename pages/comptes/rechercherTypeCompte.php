<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rechercher Type Compte</title>
    <link rel="stylesheet" href="/static/css/formstyle.css">
</head>

<body>
    <?php
    require('../../init.php');
    checkAcl('auth');
    include VIEWS_DIR . '/menu.php';
    // Récupération de la liste des types de compte
    $sql = "SELECT idCompte, nomTypeCompte FROM compte";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $typesCompte = $stmt->fetchAll();
    ?>
    <div class="container mt-5" style="max-width: 700px;">
        <form action="modifierTypeCompte.php" method="post" class="row g-3 rounded shadow">
            <legend class="text-warning">Les comptes de la banque</legend>
            <div class="form-group">
                <label for="idCompte" class="form-label">Choisir un type de compte à modifier :</label>
                <select name="idCompte" id="idCompte" class="form-control">
                    <?php foreach ($typesCompte as $typeCompte) : ?>
                        <option value="<?php echo $typeCompte['idCompte']; ?>">
                            <?php echo $typeCompte['nomTypeCompte']; ?>
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