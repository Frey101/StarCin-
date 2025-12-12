<?php

require_once ROOT_DIR . 'src/Database/DBConnection.php';
require_once ROOT_DIR . 'src/Controller/SecurityController.php';

class AdminDashboardController
{
    private PDO $db;

    public function __construct()
    {
        SecurityController::restrictAccess(); // admin only
        $this->db = DBConnection::getInstance()->getPDO();
    }

    public function dashboard(): void
    {
        // ===== STATS =====
        $stats = [
            'films' => $this->count('film'),
            'users' => $this->count('utilisateur'),
            'propositions' => $this->countPropositions(),
            'votes' => $this->count('note')
        ];

        // ===== LISTES =====
        $films = $this->getFilms();
        $users = $this->getUsers();
        $propositions = $this->getPropositions();
        $comments = $this->getComments();

        require ROOT_DIR . 'views/layout/header.php';
        require ROOT_DIR . 'views/admin/dashboard.php';
        require ROOT_DIR . 'views/layout/footer.php';
    }

    /* ================= STATS ================= */

    private function count(string $table): int
    {
        return (int) $this->db
            ->query("SELECT COUNT(*) FROM $table")
            ->fetchColumn();
    }

    private function countPropositions(): int
    {
        return (int) $this->db
            ->query("SELECT COUNT(*) FROM proposition_film WHERE statut='en_attente'")
            ->fetchColumn();
    }

    /* ================= FILMS ================= */

    private function getFilms(): array
    {
        $sql = "
            SELECT f.id_film, f.titre, f.categorie,
                   ROUND(AVG(n.valeur),1) AS note
            FROM film f
            LEFT JOIN note n ON n.id_film = f.id_film
            GROUP BY f.id_film
        ";
        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    /* ================= USERS ================= */

    private function getUsers(): array
    {
        return $this->db
            ->query("SELECT id_utilisateur, email, role FROM utilisateur")
            ->fetchAll(PDO::FETCH_ASSOC);
    }

    /* ================= PROPOSITIONS ================= */

    private function getPropositions(): array
    {
        $sql = "
            SELECT p.id_propositionFilm, p.statut, u.email
            FROM proposition_film p
            JOIN administrateur a ON p.id_admin = a.id_admin
            JOIN utilisateur u ON a.id_utilisateur = u.id_utilisateur
            WHERE p.statut='en_attente'
        ";
        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    /* ================= COMMENTS ================= */

    private function getComments(): array
    {
        $sql = "
            SELECT m.id_message, u.email, m.contenu, fo.titre
            FROM message m
            JOIN utilisateur u ON u.id_utilisateur = m.id_utilisateur
            JOIN forum fo ON fo.id_forum = m.id_forum
        ";
        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }
}
