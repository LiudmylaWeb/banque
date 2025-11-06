<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier Type Compte</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <?php
    require('../../init.php');
    checkAcl('auth');
    include VIEWS_DIR . '/menu.php';

    $updateSuccessful = false;
    $deleteSuccessful = false;

    if (isset($_POST['idCompte'])) {
        $idCompte = $_POST['idCompte'];

        if (isset($_POST['action']) && $_POST['action'] === 'modifier') {
            $nomTypeCompte = $_POST['nomTypeCompte'];
            $description = $_POST['description'];

            $sql = "UPDATE Compte SET nomTypeCompte = ? , description = ? WHERE idCompte = ?";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$nomTypeCompte, $description, $idCompte]);

            $_SESSION['updateSuccess'] = true;
            $updateSuccessful = true;
        } elseif (isset($_POST['action']) && $_POST['action'] === 'supprimer') {
            //vérification s'il existe encore des comptes clients qui possède ce type de compte
            $stmt = $conn->prepare("SELECT COUNT(*) FROM compteClient WHERE idCompte= ? ");
            $stmt->execute([$idCompte]);
            $count = $stmt->fetchColumn();

            if ($count == 0) {
                $sql = "DELETE FROM Compte WHERE idCompte = ?";
                $stmt = $conn->prepare($sql);
                $stmt->execute([$idCompte]);
                $_SESSION['deleteSuccess'] = true;
                $deleteSuccessful = true;
            } else {
                echo '<script>alert("Suppression du type de compte impossible tant qu\'il existe des comptes clients avec ce type de compte");</script>';
            }
        } else {
            $sql = "SELECT * FROM Compte WHERE idCompte = ?";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$idCompte]);
            $compte = $stmt->fetch();
        }
    }

     // Récupère les informations du compte après la mise à jour 
     if (isset($_POST['idCompte'])) {
        $idCompte = $_POST['idCompte'];
        $sql = "SELECT * FROM Compte WHERE idCompte = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$idCompte]);
        $compte = $stmt->fetch();
    }


    // Affiche une alerte si la mise à jour a été réussie
    if ($updateSuccessful) {
        echo '<script>alert("Les informations du compte ont été mises à jour avec succès.");</script>';
    }

    // Affiche une alerte si la suppression a été réussie
    if ($deleteSuccessful) {
        echo '<script>alert("Le type de compte a été supprimé avec succès.");</script>';
    }
    ?>
    <!-- Formulaire pour la mise à jour et la suppression des informations du compte -->
    <div class="container mt-5" style="max-width: 700px;">
        <form action="modifierTypeCompte.php" method="post" name='monForm' class="row g-3 rounded shadow">
            <legend class="text-warning">INFORMATION DU COMPTE</legend>
            <!-- Champs du formulaire avec les informations à jour du compte -->
            <div class="form-group">
                <input type="hidden" class="form-control" name="idCompte" value="<?php echo isset($compte['idCompte']) ? htmlspecialchars($compte['idCompte']) : ''; ?>">
            </div>
            <div class="form-group">
                <label for="nomTypeCompte" class="form-label">Nom du Compte:</label>
                <input type="text" class="form-control" id="nomTypeCompte" name="nomTypeCompte" value="<?php echo isset($compte['nomTypeCompte']) ? htmlspecialchars($compte['nomTypeCompte']) : ''; ?>">
            </div>
            <div class="form-group">
                <label for="description" class="form-label">Description du Compte:</label>
                <input type="text" class="form-control" id="description" name="description" value="<?php echo isset($compte['description']) ? htmlspecialchars($compte['description']) : ''; ?>">
            </div>
            <div class="d-grid gap-2 col-6 mx-auto">
                <button type="submit" class="btn btn-outline-warning" name="action" value="modifier">Mettre à jour</button>
            </div>
            <div class="d-grid gap-2 col-6 mx-auto">
                <button type="submit" class="btn btn-outline-warning" name="action" value="supprimer" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce type de compte ?')">Supprimer</button>
            </div>
        </form>
    </div>
</body>

</html>