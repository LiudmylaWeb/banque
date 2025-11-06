<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Supprimer un compte client</title>
    <link rel="stylesheet" href="/static/css/formstyle.css">
</head>

<body>
    <?php
    require('../../init.php');
    checkAcl('auth');
    include VIEWS_DIR . '/menu.php';

    // Récupération de la liste des compte client avec les informations sur le client
    $sql = "SELECT CompteClient.idCompteClient, CompteClient.numClient, Client.nom, Client.prenom, CompteClient.solde 
        FROM CompteClient 
        INNER JOIN Client ON CompteClient.numClient = Client.numClient";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $compteClients = $stmt->fetchAll();

    // Traitement de la suppression du compte client
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['idCompteClient'])) {
        $idCompteClient = $_POST['idCompteClient'];

        // Récupération du solde du compte client
        $sqlSolde = "SELECT solde FROM CompteClient WHERE idCompteClient = ?";
        $stmtSolde = $conn->prepare($sqlSolde);
        $stmtSolde->execute([$idCompteClient]);
        $solde = $stmtSolde->fetchColumn();

        // Vérification du solde du compte avant suppression
        if ($solde == 0) {
            try {
                // Suppression des opérations associées à ce compte client
                $sqlDeleteOperations = "DELETE FROM Operation WHERE idCompteClient = ?";
                $stmtDeleteOperations = $conn->prepare($sqlDeleteOperations);
                $stmtDeleteOperations->execute([$idCompteClient]);

                // Requête pour supprimer le compte client spécifié
                $sqlDelete = "DELETE FROM CompteClient WHERE idCompteClient = ?";
                $stmtDelete = $conn->prepare($sqlDelete);
                $stmtDelete->execute([$idCompteClient]);

                // Message de succès de la suppression
                echo '<script>alert("Le compte client a été supprimé avec succès.");</script>';
                exit;
            } catch (PDOException $e) {
                // En cas d'erreur SQL, afficher le message d'erreur
                echo "Erreur SQL lors de la suppression du compte client : " . $e->getMessage();
            }
        } else {
            echo '<script>alert("Impossible de supprimer le compte car le solde n\'est pas nul.");</script>';
        }
    }
    ?>
    <div class="container mt-5" style="max-width: 700px;">
        <form action="supprimerCompteClient.php" method="post" class="row g-3 rounded shadow">
            <legend class="text-warning">Les comptes existants</legend>
            <div class="form-group">
                <label for="compteClient" class="form-label">Sélectionnez le compte client à supprimer :</label>
                <select name="idCompteClient" class="form-control" id="compteClient">
                    <?php foreach ($compteClients as $compteClient) : ?>
                        <option value="<?php echo $compteClient['idCompteClient']; ?>">
                            <?php echo "Client: " . htmlspecialchars($compteClient['nom']) . ' ' . htmlspecialchars($compteClient['prenom']) . ", Solde: " . htmlspecialchars($compteClient['solde']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="d-grid gap-2 col-6 mx-auto">
                <button type="submit" class="btn btn-outline-warning">Supprimer le compte</button>
            </div>
        </form>
        </div>
</body>

</html>