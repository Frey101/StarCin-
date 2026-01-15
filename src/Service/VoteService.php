<?php
require_once ROOT_DIR . 'src/Database/DBConnection.php';
require_once ROOT_DIR . 'src/Model/SessionVoteModel.php';

/**
 * Service pour la gestion des votes
 * Respecte le principe SRP : gère uniquement la logique métier des votes
 */
class VoteService
{
    private PDO $db;
    private SessionVoteModel $sessionModel;

    public function __construct()
    {
        $this->db = DBConnection::getInstance()->getPDO();
        $this->sessionModel = new SessionVoteModel();
    }

    /**
     * Vérifie si l'utilisateur peut voter (pas admin)
     */
    public function canUserVote(int $userId): bool
    {
        $stmt = $this->db->prepare("SELECT role FROM utilisateur WHERE id_utilisateur = ?");
        $stmt->execute([$userId]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $user && $user['role'] !== 'admin';
    }

    /**
     * Vérifie si l'utilisateur a déjà voté dans une session (peu importe le film)
     */
    public function hasUserVotedInSession(int $userId, int $sessionId): bool
    {
        // Récupérer l'id_voteur depuis la table voteur
        $stmt = $this->db->prepare("SELECT id_voteur FROM voteur WHERE id_utilisateur = ?");
        $stmt->execute([$userId]);
        $voteur = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$voteur) {
            return false;
        }

        // Récupérer les films de la session
        $films = $this->sessionModel->getFilmsBySession($sessionId);
        if (empty($films)) {
            return false;
        }

        $filmIds = array_column($films, 'id_film');
        if (empty($filmIds)) {
            return false;
        }

        // Vérifier si l'utilisateur a déjà voté pour un film de cette session
        $placeholders = implode(',', array_fill(0, count($filmIds), '?'));
        $stmt = $this->db->prepare("
            SELECT COUNT(*) FROM note 
            WHERE id_voteur = ? AND id_film IN ($placeholders)
        ");
        $params = array_merge([$voteur['id_voteur']], $filmIds);
        $stmt->execute($params);
        
        return $stmt->fetchColumn() > 0;
    }

    /**
     * Vérifie si l'utilisateur a déjà voté pour un film spécifique
     */
    public function hasUserVotedForFilm(int $userId, int $filmId): bool
    {
        // Récupérer l'id_voteur depuis la table voteur
        $stmt = $this->db->prepare("SELECT id_voteur FROM voteur WHERE id_utilisateur = ?");
        $stmt->execute([$userId]);
        $voteur = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$voteur) {
            return false;
        }

        // Vérifier si une note existe pour ce film et ce voteur
        $stmt = $this->db->prepare("
            SELECT COUNT(*) FROM note 
            WHERE id_voteur = ? AND id_film = ?
        ");
        $stmt->execute([$voteur['id_voteur'], $filmId]);
        return $stmt->fetchColumn() > 0;
    }

    /**
     * Enregistre un vote
     */
    public function submitVote(int $userId, int $filmId, int $note, int $sessionId): ?string
    {
        if ($note < 1 || $note > 5) {
            return "La note doit être entre 1 et 5.";
        }

        if (!$this->canUserVote($userId)) {
            return "Les administrateurs ne peuvent pas voter.";
        }

        // Vérifier si l'utilisateur a déjà voté dans cette session
        if ($this->hasUserVotedInSession($userId, $sessionId)) {
            return "Vous avez déjà voté dans cette session de vote. Vous ne pouvez voter qu'une seule fois.";
        }

        // Récupérer ou créer le voteur
        $voteurId = $this->getOrCreateVoteur($userId);
        if (!$voteurId) {
            return "Erreur lors de la récupération du voteur.";
        }

        // Vérifier si l'utilisateur a déjà voté pour ce film spécifique
        if ($this->hasUserVotedForFilm($userId, $filmId)) {
            return "Vous avez déjà voté pour ce film.";
        }

        // Créer une nouvelle note
        try {
            $stmt = $this->db->prepare("
                INSERT INTO note (valeur, id_voteur, id_film)
                VALUES (?, ?, ?)
            ");
            $stmt->execute([$note, $voteurId, $filmId]);
            return null;
        } catch (\PDOException $e) {
            // Si erreur de contrainte unique, l'utilisateur a déjà voté
            if ($e->getCode() == 23000) {
                return "Vous avez déjà voté pour ce film.";
            }
            return "Erreur lors de l'enregistrement du vote.";
        }
    }

    /**
     * Récupère ou crée un voteur pour un utilisateur
     */
    private function getOrCreateVoteur(int $userId): ?int
    {
        // Vérifier si un voteur existe déjà
        $stmt = $this->db->prepare("SELECT id_voteur FROM voteur WHERE id_utilisateur = ?");
        $stmt->execute([$userId]);
        $voteur = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($voteur) {
            return (int)$voteur['id_voteur'];
        }

        // Créer un nouveau voteur
        try {
            $stmt = $this->db->prepare("INSERT INTO voteur (id_utilisateur) VALUES (?)");
            $stmt->execute([$userId]);
            return (int)$this->db->lastInsertId();
        } catch (\PDOException $e) {
            return null;
        }
    }

    /**
     * Récupère les films d'une session active
     */
    public function getFilmsForActiveSession(int $sessionId): array
    {
        return $this->sessionModel->getFilmsBySession($sessionId);
    }
}

