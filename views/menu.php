   <!-- Liens vers Bootstrap et CSS -->
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
   <?php
    // get the variable from init.php
    global $auth;
    if ($auth->checkRole('Directeur')) {
        include VIEWS_DIR . '/roles/directeur.php';
    } elseif ($auth->checkRole('Agent')) {
        include VIEWS_DIR . '/roles/agent.php';
    } elseif ($auth->checkRole('Conseiller')) {
        include VIEWS_DIR . '/roles/conseiller.php';
    }
    ?>

   <!-- Liens vers les scripts Bootstrap -->
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>