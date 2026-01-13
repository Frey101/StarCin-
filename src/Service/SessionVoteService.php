<?php
require_once ROOT_DIR . 'src/Model/SessionVoteModel.php';
require_once ROOT_DIR . 'src/Entity/SessionVote.php';

/**
 * Service pour la gestion des sessions de vote
 * Respecte le principe SRP : gère uniquement la logique métier des sessions
 */
class SessionVoteService
{
    private SessionVoteModel $model;

    public function __construct()
    {
        $this->model = new SessionVoteModel();
    }

    public function getAllSessions(): array
    {
        return $this->model->findAll();
    }

    public function getActiveSessions(): array
    {
        return $this->model->findActive();
    }

    public function getSession(int $id): ?SessionVote
    {
        return $this->model->find($id);
    }

    public function getFilmsBySession(int $sessionId): array
    {
        $films = $this->model->getFilmsBySession($sessionId);
        // Ajouter le chemin de l'image pour chaque film
        require_once ROOT_DIR . 'src/Repository/AdminRepository.php';
        $repository = new AdminRepository();
        foreach ($films as &$film) {
            if (isset($film['id_film'])) {
                $film['image'] = $repository->getFilmImagePath($film['id_film']);
            }
        }
        return $films;
    }

    public function createSession(array $data, int $adminId): ?string
    {
        $errors = $this->validateSessionData($data);
        if (!empty($errors)) {
            return implode(', ', $errors);
        }

        $session = new SessionVote();
        $session->setDateDebut($data['dateDebut']);
        $session->setDateFin($data['dateFin']);
        $session->setDuree((int)$data['duree']);
        $session->setAnnee((int)$data['annee']);
        $session->setIdAdmin($adminId);

        if (!$this->model->save($session)) {
            return "Erreur lors de la création de la session.";
        }

        // Récupérer l'ID de la session créée
        require_once ROOT_DIR . 'src/Database/DBConnection.php';
        $pdo = DBConnection::getInstance()->getPDO();
        $sessionId = (int)$pdo->lastInsertId();
        
        // Ajouter les films à la session
        if (isset($data['films']) && is_array($data['films']) && !empty($data['films'])) {
            foreach ($data['films'] as $filmId) {
                if ($filmId > 0) {
                    $this->model->addFilmToSession($sessionId, (int)$filmId);
                }
            }
        } else {
            // Si aucun film n'est sélectionné, retourner une erreur
            // Supprimer la session créée
            $this->model->delete($sessionId);
            return "Veuillez sélectionner au moins un film pour la session de vote.";
        }

        return null;
    }

    public function updateSession(int $id, array $data): ?string
    {
        $session = $this->model->find($id);
        if (!$session) {
            return "Session non trouvée.";
        }

        $errors = $this->validateSessionData($data);
        if (!empty($errors)) {
            return implode(', ', $errors);
        }

        $session->setDateDebut($data['dateDebut']);
        $session->setDateFin($data['dateFin']);
        $session->setDuree((int)$data['duree']);
        $session->setAnnee((int)$data['annee']);

        if (!$this->model->save($session)) {
            return "Erreur lors de la mise à jour de la session.";
        }

        return null;
    }

    public function deleteSession(int $id): bool
    {
        return $this->model->delete($id);
    }

    private function validateSessionData(array $data): array
    {
        $errors = [];

        if (empty($data['dateDebut'])) {
            $errors[] = 'La date de début est requise';
        }

        if (empty($data['dateFin'])) {
            $errors[] = 'La date de fin est requise';
        }

        if (!empty($data['dateDebut']) && !empty($data['dateFin'])) {
            if (strtotime($data['dateDebut']) > strtotime($data['dateFin'])) {
                $errors[] = 'La date de début doit être antérieure à la date de fin';
            }
        }

        if (empty($data['duree']) || !is_numeric($data['duree']) || $data['duree'] <= 0) {
            $errors[] = 'La durée doit être un nombre positif';
        }

        if (empty($data['annee']) || !is_numeric($data['annee'])) {
            $errors[] = 'L\'année est requise';
        }

        return $errors;
    }
}

