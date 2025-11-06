<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Synthèse client</title>
    <link rel="stylesheet" href="/static/css/formstyle.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>
    <?php
    require('../../init.php');
    checkAcl('auth');
    include VIEWS_DIR . '/menu.php';
    echo '<div class="p-3">';
    // Vérifie si le formulaire a été soumis et que numClient est défini
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['numClient'])) {
        // Récupération du numéro de client sélectionné
        $numClient = $_POST['numClient'];

        // Requête SQL pour récupérer les informations du client
        $sql = "SELECT c.numClient, c.nom, c.prenom, c.adresse, c.mail, c.numtel, s.description AS situation, c.dateNaissance, c.numEmploye, e.nom AS nomEmploye
                FROM client c
                LEFT JOIN situation s ON c.idSituation = s.idSituation
                LEFT JOIN employe e ON c.numEmploye = e.numEmploye
                WHERE c.numClient = :numClient";

        try {
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':numClient', $numClient, PDO::PARAM_INT);
            $stmt->execute();

            $clientInfo = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($clientInfo) {
                echo "<h2>Synthèse du client : {$clientInfo['nom']} {$clientInfo['prenom']}</h2>";
                echo "<p><strong>IdClient :</strong> {$clientInfo['numClient']}</p>";
                echo "<p><strong>Adresse :</strong> {$clientInfo['adresse']}</p>";
                echo "<p><strong>Email :</strong> {$clientInfo['mail']}</p>";
                echo "<p><strong>Téléphone :</strong> {$clientInfo['numtel']}</p>";
                echo "<p><strong>Situation :</strong> {$clientInfo['situation']}</p>";
                echo "<p><strong>Date de naissance :</strong> {$clientInfo['dateNaissance']}</p>";

                // Affichage de l'employé en charge du dossier client
                echo "<p><strong>Employé en charge du client :</strong> {$clientInfo['nomEmploye']}</p>";

                // Affichage des détails pour chaque compte du client
                $sql = "SELECT cc.idCompteClient, co.nomTypeCompte, cc.dateOuverture, cc.solde, cc.montantDecouvert
                        FROM compteclient cc
                        LEFT JOIN compte co ON cc.idCompte = co.idCompte
                        WHERE cc.numClient = :numClient";
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':numClient', $numClient, PDO::PARAM_INT);
                $stmt->execute();
                $comptes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    ?>
                <div class="accordion" id="accordionExample">
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingOne">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                Comptes
                            </button>
                        </h2>
                        <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                <?php
                                foreach ($comptes as $compte) {
                                    echo "<h4>{$compte['nomTypeCompte']}</h4>";
                                    echo "<ul>";
                                    echo "<li><strong>IdCompte :</strong> {$compte['idCompteClient']}</li>";
                                    echo "<li><strong>Date d'ouverture :</strong> {$compte['dateOuverture']}</li>";
                                    echo "<li><strong>Solde :</strong> {$compte['solde']} €</li>";
                                    echo "<li><strong>Montant autorisé de découvert :</strong> {$compte['montantDecouvert']} €</li>";

                                    // Affichage de l'historique des opérations pour ce compte
                                    $sql_operations = "SELECT numOp, montant, dateOperation, typeOp
                            FROM operation
                            WHERE idCompteClient = :idCompteClient";
                                    $stmt_operations = $conn->prepare($sql_operations);
                                    $stmt_operations->bindParam(':idCompteClient', $compte['idCompteClient'], PDO::PARAM_INT);
                                    $stmt_operations->execute();
                                    $operations = $stmt_operations->fetchAll(PDO::FETCH_ASSOC);

                                    echo "<li><strong>Historique des opérations :</strong>";
                                    echo "<ul>";
                                    foreach ($operations as $operation) {
                                        echo "<li>Opération {$operation['numOp']} - {$operation['typeOp']} de {$operation['montant']} € le {$operation['dateOperation']}</li>";
                                    }
                                    echo "</ul></li>";

                                    echo "</ul>";
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingTwo">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                Contrats
                            </button>
                        </h2>
                        <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                <?php
                                // Affichage des contrat du client
                                $sql = "SELECT ct.numContrat, ct.nomTypeContrat, cr.tarifMensuel, cr.dateOuvertureContrat
                                    FROM contratclient cr
                                    LEFT JOIN contrat ct ON cr.numContrat = ct.numContrat
                                    WHERE cr.numClient = :numClient";
                                $stmt = $conn->prepare($sql);
                                $stmt->bindParam(':numClient', $numClient, PDO::PARAM_INT);
                                $stmt->execute();
                                $contrats = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                foreach ($contrats as $contrat) {
                                    echo "<ul>";
                                    echo "<li><strong>IdContrat :</strong> {$contrat['numContrat']}</li>";
                                    echo "<li><strong>Type de contrat :</strong> {$contrat['nomTypeContrat']}</li>";
                                    echo "<li><strong>Tarif mensuel :</strong> {$contrat['tarifMensuel']} €</li>";
                                    echo "<li><strong>Date d'ouverture du contrat :</strong> {$contrat['dateOuvertureContrat']}</li>";
                                    echo "</ul>";
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingThree">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                Historique des Rendez-vous
                            </button>
                        </h2>
                        <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                    <?php
                    // Affichage de l'historique des rendez-vous du client avec le motif
                    $sql = "SELECT dateRdv, heureRdv, m.libelleMotif
                        FROM rdv r
                        LEFT JOIN motif m ON r.idMotif = m.idMotif
                        WHERE r.numClient = :numClient";
                    $stmt = $conn->prepare($sql);
                    $stmt->bindParam(':numClient', $numClient, PDO::PARAM_INT);
                    $stmt->execute();
                    $rdvs = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    if ($rdvs) {
                        echo "<ul>";
                        foreach ($rdvs as $rdv) {
                            echo "<li>Rendez-vous le {$rdv['dateRdv']} à {$rdv['heureRdv']}h, Motif : {$rdv['libelleMotif']}</li>";
                        }
                        echo "</ul>";
                    } else {
                        echo "<p>Aucun rendez-vous trouvé pour ce client.</p>";
                    }
                } else {
                    echo "<p>Client non trouvé.</p>";
                }
            } catch (PDOException $e) {
                // Gestion des erreurs PDO
                echo "Erreur de base de données : " . $e->getMessage();
            }
        } else {
            // Redirection ou message d'erreur si le formulaire n'est pas soumis correctement
            echo "<p>Une erreur s'est produite. Veuillez réessayer.</p>";
        }
                    ?>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
</body>

</html>