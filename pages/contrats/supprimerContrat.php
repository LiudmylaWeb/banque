<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supprimer un contrat</title>
    <link rel="stylesheet" href="/static/css/formstyle.css">
</head>

<body>
    <?php
    require('../../init.php');
    checkAcl('auth');
    include VIEWS_DIR . '/menu.php';

    $deleteSuccessful = false;

    if (isset($_POST['numContrat']) && isset($_POST['action']) && $_POST['action'] === 'supprimer') {
        $numContrat = $_POST['numContrat'];

        $sql = "DELETE FROM ContratClient WHERE idContratClient = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$numContrat]);

        $_SESSION['deleteSuccess'] = true;
        $deleteSuccessful = true;
    } elseif (isset($_POST['numContrat'])) {
        $numContrat = $_POST['numContrat'];

        $sql = "SELECT cc.idContratClient, c.nomTypeContrat, cl.nom, cl.prenom
                FROM ContratClient cc
                INNER JOIN Contrat c ON cc.numContrat = c.numContrat
                INNER JOIN Client cl ON cc.numClient = cl.numClient
                WHERE cc.idContratClient = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$numContrat]);
        $contrat = $stmt->fetch();
    }

    // Affiche une alerte si la suppression a été réussie
    if ($deleteSuccessful) {
        echo '<script>alert("Le contrat a été supprimé avec succès.");</script>';
    }
    ?>

    <!-- Formulaire pour la suppression du contrat -->
    <div class="container mt-5" style="max-width: 700px;">
        <form action="supprimerContrat.php" method="post" class="row g-3 rounded shadow">
            <legend class="text-warning">Les contrats de la banque</legend>
            <!-- Champs du formulaire avec les informations du contrat à supprimer -->
            <div class="form-group">
                <label for="numContrat" class="form-label">Êtes-vous sûr de vouloir supprimer le contrat ?</label>
                <input type="hidden" class="form-control" name="numContrat" id="numContrat" value="<?php echo isset($contrat['idContratClient']) ? htmlspecialchars($contrat['idContratClient']) : ''; ?>">
                <?php echo isset($contrat['idContratClient']) ? "Contrat n°" . htmlspecialchars($contrat['idContratClient']) . " - " . htmlspecialchars($contrat['nomTypeContrat']) . " pour " . htmlspecialchars($contrat['nom']) . ' ' . htmlspecialchars($contrat['prenom']) : ''; ?>
            </div>
            <div class="d-grid gap-2 col-6 mx-auto">
                <button type="submit" name="action" value="supprimer" class="btn btn-outline-warning">Supprimer</button>
            </div>
        </form>
    </div>
</body>

</html>