<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulaire de Vente de Contrat</title>
    <link rel="stylesheet" href="/static/css/formstyle.css">
</head>

<body>
    <?php
    require('../../init.php');
    checkAcl('auth');
    include VIEWS_DIR . '/menu.php';

    try {
        if (isset($_POST['ventecon'], $_POST['date'], $_POST['tarif'], $_POST['nomcli'], $_POST['nomcon'])) {
            // Récupération des données du formulaire
            $datcon = $_POST['date'];
            $tarcon = $_POST['tarif'];
            $nomClientValue = $_POST['nomcli'];
            $nomClientParts = explode(';', $nomClientValue);
            $numClient = $nomClientParts[0];
            $nomcon = $_POST['nomcon'];

            // Recherche du numéro de contrat à partir du nom du contrat
            $sql = "SELECT numContrat FROM contrat WHERE nomTypeContrat = ?";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$nomcon]);
            $rowcon = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($numClient && $rowcon) {
                // Insertion des données dans la base de données
                $sql = "INSERT INTO ContratClient (dateOuvertureContrat, tarifMensuel, numClient, numContrat) VALUES (?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                if ($stmt->execute([$datcon, $tarcon, $numClient, $rowcon['numContrat']])) {
                    echo '<script>alert("Le contrat client a été ajouté à la base de données.");</script>';
                } else {
                    echo '<script>alert("Une erreur est survenue lors de l\'ajout du contrat client.");</script>';
                }
            } else {
                echo '<script>alert("Le client ou le contrat spécifié n\'existe pas.");</script>';
            }
        }
    } catch (PDOException $e) {
        $msg = 'ERREUR dans ' . $e->getFile() . ' Ligne ' . $e->getLine() . ' : ' . $e->getMessage();
    }
    ?>
    <div class="container mt-5" style="max-width: 700px;">
        <form action="vendreContrat.php" method="post" class="row g-3 rounded shadow">
            <legend class="text-warning">Formulaire de Vente de Contrat</legend>
            <div class="form-group">
                <label for="date" class="form-label">Date d'ouverture :</label>
                <input type="date" id="date" name="date" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="tarif" class="form-label">Tarif mensuel :</label>
                <input type="text" id="tarif" name="tarif" class="form-control" required min="0">
            </div>
            <div class="form-group">
                <label for="nomcli" class="form-label">Information du client :</label>
                <select id="nomcli" name="nomcli" class="form-control">
                <?php
                    $sql = "SELECT numClient, nom, prenom, dateNaissance FROM Client";
                    $stmt = $conn->prepare($sql);
                    $stmt->execute();
                    $clients = $stmt->fetchAll();
    
                    foreach ($clients as $client) {
                        // Formatage de la date de naissance pour l'affichage
                        $dateNaissance = new DateTime($client['dateNaissance']);
                        $dateFormatted = $dateNaissance->format('d/m/Y');
    
                        // Création de la valeur pour chaque option
                        $optionValue = htmlspecialchars($client['numClient']) . ';' . htmlspecialchars($client['nom']) . ';' . htmlspecialchars($client['prenom']) . ';' . $dateFormatted;
    
                        // Affichage de chaque option du menu déroulant
                        echo "<option value=\"{$optionValue}\">{$client['numClient']} {$client['nom']} {$client['prenom']} - né le {$dateFormatted}</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="nomcon" class="form-label">Nom du contrat :</label>
                <select id="nomcon" name="nomcon" class="form-control">
                    <?php
                    $sql = "SELECT nomTypeContrat FROM contrat";
                    $stmt = $conn->prepare($sql);
                    $stmt->execute();
                    $nomtypecontrats = $stmt->fetchAll();

                    foreach ($nomtypecontrats as $nomtypecontrat) {
                        echo "<option value=\"{$nomtypecontrat['nomTypeContrat']}\">{$nomtypecontrat['nomTypeContrat']}</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="d-grid gap-2 col-6 mx-auto">
                <button type="submit" name="ventecon" value="Vendre un contrat" class="btn btn-outline-warning">Vendre un contrat</button>
            </div>
        </form>
    </div>
</body>

</html>