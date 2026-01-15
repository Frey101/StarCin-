<?php
// src/Repository/AdminRepository.php

require_once ROOT_DIR . 'src/Database/DBConnection.php';
require_once ROOT_DIR . 'src/Repository/IAdminRepository.php';

/**
 * Repository pour les opérations admin
 * Respecte le principe SRP : gère uniquement l'accès aux données
 * Respecte le principe DIP : implémente IAdminRepository
 */
class AdminRepository implements IAdminRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = DBConnection::getInstance()->getPDO();
    }

    // ==================== STATISTIQUES ====================
    
    public function countFilms(): int
    {
        return (int)$this->db->query("SELECT COUNT(*) FROM film")->fetchColumn();
    }

    public function countUsers(): int
    {
        return (int)$this->db->query("SELECT COUNT(*) FROM utilisateur")->fetchColumn();
    }

    public function countPropositions(): int
    {
        return (int)$this->db
            ->query("SELECT COUNT(*) FROM proposition_film WHERE statut='en_attente'")
            ->fetchColumn();
    }

    public function countVotes(): int
    {
        return (int)$this->db->query("SELECT COUNT(*) FROM note")->fetchColumn();
    }

    // ==================== FILMS ====================
    
    public function getFilmsWithRatings(): array
    {
        $sql = "
            SELECT f.id_film, f.titre, f.categorie, f.annee, f.synopsis, f.bandeannonce,
                   ROUND(AVG(n.valeur), 1) AS note_moyenne,
                   COUNT(n.id_note) AS nb_votes
            FROM film f
            LEFT JOIN note n ON n.id_film = f.id_film
            GROUP BY f.id_film
            ORDER BY f.titre
        ";
        $films = $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
        
        // Ajouter le chemin de l'image basé sur l'ID (convention de nommage)
        foreach ($films as &$film) {
            $film['image'] = $this->getFilmImagePath($film['id_film']);
        }
        
        return $films;
    }

    /**
     * Retourne le chemin de l'image d'un film basé sur son ID
     * Convention : film_{id_film}.jpg (ou autres extensions)
     */
    public function getFilmImagePath(int $filmId): ?string
    {
        $imageDir = ROOT_DIR . 'public/image/';
        $extensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        
        foreach ($extensions as $ext) {
            $fileName = "film_{$filmId}.{$ext}";
            $filePath = $imageDir . $fileName;
            if (file_exists($filePath)) {
                return ROOT_PATH . 'public/image/' . $fileName;
            }
        }
        
        return null;
    }

    public function getFilmById(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM film WHERE id_film = ?");
        $stmt->execute([$id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($result) {
            // Ajouter le chemin de l'image basé sur l'ID
            $result['image'] = $this->getFilmImagePath($id);
        }
        return $result ?: null;
    }

    public function deleteFilm(int $id): bool
    {
        try {
            $stmt = $this->db->prepare("DELETE FROM film WHERE id_film = ?");
            return $stmt->execute([$id]);
        } catch (\PDOException $e) {
            return false;
        }
    }

    // ==================== UTILISATEURS ====================
    
    public function getUsers(): array
    {
        $sql = "
            SELECT id_utilisateur, email, role, nom, prenom, ville
            FROM utilisateur
            ORDER BY id_utilisateur DESC
        ";
        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getUserById(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM utilisateur WHERE id_utilisateur = ?");
        $stmt->execute([$id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    public function promoteUser(int $id): bool
    {
        try {
            $stmt = $this->db->prepare("UPDATE utilisateur SET role='admin' WHERE id_utilisateur=?");
            return $stmt->execute([$id]);
        } catch (\PDOException $e) {
            return false;
        }
    }

    public function deleteUser(int $id): bool
    {
        try {
            $stmt = $this->db->prepare("DELETE FROM utilisateur WHERE id_utilisateur=?");
            return $stmt->execute([$id]);
        } catch (\PDOException $e) {
            return false;
        }
    }

    // ==================== PROPOSITIONS ====================
    
    public function getPropositions(): array
    {
        $sql = "
            SELECT p.id_propositionFilm, p.statut, p.commentaire, p.dateProposition,
                   DATE_FORMAT(p.dateProposition, '%d/%m/%Y') AS date_proposition
            FROM proposition_film p
            WHERE p.statut='en_attente'
            ORDER BY p.dateProposition DESC
        ";
        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updatePropositionStatus(int $id, string $status): bool
    {
        try {
            $stmt = $this->db->prepare("UPDATE proposition_film SET statut=? WHERE id_propositionFilm=?");
            return $stmt->execute([$status, $id]);
        } catch (\PDOException $e) {
            return false;
        }
    }

    // ==================== COMMENTAIRES ====================
    
    public function getCommentsWithDetails(): array
    {
        $sql = "
            SELECT m.id_message, u.email, m.contenu, 
                   fo.titre AS forum_titre,
                   DATE_FORMAT(m.datePublication, '%d/%m/%Y %H:%i') AS date_message
            FROM message m
            JOIN utilisateur u ON u.id_utilisateur = m.id_utilisateur
            JOIN forum fo ON fo.id_forum = m.id_forum
            ORDER BY m.datePublication DESC
            LIMIT 50
        ";
        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function deleteComment(int $id): bool
    {
        try {
            $stmt = $this->db->prepare("DELETE FROM message WHERE id_message=?");
            return $stmt->execute([$id]);
        } catch (\PDOException $e) {
            return false;
        }
    }
}

