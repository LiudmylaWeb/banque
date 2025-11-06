<nav class="navbar navbar-expand-lg bg-black">
    <div class="container-fluid">
        <a class="navbar-brand" href="/"><img src="/static/images/cochon.png" width="80" alt="icon" style="color: black;"></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <!-- Liste des éléments de navigation -->
                <!-- Gestion des clients -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-white" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Gestion des clients
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="/clients/rechercherClient.php">Modifier les informations du client</a></li>
                        <li><a class="dropdown-item" href="/clients/ajouterClient.php">Inscrire un nouveau client</a></li>
                        <li><a class="dropdown-item" href="/comptes/gestionDecouvert.php">Modifier la valeur d'un découvert</a></li>
                    </ul>
                </li>
                <!-- Gestion des syntheses -->
                <li class="nav-item">
                    <a class="nav-link text-white" href="/clients/rechercheSyntheseClient.php">Consulter la synthèse d'un client</a>
                </li>
                <!-- Gestion des dépôts et des retraits -->
                <li class="nav-item">
                    <a class="nav-link text-white" href="/comptes/rechercherCompte.php">Réaliser un dépôt ou un retrait</a>
                </li>
                <!-- Gestion des rdv -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-white" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Rendez-vous
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="/rendez-vous/creerRDV.php">Prendre un rendez-vous pour un client</a></li>
                        <li><a class="dropdown-item" href="/rendez-vous/rechercherRDV.php">Gérer un rendez-vous pour un client</a></li>
                        <li><a class="dropdown-item" href="/rendez-vous/calendrier.php">Visualiser le calendrier</a></li>
                    </ul>
                </li>
            </ul>
            <?php
            include VIEWS_DIR . '/themeswitcher.php';
            ?>
            <a type="button" class="btn btn-outline-warning" href="/utilisateurs/deconnexion.php">Deconnexion</a>
        </div>
    </div>
</nav>