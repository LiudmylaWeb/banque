<!DOCTYPE html>
<html lang="fr">

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

    $updateSuccessful = false;

    // Vérifie si le formulaire a été soumis
    if (isset($_POST['action']) && $_POST['action'] === 'modifier' && isset($_POST['numClient'])) {
        $numClient = $_POST['numClient'];
        $nom = $_POST['nom'];
        $prenom = $_POST['prenom'];
        $adresse = $_POST['adresse'];
        $mail = $_POST['mail'];
        $numTel = $_POST['numTel'];
        $idSituation = $_POST['idSituation'];
        $dateNaissance = $_POST['dateNaissance'];
        $numEmploye = $_POST['numEmploye'];
        // Prépare et exécute la requête SQL pour mettre à jour les informations du client
        $sql = "UPDATE Client SET nom = ?, prenom = ?, adresse = ?, mail = ?, numTel = ?, idSituation = ?, dateNaissance = ?, numEmploye = ? WHERE numClient = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$nom, $prenom, $adresse, $mail, $numTel, $idSituation, $dateNaissance, $numEmploye, $numClient]);

        // Marque que la mise à jour a été réussie
        $updateSuccessful = true;
    }

    // Récupère les informations du client après la mise à jour ou si l'ID est spécifié
    if (isset($_POST['numClient'])) {
        $numClient = $_POST['numClient'];
        $sql = "SELECT * FROM Client WHERE numClient = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$numClient]);
        $client = $stmt->fetch();
    }

    // Affiche une alerte si la mise à jour a été réussie
    if ($updateSuccessful) {
        echo '<script>alert("Les informations du client ont été mises à jour avec succès.");</script>';
    }
    ?>

    <!-- Formulaire pour la mise à jour des informations du client -->
    <div class="container mt-5" style="max-width: 700px;">
        <form action="modifierClient.php" method="post" class="row g-3 rounded shadow">
            <legend class="text-warning">INFORMATION DU CLIENT</legend>
            <!-- Champs du formulaire avec les informations à jour du client -->
            <div class="form-group">
                <label for="numClient" class="form-label">ID Client : </label>
                <input type="text" name="numClient" id="numClient" class="form-control"
                    value="<?php echo htmlspecialchars($client['numClient'] ?? ''); ?>" readonly>
            </div>
            <div class="form-group col-md-6">
                <label for="nom" class="form-label">Nom : </label>
                <input type="text" id="nom" name="nom" class="form-control"
                    value="<?php echo htmlspecialchars($client['nom'] ?? ''); ?>">
            </div>
            <div class="form-group col-md-6">
                <label for="prenom" class="form-label">Prénom : </label>
                <input type="text" id="prenom" name="prenom" class="form-control"
                    value="<?php echo htmlspecialchars($client['prenom'] ?? ''); ?>">
            </div>
            <div class="form-group">
                <label for="adresse" class="form-label">Adresse : </label>
                <input type="text" id="adresse" name="adresse" class="form-control"
                    value="<?php echo htmlspecialchars($client['adresse'] ?? ''); ?>">
            </div>
            <div class="form-group">
                <label for="mail" class="form-label">Mail : </label>
                <input type="email" id="mail" name="mail" class="form-control"
                    value="<?php echo htmlspecialchars($client['mail'] ?? ''); ?>">
            </div>
            <div class="form-group">
                <label for="numTel" class="form-label">Numéro de Téléphone : </label>
                <input type="text" id="numTel" name="numTel" class="form-control"
                    value="<?php echo htmlspecialchars($client['numTel'] ?? ''); ?>">
            </div>
            <div class="form-group">
                <label for="dateNaissance" class="form-label">Date de naissance : </label>
                <input type="date" id="dateNaissance" name="dateNaissance" class="form-control"
                    value="<?php echo htmlspecialchars($client['dateNaissance'] ?? ''); ?>">
            </div>
            <div class="form-group">
                <label for="situation" class="form-label">Situation : </label>
                <select id="situation" name="idSituation" class="form-control">
                    <?php
                    $sql = "SELECT idSituation, description FROM Situation ORDER BY description";
                    $stmt = $conn->prepare($sql);
                    $stmt->execute();
                    $situations = $stmt->fetchAll();

                    foreach ($situations as $situation) {
                        $selected = ($client['idSituation'] == $situation['idSituation']) ? 'selected' : '';
                        echo "<option value=\"{$situation['idSituation']}\" {$selected}>{$situation['description']}</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="numEmploye" class="form-label">Employé en charge du dossier : </label>
                <select id="numEmploye" name="numEmploye" class="form-control">
                    <?php
                    $sql = "SELECT numEmploye, nom FROM employe WHERE categorie='Conseiller' AND actif = 1";
                    $stmt = $conn->prepare($sql);
                    $stmt->execute();
                    $employes = $stmt->fetchAll();
                    foreach ($employes as $employe) {
                        echo "<option value=\"{$employe['numEmploye']}\">{$employe['nom']}</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="d-grid gap-2 col-6 mx-auto">
                <button type="submit" name="action" value="modifier" class="btn btn-outline-warning">Mettre à jour</button>
            </div>
        </form>
    </div>
</body>

</html>
