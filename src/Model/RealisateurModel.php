<?php
require_once __DIR__ . '/../Database/DBConnection.php';

/**
 * Modèle pour la gestion des réalisateurs
 * Respecte le principe SRP : gère uniquement l'accès aux données des réalisateurs
 */
class RealisateurModel
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = DBConnection::getInstance()->getPDO();
    }

    /**
     * Récupère tous les réalisateurs avec leurs informations utilisateur
     */
    public function findAll(): array
    {
        $stmt = $this->pdo->prepare("
            SELECT r.id_realisateur, r.id_utilisateur,
                   u.nom, u.prenom, u.email, u.ville
            FROM realisateur r
            JOIN utilisateur u ON u.id_utilisateur = r.id_utilisateur
            ORDER BY u.nom, u.prenom
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Récupère un réalisateur par son ID
     */
    public function find(int $id): ?array
    {
        $stmt = $this->pdo->prepare("
            SELECT r.id_realisateur, r.id_utilisateur,
                   u.nom, u.prenom, u.email, u.ville
            FROM realisateur r
            JOIN utilisateur u ON u.id_utilisateur = r.id_utilisateur
            WHERE r.id_realisateur = ?
        ");
        $stmt->execute([$id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    /**
     * Vérifie si un utilisateur est réalisateur
     */
    public function isRealisateur(int $userId): bool
    {
        $stmt = $this->pdo->prepare("SELECT id_realisateur FROM realisateur WHERE id_utilisateur = ?");
        $stmt->execute([$userId]);
        return $stmt->fetch(PDO::FETCH_ASSOC) !== false;
    }

    /**
     * Ajoute un utilisateur comme réalisateur
     */
    public function addRealisateur(int $userId): bool
    {
        try {
            // Vérifier que l'utilisateur n'est pas déjà réalisateur
            if ($this->isRealisateur($userId)) {
                return false;
            }

            $stmt = $this->pdo->prepare("INSERT INTO realisateur (id_utilisateur) VALUES (?)");
            return $stmt->execute([$userId]);
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * Supprime un réalisateur
     */
    public function removeRealisateur(int $realisateurId): bool
    {
        try {
            $stmt = $this->pdo->prepare("DELETE FROM realisateur WHERE id_realisateur = ?");
            return $stmt->execute([$realisateurId]);
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * Récupère les utilisateurs qui ne sont pas réalisateurs (pour les promouvoir)
     */
    public function getNonRealisateurs(): array
    {
        $stmt = $this->pdo->prepare("
            SELECT u.id_utilisateur, u.nom, u.prenom, u.email, u.ville
            FROM utilisateur u
            LEFT JOIN realisateur r ON r.id_utilisateur = u.id_utilisateur
            WHERE r.id_realisateur IS NULL AND u.role != 'admin'
            ORDER BY u.nom, u.prenom
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}