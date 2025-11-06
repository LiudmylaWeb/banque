<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier Rendez-vous</title>
    <link rel="stylesheet" href="/static/css/formstyle.css">
</head>

<body>
    <?php
    require('../../init.php');
    checkAcl('auth');
    include VIEWS_DIR . '/menu.php';

    $updateSuccessful = false;
    $deleteSuccessful = false;

    // Récupération de la liste des motifs
    try {
        $sql = "SELECT idMotif, libelleMotif FROM motif";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $motifs = $stmt->fetchAll();

        // Récupération de la liste des employés
        $sql = "SELECT numEmploye, nom FROM employe WHERE categorie = 'Conseiller' AND actif=1";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $employes = $stmt->fetchAll();

        // Récupération de la liste des clients
        $sql = "SELECT numClient, nom, prenom, dateNaissance FROM client";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $clients = $stmt->fetchAll();
    } catch (PDOException $e) {
        echo "Erreur lors de la récupération des données : " . $e->getMessage();
    }

    if (isset($_POST['numRdv'])) {
        $numRdv = $_POST['numRdv'];

        if (isset($_POST['action']) && $_POST['action'] === 'modifier') {
            $dateRdv = $_POST['dateRdv'];
            $heureRdv = $_POST['heureRdv'];
            $numEmploye = $_POST['numEmploye'];
            $idMotif = $_POST['idMotif'];
            $numClient = $_POST['numClient'];

            try {
                // Vérifier si le rendez-vous existe déjà pour le même conseiller, date et heure.
                $stmt = $conn->prepare("SELECT COUNT(*) FROM rdv WHERE dateRdv = ? AND heureRdv = ? AND numEmploye = ?");
                $stmt->execute([$dateRdv, $heureRdv, $numEmploye]);
                $count = $stmt->fetchColumn();

                if (strtotime($dateRdv) < strtotime(date('Y-m-d'))) {
                    echo '<script>alert("La date du rendez-vous ne peut pas être dans le passé.");</script>';
                } else if ($count == 0) {
                    // Mise à jour du rendez-vous
                    $sql = "UPDATE rdv SET dateRdv = ?, heureRdv = ?, numEmploye = ?, idMotif = ?, numClient = ? WHERE numRdv = ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->execute([$dateRdv, $heureRdv, $numEmploye, $idMotif, $numClient, $numRdv]); // Ajoutez $numRdv à la fin

                    $_SESSION['updateSuccess'] = true;
                    $updateSuccessful = true;
                } else {
                    echo '<script>alert("Un rendez-vous existe déjà pour cette date, cette heure et ce conseiller.");</script>';
                }
            } catch (PDOException $e) {
                echo "Erreur lors de la mise à jour du rendez-vous : " . $e->getMessage();
            }
        } elseif (isset($_POST['action']) && $_POST['action'] === 'supprimer') {
            try {
                // Suppression du rendez-vous
                $sql = "DELETE FROM rdv WHERE numRdv = ?";
                $stmt = $conn->prepare($sql);
                $stmt->execute([$numRdv]); // Assurez-vous que $numRdv est correctement défini

                $_SESSION['deleteSuccess'] = true;
                $deleteSuccessful = true;
            } catch (PDOException $e) {
                echo "Erreur lors de la suppression du rendez-vous : " . $e->getMessage();
            }
        }

        // Sélectionner à nouveau le rendez-vous après la mise à jour ou la suppression
        try {
            $sql = "SELECT * FROM rdv WHERE numRdv = ?";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$numRdv]);
            $Rdv = $stmt->fetch();
        } catch (PDOException $e) {
            echo "Erreur lors de la sélection du rendez-vous : " . $e->getMessage();
        }
    }

    // Affiche une alerte si la mise à jour a été réussie
    if ($updateSuccessful) {
        echo '<script>alert("Le rendez-vous a été mis à jour avec succès.");</script>';
    }

    // Affiche une alerte si la suppression a été réussie
    if ($deleteSuccessful) {
        echo '<script>alert("Le rendez-vous a été supprimé avec succès.");</script>';
    }
    ?>
    <!-- Formulaire pour la mise à jour et la suppression des informations du rendez-vous -->
    <div class="container mt-5" style="max-width: 700px;">
        <form action="modifierRDV.php" method="post" name="monForm" class="row g-3 rounded shadow">
            <legend class="text-warning">MODIFICATION DU RDV</legend>

            <!-- Champs du formulaire avec les informations à jour du rendez-vous -->
            <div class="form-group">
                <input type="hidden" class="form-control" name="numRdv" id="numRdv" value="<?php echo isset($Rdv['numRdv']) ? htmlspecialchars($Rdv['numRdv']) : ''; ?>">
            </div>

            <div class="form-group">
                <label for="dateRdv" class="form-label">Date du rendez-vous :</label>
                <input type="date" class="form-control" id="dateRdv" name="dateRdv" value="<?php echo isset($Rdv['dateRdv']) ? htmlspecialchars($Rdv['dateRdv']) : ''; ?>">
            </div>
            <div class="form-group">
                <label for="heureRdv" class="form-label">Heure du rendez-vous :</label>
                <input type="number" class="form-control" id="heureRdv" name="heureRdv" value="<?php echo isset($Rdv['heureRdv']) ? htmlspecialchars($Rdv['heureRdv']) : ''; ?>">
            </div>
            <div class="form-group">
                <label for="employe" class="form-label">Choisir un employé pour le rendez-vous :</label>
                <select name="numEmploye" class="form-control" id="numEmploye">
                    <?php foreach ($employes as $employe) : ?>
                        <option value="<?php echo $employe['numEmploye']; ?>">
                            <?php echo $employe['nom']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="idMotif" class="form-label">Sélectionnez le motif :</label>
                <select name="idMotif" id="idMotif" class="form-control">
                    <?php foreach ($motifs as $motif) : ?>
                        <option value="<?php echo $motif['idMotif']; ?>" <?php if (isset($Rdv['idMotif']) && $Rdv['idMotif'] == $motif['idMotif']) echo 'selected'; ?>>
                            <?php echo $motif['libelleMotif'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="client" class="form-label">Sélectionnez le client pour le rendez-vous :</label>
                <select name="numClient" id="numClient" class="form-control">
                    <?php foreach ($clients as $client) : ?>
                        <option value="<?php echo $client['numClient']; ?>">
                            <?php echo htmlspecialchars($client['nom']) . ' ' . htmlspecialchars($client['prenom']) . ' ' . htmlspecialchars($client['dateNaissance']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="d-grid gap-2 col-6 mx-auto">
                <button type="submit" name="action" value="modifier" class="btn btn-outline-warning">Mettre à jour</button>
            </div>
            <div class="d-grid gap-2 col-6 mx-auto">
                <button type="submit" name="action" value="supprimer" class="btn btn-outline-warning" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce rendez-vous ?')">Supprimer</button>
            </div>
        </form>
</body>
</div>

</html>