<?php
//Cette ligne définit une constante nommée SERVEUR avec la valeur 'localhost'. Cette constante est utilisée pour spécifier l'emplacement du serveur de base de données.
define("SERVEUR", "localhost");
define("USER", "root");
define("PASSWORD", "");
define("BDD", "banque");
// Créer la connexion
//Cette ligne crée une nouvelle instance de la classe PDO pour établir une connexion à la base de données MySQL.
$conn = new PDO('mysql:host=' . SERVEUR . ';dbname=' . BDD, USER, PASSWORD);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$conn->query('SET NAMES UTF8');
