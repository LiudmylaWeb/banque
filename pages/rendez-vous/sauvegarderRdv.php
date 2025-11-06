<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Ajouter un rendez-vous</title>
</head>

<body>
    <?php
    require('../../init.php');
    checkAcl('auth');
    include VIEWS_DIR . '/menu.php';
    $createSuccessful = false;
    // Vérifier que le formulaire a été soumis
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Collecter les données du formulaire
        $dateRdv = $_POST['dateRdv'];
        $heureRdv = $_POST['heureRdv'];
        // $numEmploye = $auth->id();
        $numEmploye = $_POST['numEmploye'];
        $idMotif = $_POST['idMotif'];
        $numClient = $_POST['numClient'];


        // Validation de la date pour s'assurer qu'elle n'est pas passée
        $currentDate = date('Y-m-d'); // Récupère la date actuelle
        if (strtotime($dateRdv) < strtotime($currentDate)) {
            echo '<script>alert("Impossible de créer un rendez-vous à une date passée.");</script>';
        } else {
            // Vérifier si le rendez-vous existe déjà pour le même conseiller, date et heure.
            $stmt = $conn->prepare("SELECT COUNT(*) FROM rdv WHERE dateRdv = ? AND heureRdv = ? AND numEmploye = ?");
            $stmt->execute([$dateRdv, $heureRdv, $numEmploye]);
            $count = $stmt->fetchColumn();
            // Marque que la mise à jour a été réussie

            if ($count == 0) {
                try {
                    // Le rendez-vous n'existe pas encore, donc on peut l'insérer
                    // Préparer une requête SQL pour insérer les données
                    $stmt = $conn->prepare("INSERT INTO rdv (dateRdv, heureRdv, numEmploye, idMotif, numClient) VALUES (?, ?, ?, ?, ?)");
                    $stmt->execute([$dateRdv, $heureRdv, $numEmploye, $idMotif, $numClient]);
                    $createSuccessful = true;
                    $stmt = $conn->prepare("SELECT listePieces FROM Motif WHERE idMotif = ?");
                    $stmt->execute([$idMotif]);
                    $listePieces = $stmt->fetch(PDO::FETCH_ASSOC);
                    $piecesFournir = $listePieces['listePieces']; // Correction ici pour récupérer correctement les données
                    echo "<script>alert('RDV ajouté avec succès!\\nVoici la liste de pièces à fournir : \\n" . addslashes($piecesFournir) . "');</script>"; // Utilisation de addslashes pour gérer les apostrophes
                    exit;
                } catch (PDOException $e) {
                    echo "Erreur : " . $e->getMessage();
                }
            } else {
                echo '<script>alert("Un rendez-vous existe déjà pour cette date, cette heure et ce conseiller.");</script>';
            }
        }
    }
    ?>
</body>

</html>