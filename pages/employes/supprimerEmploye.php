<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supprimer un employé</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <?php
    require('../../init.php');
    checkAcl('auth');
    include VIEWS_DIR . '/menu.php';

    $deleteSuccessful = false;

    if (isset($_POST['numEmploye']) && isset($_POST['action']) && $_POST['action'] === 'supprimer') {
        $numEmploye = $_POST['numEmploye'];

        $sql = "DELETE FROM employe WHERE numEmploye = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$numEmploye]);

        $_SESSION['deleteSuccess'] = true;
        $deleteSuccessful = true; 
    } elseif (isset($_POST['numEmploye'])) {
        $numEmploye = $_POST['numEmploye'];

        $sql = "SELECT * FROM employe WHERE numEmploye = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$numEmploye]);
        $employe = $stmt->fetch();
    }

    // Affiche une alerte si la suppression a été réussie
    if ($deleteSuccessful) {
        echo '<script>alert("L\'employé a été supprimé avec succès.");</script>';
    }
    ?>
    <!-- Formulaire pour la suppression de l'employé -->
    <form action="supprimerEmploye.php" method="post" name='monForm'>

        <!-- Champs du formulaire avec les informations de l'employé à supprimer -->
        <p>
            <label for="numEmploye">Etes-vous sur de vouloir supprimer :</label>
            <input type="hidden" name="numEmploye" id="numEmploye" value="<?php echo isset($employe['numEmploye']) ? htmlspecialchars($employe['numEmploye']) : ''; ?>">
        </p>

        <p>
            <label for="nom">Nom:</label>
            <input type="text" id="nom" name="nom" value="<?php echo isset($employe['nom']) ? htmlspecialchars($employe['nom']) : ''; ?>" readonly>
        </p>

        <p>
            <label for="login">Login:</label>
            <input type="text" id="login" name="login" value="<?php echo isset($employe['login']) ? htmlspecialchars($employe['login']) : ''; ?>" readonly>
        </p>
        
        <p>
            <label for="categorie">Catégorie:</label>
            <input type="text" id="categorie" name="categorie" value="<?php echo isset($employe['categorie']) ? htmlspecialchars($employe['categorie']) : ''; ?>" readonly>
        </p>
        
        <p>
            <a href="../..">Page précédente</a>
            <button type="submit" name="action" value="supprimer">Supprimer</button>
        </p>
    </form>
</body>

</html>
