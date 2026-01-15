<?php

require_once ROOT_DIR . 'src/Database/DBConnection.php';

class AdminModel
{
    private PDO $db;
 
    public function __construct()
    {
        $this->db = DBConnection::getInstance()->getPDO();
    }

    /* ===== STATS ===== */

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

    /* ===== LISTES ===== */

    public function getFilms(): array
    {
        return $this->db
            ->query("SELECT * FROM film")
            ->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getUsers(): array
    {
        return $this->db
            ->query("SELECT id_utilisateur, email, role FROM utilisateur")
            ->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPropositions(): array
    {
        return $this->db
            ->query("SELECT * FROM proposition_film WHERE statut='en_attente'")
            ->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getComments(): array
    {
        return $this->db
            ->query("SELECT * FROM message")
            ->fetchAll(PDO::FETCH_ASSOC);
    }

    /* ===== ACTIONS ===== */

    public function deleteFilm(int $id): void
    {
        $stmt = $this->db->prepare("DELETE FROM film WHERE id_film = ?");
        $stmt->execute([$id]);
    }

    public function promoteUser(int $id): void
    {
        $stmt = $this->db->prepare("UPDATE utilisateur SET role='admin' WHERE id_utilisateur=?");
        $stmt->execute([$id]);
    }

    public function updateProposition(int $id, string $statut): void
    {
        $stmt = $this->db->prepare("UPDATE proposition_film SET statut=? WHERE id_propositionFilm=?");
        $stmt->execute([$statut, $id]);
    }

    public function deleteComment(int $id): void
    {
        $stmt = $this->db->prepare("DELETE FROM message WHERE id_message=?");
        $stmt->execute([$id]);
    }
}
