<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Formulaire d'ouverture de compte</title>
    <link rel="stylesheet" href="/static/css/formstyle.css">
</head>

<body>

    <?php
    require('../../init.php');
    checkAcl('auth');
    include VIEWS_DIR . '/menu.php';

    try {
        if (isset($_POST['ventecom'], $_POST['date'], $_POST['solde'], $_POST['decouvert'], $_POST['nomclient'], $_POST['nomcompte'])) {
            // Récupération des données du formulaire
            $datcompte = $_POST['date'];
            $soldecompte = $_POST['solde'];
            $decouvertcompte = $_POST['decouvert'];
            $nomClientValue = $_POST['nomclient'];
            $nomClientParts = explode(';', $nomClientValue);
            $numClient = $nomClientParts[0];
            $nomcompte = $_POST['nomcompte'];
    
            // Recherche du numéro de compte à partir du nom du compte
            $sql = "SELECT idCompte FROM compte WHERE nomTypeCompte = ?";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$nomcompte]);
            $rowcompte = $stmt->fetch(PDO::FETCH_ASSOC);
    
            if ($rowcompte && $numClient) {
                // Insertion des données dans la base de données
                $sql = "INSERT INTO Compteclient (dateouverture, solde, montantDecouvert, numClient, idCompte) VALUES (?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                if ($stmt->execute([$datcompte, $soldecompte, $decouvertcompte, $numClient, $rowcompte['idCompte']])) {
                    echo '<script>alert("Le compte client a été ajouté à la base de données.");</script>';
                } else {
                    echo '<script>alert("Une erreur est survenue lors de l\'ajout du compte client.");</script>';
                }
            } else {
                echo '<script>alert("Le client ou le compte spécifié n\'existe pas.");</script>';
            }
        }
    } catch (PDOException $e) {
        $msg = 'ERREUR dans ' . $e->getFile() . ' Ligne ' . $e->getLine() . ' : ' . $e->getMessage();
    }
    ?>
    <div class="container mt-5" style="max-width: 700px;">
        <form action="ouvrirCompte.php" method="post" class="row g-3 rounded shadow">
            <legend class="text-warning">Formulaire d'ouverture de compte</legend>
            <div class="form-group">
                <label for="date" class="form-label">Date d'ouverture :</label>
                <input type="date" class="form-control" id="date" name="date" required>
            </div>
            <div class="form-group">
                <label for="solde" class="form-label">Solde :</label>
                <input type="text" class="form-control" id="solde" name="solde" value="0" readonly>
            </div>
            <div class="form-group">
                <label for="decouvert" class="form-label">Montant du découvert :</label>
                <input type="text" class="form-control" id="decouvert" name="decouvert" required>
            </div>
            <div class="form-group">
                <label for="nomclient" class="form-label">Information du client :</label>
                <select id="nomclient" class="form-control" name="nomclient">
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
                <label for="nomcompte">Nom du compte :</label>
                <select id="nomcompte" class="form-control" name="nomcompte">
                    <?php
                    $sql = "SELECT nomTypeCompte FROM compte";
                    $stmt = $conn->prepare($sql);
                    $stmt->execute();
                    $nomtypecomptes = $stmt->fetchAll();
    
                    foreach ($nomtypecomptes as $nomtypecompte) {
                        echo "<option value=\"{$nomtypecompte['nomTypeCompte']}\">{$nomtypecompte['nomTypeCompte']}</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="d-grid gap-2 col-6 mx-auto">
                <button type="submit" name="ventecom" value="Vendre un compte" class="btn btn-outline-warning">Vendre un compte</button>
            </div>
        </form>
    </div>
    </body>
    

</html>