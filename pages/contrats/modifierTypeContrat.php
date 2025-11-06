<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier le type du contrat</title>
    <link rel="stylesheet" href="/static/css/formstyle.css">
</head>

<body>
    <?php
    require('../../init.php');
    checkAcl('auth');
    include VIEWS_DIR . '/menu.php';

    $updateSuccessful = false;
    $deleteSuccessful = false;

    if (isset($_POST['numContrat'])) {
        $numContrat = $_POST['numContrat'];

        if (isset($_POST['action']) && $_POST['action'] === 'modifier') {
            $nomTypeContrat = $_POST['nomTypeContrat'];
            $description = $_POST['description'];

            $sql = "UPDATE contrat SET nomTypeContrat = ?, description = ? WHERE numContrat = ?";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$nomTypeContrat, $description, $numContrat]);

            $_SESSION['updateSuccess'] = true;
            $updateSuccessful = true;
        } elseif (isset($_POST['action']) && $_POST['action'] === 'supprimer') {
            $sql = "DELETE FROM contrat WHERE numContrat = ?";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$numContrat]);

            $_SESSION['deleteSuccess'] = true;
            $deleteSuccessful = true;
        } else {
            $sql = "SELECT * FROM contrat WHERE numContrat = ?";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$numContrat]);
            $contrat = $stmt->fetch();
        }
    }

    // Récupère les informations du compte après la mise à jour 
    if (isset($_POST['numContrat'])) {
        $numContrat = $_POST['numContrat'];
        $sql = "SELECT * FROM Contrat WHERE numContrat = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$numContrat]);
        $contrat = $stmt->fetch();
    }


    // Affiche une alerte si la mise à jour a été réussie
    if ($updateSuccessful) {
        echo '<script>alert("Les informations du contrat ont été mises à jour avec succès.");</script>';
    }

    // Affiche une alerte si la suppression a été réussie
    if ($deleteSuccessful) {
        echo '<script>alert("Le type de contrat a été supprimé avec succès.");</script>';
    }
    ?>
    <!-- Formulaire pour la mise à jour et la suppression des informations du contrat -->
    <div class="container mt-5" style="max-width: 700px;">
        <form action="modifierTypeContrat.php" method="post" name="monForm" class="row g-3 rounded shadow">
            <legend class="text-warning">MODIFICATION DU TYPE DE CONTRAT</legend>

            <!-- Champs du formulaire avec les informations à jour du contrat -->
            <div class="form-group">
                <input type="hidden" name="numContrat" value="<?php echo isset($contrat['numContrat']) ? htmlspecialchars($contrat['numContrat']) : ''; ?>">
            </div>

            <div class="form-group">
                <label for="nomTypeContrat" class="form-label">Nom du Contrat:</label>
                <input type="text" class="form-control" id="nomTypeContrat" name="nomTypeContrat" value="<?php echo isset($contrat['nomTypeContrat']) ? htmlspecialchars($contrat['nomTypeContrat']) : ''; ?>">
            </div>
            <div class="form-group">
            <label for="description" class="form-label">Description du Contrat:</label>
            <input type="text" class="form-control" id="description" name="description"
                value="<?php echo isset($contrat['description']) ? htmlspecialchars($contrat['description']) : ''; ?>">
            </div>
            <div class="d-grid gap-2 col-6 mx-auto">
                <button type="submit" name="action" value="modifier" class="btn btn-outline-warning">Mettre à jour</button>
            </div>
            <div class="d-grid gap-2 col-6 mx-auto">
                <button type="submit" name="action" value="supprimer" class="btn btn-outline-warning" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce type de contrat ?')">Supprimer</button>
            </div>
    </div>
    </form>
    </div>
</body>

</html>