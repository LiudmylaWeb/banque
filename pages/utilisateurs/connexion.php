<!DOCTYPE html>
<html>

<head>
    <title>Connexion</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="formstyle.css">
</head>

<body>

    <?php
    require('../../init.php');
    checkAcl('guest');
    //echo password_hash('password', PASSWORD_DEFAULT);
    ?>
    <div class="container mt-5" style="max-width: 500px;">
        <div style="border: 2px dashed black; padding: 20px; border-radius: 5px;">
            <form action="/utilisateurs/authentification.php" method="post">
                <h2>Connexion</h2>
                <div class="mb-3">
                    <label for="login" class="form-label">Identifiant :</label>
                    <input type="text" class="form-control" name="login" id="login" required>
                </div>
                <div class="mb-3">
                    <label for="motDePasse" class="form-label">Mot de passe</label>
                    <input type="password" class="form-control" name="motDePasse" id="motDePasse" required>
                </div>
                <button type="submit" class="btn btn-dark">Connexion</button>
            </form>
        </div>
    </div>
</body>

</html>