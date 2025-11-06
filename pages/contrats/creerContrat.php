<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Créer un type de Contrat</title>
    <link rel="stylesheet" href="/static/css/formstyle.css">
</head>

<body>
    <?php
    require('../../init.php');
    checkAcl('auth');
    include VIEWS_DIR . '/menu.php';

    $createSuccessful = false;

    if (isset($_POST['action']) && !empty($_POST['nomContrat'])) {
        $nomContrat = $_POST['nomContrat'];
        $description = $_POST['description'];

        // Vérifier si le nom du contrat n'existe pas déjà
    $sql_check = "SELECT COUNT(*) AS count FROM contrat WHERE nomTypeContrat = ?";
    $res_check = $conn->prepare($sql_check);
    $res_check->execute([$nomContrat]);
    $row = $res_check->fetch(PDO::FETCH_ASSOC);
    
    if ($row['count'] == 0) {
        $sql = "INSERT INTO contrat (nomTypeContrat, description) VALUES (?, ?)";
        $res = $conn->prepare($sql);
        if ($res->execute([$nomContrat, $description])) {
            $createSuccessful = true;
        }
    }else {
        // Le nom du contrat existe déjà
        echo "<script>alert ('Le nom du contrat existe déjà dans la base de données.')</script>";}
    // Affiche une alerte si la création a été réussie
    if ($createSuccessful) {
        echo '<script>alert("Le type de contrat a été créé avec succès.");</script>';
    }}
    ?>
    <!-- Formulaire pour la création du contrat -->
    <div class="container mt-5" style="max-width: 700px;">
        <form action="creerContrat.php" method="post" name="monForm" class="row g-3 rounded shadow">
            <legend class="text-warning">Création du contrat</legend>
            <div class="form-group">
                <label for="nomContrat" class="form-label">Nom du contrat :</label>
                <input type="text" name="nomContrat" id="nomContrat" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="description" class="form-label">Description :</label>
                <textarea name="description" id="description" class="form-control" required></textarea>
            </div>
            <div class="d-grid gap-2 col-6 mx-auto">
                <button type="submit" name="action" value="Créer" class="btn btn-outline-warning">Créer</button>
            </div>
        </form>
    </div>
</body>

</html>