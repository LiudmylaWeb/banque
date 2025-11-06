<?php
require('../../init.php');  // Inclure le fichier init.php qui configure la connexion à la base de données

$login = $_POST['login'];  // Récupérer le login depuis le formulaire
$password = $_POST['motDePasse'];  // Récupérer le mot de passe depuis le formulaire

// Création d'une instance de la classe Auth
$auth = new Auth($conn);

// Utiliser la méthode login de la classe Auth
$auth->login($login, $password);

// Rediriger vers la page d'accueil
header("Location: /");

