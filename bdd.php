<?php
// --- Paramètres de connexion à la Base de Données ---
// N'oubliez pas de remplacer ces valeurs par les vôtres !

const DB_HOST = 'localhost';   // L'hôte de la base de données (souvent 'localhost')
const DB_NAME = 'cinema'; // Le nom de votre base de données
const DB_USER = 'alys';        // Votre nom d'utilisateur de la base de données
const DB_PASS = '1234'; // Votre mot de passe de la base de données

// --- Démarrage de la connexion PDO ---
try {
    // Crée une nouvelle instance de PDO
    $bdd = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8", DB_USER, DB_PASS);

    // Configure PDO pour lancer des exceptions en cas d'erreur
    $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Configure PDO pour retourner les résultats sous forme de tableau associatif par défaut
    $bdd->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    // Si la connexion échoue, affiche une erreur et arrête le script
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}
?>