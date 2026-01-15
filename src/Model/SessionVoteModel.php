<?php
require_once __DIR__ . '/../Database/DBConnection.php';
require_once __DIR__ . '/../Entity/SessionVote.php';

class SessionVoteModel
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = DBConnection::getInstance()->getPDO();
    }

    public function save(SessionVote $session): bool
    {
        if ($session->getIdSessionvote() === null) {
            $sql = "
                INSERT INTO session_vote (dateDebut, dateFin, duree, annee, id_admin)
                VALUES (:dateDebut, :dateFin, :duree, :annee, :id_admin)
            ";
        } else {
            $sql = "
                UPDATE session_vote SET
                    dateDebut = :dateDebut,
                    dateFin = :dateFin,
                    duree = :duree,
                    annee = :annee
                WHERE id_sessionvote = :id_sessionvote
            ";
        }

        $stmt = $this->pdo->prepare($sql);
        $params = [
            ':dateDebut' => $session->getDateDebut(),
            ':dateFin' => $session->getDateFin(),
            ':duree' => $session->getDuree(),
            ':annee' => $session->getAnnee(),
        ];

        if ($session->getIdSessionvote() !== null) {
            $params[':id_sessionvote'] = $session->getIdSessionvote();
        } else {
            $params[':id_admin'] = $session->getIdAdmin();
        }

        return $stmt->execute($params);
    }

    public function findAll(): array
    {
        $stmt = $this->pdo->query("SELECT * FROM session_vote ORDER BY dateDebut DESC");
        $sessions = [];
        while ($data = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $sessions[] = $this->hydrateSession($data);
        }
        return $sessions;
    }

    public function find(int $id): ?SessionVote
    {
        $stmt = $this->pdo->prepare("SELECT * FROM session_vote WHERE id_sessionvote = :id");
        $stmt->execute([':id' => $id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        return $data ? $this->hydrateSession($data) : null;
    }

    public function findActive(): array
    {
        $today = date('Y-m-d');
        $stmt = $this->pdo->prepare("
            SELECT * FROM session_vote 
            WHERE dateDebut <= :today AND dateFin >= :today2
            ORDER BY dateDebut DESC
        ");
        $stmt->execute([':today' => $today, ':today2' => $today]);
        $sessions = [];
        while ($data = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $sessions[] = $this->hydrateSession($data);
        }
        return $sessions;
    }

    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM session_vote WHERE id_sessionvote = :id");
        return $stmt->execute([':id' => $id]);
    }

    public function addFilmToSession(int $sessionId, int $filmId): bool
    {
        try {
            $stmt = $this->pdo->prepare("
                INSERT INTO session_vote_film (id_sessionvote, id_film)
                VALUES (:session_id, :film_id)
            ");
            return $stmt->execute([
                ':session_id' => $sessionId,
                ':film_id' => $filmId
            ]);
        } catch (\PDOException $e) {
            return false;
        }
    }

    public function removeFilmFromSession(int $sessionId, int $filmId): bool
    {
        $stmt = $this->pdo->prepare("
            DELETE FROM session_vote_film 
            WHERE id_sessionvote = :session_id AND id_film = :film_id
        ");
        return $stmt->execute([
            ':session_id' => $sessionId,
            ':film_id' => $filmId
        ]);
    }

    public function getFilmsBySession(int $sessionId): array
    {
        $stmt = $this->pdo->prepare("
            SELECT f.* FROM film f
            INNER JOIN session_vote_film svf ON f.id_film = svf.id_film
            WHERE svf.id_sessionvote = :session_id
        ");
        $stmt->execute([':session_id' => $sessionId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    private function hydrateSession(array $data): SessionVote
    {
        $session = new SessionVote();
        $session->setIdSessionvote((int)$data['id_sessionvote']);
        $session->setDateDebut($data['dateDebut']);
        $session->setDateFin($data['dateFin']);
        $session->setDuree((int)$data['duree']);
        $session->setAnnee((int)$data['annee']);
        $session->setIdAdmin((int)$data['id_admin']);
        return $session;
    }
}

