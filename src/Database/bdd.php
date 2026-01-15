<?php

// Fichier : src/Database/DBConnection.php

class DBConnection {
    // Variable statique pour stocker l'unique instance de la classe
    private static $instance = null;

    // L'objet PDO qui contiendra la connexion active
    private $pdo;

    // Paramètres de Connexion (À MODIFIER !) 
    private $host = 'localhost';
    private $db   = 'cinema'; 
    private $user = 'root';        
    private $pass = '';         

    private $charset = 'utf8mb4';

    /**
     * Constructeur privé pour empêcher l'instanciation directe (Singleton).
     */
    private function __construct() {
        $dsn = "mysql:host={$this->host};dbname={$this->db};charset={$this->charset}";

        // Options de configuration de PDO
        $options = [
            // Afficher les erreurs PDO sous forme d'exceptions PHP
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            // Récupérer les résultats sous forme de tableaux associatifs par défaut
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            // Désactiver l'émulation des requêtes préparées pour la sécurité et la performance
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        try {
            // Création de l'objet PDO
            $this->pdo = new PDO($dsn, $this->user, $this->pass, $options);
        } catch (\PDOException $e) {
            // Arrêter l'application en cas d'échec de connexion
            // throw new \PDOException($e->getMessage(), (int)$e->getCode());
            die("Erreur de connexion à la base de données : " . $e->getMessage());
        }
    }

    /**
     * Méthode statique pour obtenir l'instance unique de la connexion.
     * Si l'instance n'existe pas, elle est créée.
     */
    public static function getInstance(): DBConnection {
        if (self::$instance === null) {
            self::$instance = new DBConnection();
        }
        return self::$instance;
    }

    /**
     * Méthode pour obtenir l'objet PDO. C'est elle que vous appellerez pour faire des requêtes.
     */
    public function getPDO(): PDO {
        return $this->pdo;
    }
}