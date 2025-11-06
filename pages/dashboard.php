<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
</head>

<body>

    <?php
    require('../init.php');
    checkAcl('auth');
    include VIEWS_DIR . '/menu.php';
    $user = $auth->getUser();
    ?>
    <div class="text-center">
        </br>
        <h1>Bonjour <?php echo $user['nom']; ?>,</h1></br>
        <p>Bienvenue sur le tableau de bord de la banque.</p>
        <p>Vous pouvez naviguer dans le menu pour accéder aux différentes fonctionnalités.</p>
        <p>Si vous avez des questions, n'hésitez pas à contacter le support au 06 45 16 35 52</p>
    </div>
</body>

</html>