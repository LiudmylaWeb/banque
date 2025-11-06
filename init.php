<?php
// Définition de la variable $RUN_DIR pour notre répertoire racine, nous l'utiliserons comme un élément d'entrée pour les fichiers afin de ne pas avoir à l'écrire à chaque fois
// __DIR__ représente notre répertoire courant
// ROOT_DIR est une constante où nous stockons notre répertoire
GLOBAL $RUN_DIR;
$RUN_DIR = __DIR__ .'/';
define('ROOT_DIR', __DIR__ .'/pages/' );
define('VIEWS_DIR', __DIR__ .'/views/' );
require('connect.php');
require('auth.php');
session_start();

//l'instance pour gérer l'authentification 
$auth = new Auth($conn);

// liste de contrôle d'accès

function checkAcl($acl = null) {
    global $auth; 
    // Définition des chemins de redirection
    $loginPath = '/utilisateurs/connexion.php';
    $dashboardPath = '/pages/dashboard.php';

    // Définition d'une fonction pour gérer la redirection
    $redirect = function ($path) {
        header("Location: $path");
        exit;
    };

    // Gestion de l'authentification en fonction de la liste de contrôle d'accès
    if ($acl === 'guest' && $auth->isLoggedIn()) {
        $redirect($dashboardPath);
    } elseif ($acl === 'auth' && !$auth->isLoggedIn()) {
        $redirect($loginPath);
    } elseif ($acl !== 'guest' && $acl !== 'auth') {
        // Comportement par défaut pour les rôles non reconnus ou si aucun rôle n'est spécifié
        $auth->isLoggedIn() ? $redirect($dashboardPath) : $redirect($loginPath);
    }
}
