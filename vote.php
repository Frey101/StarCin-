<?php
session_start();

// Si l'utilisateur n'est PAS connecté, il est redirigé vers la page de connexion
if (!isset($_SESSION['utilisateur_connecte']) || $_SESSION['utilisateur_connecte'] !== true) {
    header('Location: connexion.php');
    exit;
}

// Le reste du code de vote.php commence ici...
include 'config_bdd.php';
include 'header.php';
// ...
?><?php
