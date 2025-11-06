<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="/static/css/formstyle.css">
</head>

<body>
    <?php
    require('../../init.php');
    checkAcl('auth');
    include VIEWS_DIR . '/menu.php';
    ?>

    <div class="container mt-5" style="max-width: 700px;">
        <form action="syntheseClient.php" method="post" class="row g-3 rounded shadow">
            <legend class="text-warning">Les clients de la banque</legend>
            <div class="form-group">
                <label for="numClient" class="form-label">Sélectionnez le client :</label>
                <select name="numClient" id="numClient" class="form-control">
                    <?php
                    try {
                        $sql = "SELECT numClient, nom, prenom, dateNaissance FROM client";
                        $stmt = $conn->prepare($sql);
                        $stmt->execute();
                        $clients = $stmt->fetchAll(PDO::FETCH_ASSOC);

                        foreach ($clients as $client) {
                            echo "<option value=\"" . htmlspecialchars($client['numClient']) . "\">" . htmlspecialchars($client['nom']) . " " . htmlspecialchars($client['prenom']) . " (né(e) le " . htmlspecialchars($client['dateNaissance']) . ")</option>";
                        }
                    } catch (PDOException $e) {
                        echo "Erreur de base de données : " . $e->getMessage();
                    }
                    ?>
                </select>
            </div>
            <div class="d-grid gap-2 col-6 mx-auto">
                <button type="submit" class="btn btn-outline-warning">Afficher la synthèse du client</button>
            </div>
        </form>
    </div>
</body>
</html>