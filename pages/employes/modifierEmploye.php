<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier ou supprimer un employé</title>
    <link rel="stylesheet" href="/static/css/formstyle.css">
</head>

<body>
    <?php
    require('../../init.php');
    checkAcl('auth');
    include VIEWS_DIR . '/menu.php';

    $updateSuccessful = false;

    if (isset($_POST['numEmploye'])) {
        $numEmploye = $_POST['numEmploye'];
        if (isset($_POST['action']) && $_POST['action'] === 'modifier') {
            $nom = $_POST['nom'];
            $login = $_POST['login'];
            $motDePasse = $_POST['motDePasse'];
            $categorie = $_POST['categorie'];
            $actif = $_POST['actif'];

            if (!empty($motDePasse)) {
                $hashedPassword = password_hash($motDePasse, PASSWORD_DEFAULT);
            } else {
                $sql = "SELECT motDePasse FROM employe WHERE numEmploye = ?";
                $stmt = $conn->prepare($sql);
                $stmt->execute([$numEmploye]);
                $hashedPassword = $stmt->fetchColumn();
            }

            $sql = "UPDATE employe SET nom = ?, login = ?, motDePasse = ?, categorie = ?, actif = ? WHERE numEmploye = ?";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$nom, $login, $hashedPassword, $categorie, $actif, $numEmploye]);

            $_SESSION['updateSuccess'] = true;
            $updateSuccessful = true;
        }else{
            //récupération des infos de l'employé
        $sql = "SELECT * FROM employe WHERE numEmploye = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$numEmploye]);
        $employe = $stmt->fetch();
        }
    }

    // Affiche une alerte si la mise à jour a été réussie
    if ($updateSuccessful == true) {
        echo '<script>alert("Les informations de l\'employé ont été mises à jour avec succès.");</script>';
    }

    ?>
    <!-- Formulaire pour la mise à jour et la suppression des informations de l'employé -->
    <div class="container mt-5" style="max-width: 700px;">
        <form action="modifierEmploye.php" method="post" name="monForm" class="row g-3 rounded shadow">
            <legend class="text-warning">INFORMATION DE L'EMPLOYE</legend>
            <!-- Champs du formulaire avec les informations à jour de l'employé -->
            <div class="form-group">
                <input type="hidden" class="form-control" name="numEmploye" value="<?php echo isset($employe['numEmploye']) ? htmlspecialchars($employe['numEmploye']) : ''; ?>">
            </div>
            <div class="form-group">
                <label for="nom" class="form-label">Nom:</label>
                <input type="text" class="form-control" id="nom" name="nom" value="<?php echo isset($employe['nom']) ? htmlspecialchars($employe['nom']) : ''; ?>">
            </div>
            <div class="form-group col-md-6">
                <label for="login" class="form-label">Login:</label>
                <input type="text" class="form-control" id="login" name="login" minlength="6" maxlength="20" value="<?php echo isset($employe['login']) ? htmlspecialchars($employe['login']) : ''; ?>">
            </div>
            <div class="form-group col-md-6">
                <label for="motDePasse" class="form-label">Mot de Passe (laissez vide si inchangé):</label>
                <input type="password" class="form-control" id="motDePasse" name="motDePasse" minlength="6" maxlength="20">
            </div>
            <div class="form-group">
                <label for="categorie" class="form-label">Catégorie:</label>
                <select id="categorie" name="categorie" class="form-control">
                    <option value="Conseiller" <?php echo (isset($employe['categorie']) && $employe['categorie'] == 'Conseiller') ? 'selected' : ''; ?>>Conseiller</option>
                    <option value="Agent" <?php echo (isset($employe['categorie']) && $employe['categorie'] == 'Agent') ? 'selected' : ''; ?>>Agent</option>
                    <option value="Directeur" <?php echo (isset($employe['categorie']) && $employe['categorie'] == 'Directeur') ? 'selected' : ''; ?>>Directeur</option>
                </select>
            </div>
            <div class="form-group">
                <label for="actif" class="form-label">L'employé est-il en poste :</label>
                <select class="form-control" id="actif" name="actif">
                    <option value="1" <?php echo (isset($employe['actif']) && $employe['actif'] == 1) ? 'selected' : ''; ?>>Oui</option>
                    <option value="0" <?php echo (isset($employe['actif']) && $employe['actif'] == 0) ? 'selected' : ''; ?>>Non</option>
                </select>
            </div>
            <div class="d-grid gap-2 col-6 mx-auto">
                <button type="submit" name="action" value="modifier" class="btn btn-outline-warning">Mettre à jour</button>
            </div>
    </div>
    </form>
    </div>
</body>

</html>