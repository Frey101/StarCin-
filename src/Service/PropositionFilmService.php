<?php
require_once ROOT_DIR . 'src/Database/DBConnection.php';

/**
 * Service pour la gestion des propositions de films par les réalisateurs
 * Respecte le principe SRP : gère uniquement la logique métier des propositions
 */
class PropositionFilmService
{
    private PDO $db;

    public function __construct()
    {
        $this->db = DBConnection::getInstance()->getPDO();
    }

    /**
     * Crée une proposition de film par un réalisateur
     */
    public function createProposition(int $realisateurId, string $commentaire): ?string
    {
        if (empty(trim($commentaire))) {
            return "Le commentaire est requis.";
        }

        // Vérifier si l'utilisateur est un réalisateur
        $stmt = $this->db->prepare("SELECT id_realisateur FROM realisateur WHERE id_utilisateur = ?");
        $stmt->execute([$realisateurId]);
        $realisateur = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$realisateur) {
            return "Vous devez être un réalisateur pour proposer un film.";
        }

        // Récupérer un admin pour la proposition (le premier admin disponible)
        $adminStmt = $this->db->query("SELECT id_admin FROM administrateur LIMIT 1");
        $admin = $adminStmt->fetch(PDO::FETCH_ASSOC);

        if (!$admin) {
            return "Aucun administrateur disponible pour traiter la proposition.";
        }

        try {
            $stmt = $this->db->prepare("
                INSERT INTO proposition_film (statut, dateProposition, commentaire, id_admin, id_realisateur)
                VALUES ('en_attente', CURDATE(), ?, ?, ?)
            ");
            $stmt->execute([
                $commentaire,
                $admin['id_admin'],
                $realisateur['id_realisateur']
            ]);
            return null;
        } catch (\PDOException $e) {
            return "Erreur lors de la création de la proposition.";
        }
    }

    /**
     * Récupère toutes les propositions avec les détails du réalisateur
     */
    public function getAllPropositions(): array
    {
        $sql = "
            SELECT p.id_propositionFilm, p.statut, p.commentaire, p.dateProposition,
                   DATE_FORMAT(p.dateProposition, '%d/%m/%Y') AS date_proposition,
                   u.email AS utilisateur_email, u.nom, u.prenom
            FROM proposition_film p
            LEFT JOIN realisateur r ON r.id_realisateur = p.id_realisateur
            LEFT JOIN utilisateur u ON u.id_utilisateur = r.id_utilisateur
            WHERE p.statut = 'en_attente'
            ORDER BY p.dateProposition DESC
        ";
        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Accepte une proposition
     */
    public function acceptProposition(int $id): bool
    {
        try {
            $stmt = $this->db->prepare("
                UPDATE proposition_film 
                SET statut = 'acceptee', dateReponse = CURDATE()
                WHERE id_propositionFilm = ?
            ");
            return $stmt->execute([$id]);
        } catch (\PDOException $e) {
            return false;
        }
    }

    /**
     * Refuse une proposition
     */
    public function refuseProposition(int $id): bool
    {
        try {
            $stmt = $this->db->prepare("
                UPDATE proposition_film 
                SET statut = 'refusee', dateReponse = CURDATE()
                WHERE id_propositionFilm = ?
            ");
            return $stmt->execute([$id]);
        } catch (\PDOException $e) {
            return false;
        }
    }
}

