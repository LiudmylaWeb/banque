<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Création d'opération</title>
    <link rel="stylesheet" href="/static/css/formstyle.css">
</head>

<body>

<?php
require('../../init.php');
checkAcl('auth');
include VIEWS_DIR . '/menu.php';

$createSuccessful = false;

if (isset($_POST['action'])) {
    $montant = $_POST['montant'];
    $dateOperation = $_POST['dateOperation'];
    $typeOp = $_POST['typeOp'];
    $idCompteClient = $_POST['idCompteClient'];

    if ($typeOp == 'Retrait') { 
        // Récupérer le solde du compte et du découvert autorisé
        $sql = "SELECT solde, montantDecouvert FROM CompteClient WHERE idCompteClient = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$idCompteClient]);
        $resultat = $stmt->fetch(PDO::FETCH_ASSOC);
        $soldeDuCompte = $resultat['solde'];
        $montantDecouvert = $resultat['montantDecouvert'];

        // Vérification du respect du découvert
        if ($soldeDuCompte - $montant >= (-$montantDecouvert)) {
            // Opération de retrait autorisée
            $sql = "INSERT INTO operation (montant, dateOperation, typeOp, idCompteClient) VALUES (?, ?, ?, ?)";
            $res = $conn->prepare($sql);
            $res->execute([$montant, $dateOperation, $typeOp, $idCompteClient]);
            $createSuccessful = true;

            // Mise à jour du solde
            $sql = "UPDATE CompteClient SET solde = solde - ? WHERE idCompteClient = ?";
            $res = $conn->prepare($sql);
            $res->execute([$montant, $idCompteClient]);
        } else {
            // Opération non autorisée en raison d'un découvert dépassé
            echo '<script>alert("L\'opération n\'est pas autorisée car le découvert est dépassé !");</script>';
        }
    } else {
        // Réaliser l'opération de dépôt
        $sql = "INSERT INTO operation (montant, dateOperation, typeOp, idCompteClient) VALUES (?, ?, ?, ?)";
        $res = $conn->prepare($sql);
        $res->execute([$montant, $dateOperation, $typeOp, $idCompteClient]);
        $createSuccessful = true;

        // Mise à jour du solde
        $sql = "UPDATE CompteClient SET solde = solde + ? WHERE idCompteClient = ?";
        $res = $conn->prepare($sql);
        $res->execute([$montant, $idCompteClient]);
    }
}

// Affiche une alerte si la création a été réussie
if ($createSuccessful) {
    echo '<script>alert("L\'opération a été créée avec succès.");</script>';
}
?>

    <!-- Formulaire pour la création du contrat -->
    <div class="container mt-5" style="max-width: 700px;">
        <form action="rechercherCompte.php" method="post" name="monForm" class="row g-3 rounded shadow">
            <legend class="text-warning">Réalisation d'un dépot ou d'un retrait</legend>
            <div class="form-group">
                <label for="montant" class="form-label">Montant :</label>
                <input type="number" class="form-control" name="montant" id="montant" required min="0">
            </div>
            <div class="form-group">
                <label for="dateOperation" class="form-label">Date de l'opération :</label>
                <input type="date" class="form-control" name="dateOperation" id="dateOperation" required>
            </div>
            <div class="form-group">
                <label for="typeOp" class="form-label">Type de l'opération :</label>
                <select name="typeOp" id="typeOp" class="form-control" required>
                    <option value="Dépôt">Dépôt</option>
                    <option value="Retrait">Retrait</option>
                </select>
            </div>
            <div class="form-group">
                <?php
                $sql = "SELECT idCompteClient, prenom, nom, nomTypeCompte FROM client JOIN compteclient ON client.numClient = compteclient.numClient JOIN compte ON CompteClient.idCompte=compte.idCompte";
                $stmt = $conn->prepare($sql);
                $stmt->execute();
                $comptes = $stmt->fetchAll();
                ?>
                <div class="form-group">
                <label for="idCompteClient" class="form-label">Id du compte client :</label>
                <select name="idCompteClient" id="idCompteClient" class="form-control" required>
                    <?php foreach ($comptes as $compte) : ?>
                        <option value="<?php echo $compte['idCompteClient']; ?>">
                            <?php echo "Compte n°" . $compte['idCompteClient'] . " de type ". $compte['nomTypeCompte'] . " - " . " Client: " . $compte['nom'] . ' ' . $compte['prenom']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="d-grid gap-2 col-6 mx-auto">
                <button type="submit" name="action" value="Créer" class="btn btn-outline-warning">Créer</button>
            </div>
        </form>
</body>

</html>
