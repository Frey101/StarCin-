<?php
require_once ROOT_DIR . 'src/Database/DBConnection.php';
require_once ROOT_DIR . 'src/Model/SessionVoteModel.php';

/**
 * Service pour le calcul et l'affichage des résultats de vote
 * Respecte le principe SRP : gère uniquement la logique métier des résultats
 */
class ResultatService
{
    private PDO $db;
    private SessionVoteModel $sessionModel;

    public function __construct()
    {
        $this->db = DBConnection::getInstance()->getPDO();
        $this->sessionModel = new SessionVoteModel();
    }

    /**
     * Récupère les sessions terminées groupées par année
     */
    public function getFinishedSessionsByYear(): array
    {
        $today = date('Y-m-d');
        $stmt = $this->db->prepare("
            SELECT * FROM session_vote 
            WHERE dateFin < :today
            ORDER BY annee DESC, dateFin DESC
        ");
        $stmt->execute([':today' => $today]);
        $sessions = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Grouper par année
        $sessionsByYear = [];
        foreach ($sessions as $session) {
            $year = $session['annee'];
            if (!isset($sessionsByYear[$year])) {
                $sessionsByYear[$year] = [];
            }
            $sessionsByYear[$year][] = $session;
        }
        
        return $sessionsByYear;
    }

    /**
     * Calcule les résultats pour une session de vote terminée
     */
    public function calculateResultsForSession(int $sessionId): array
    {
        // Récupérer les films de la session
        $films = $this->sessionModel->getFilmsBySession($sessionId);
        
        if (empty($films)) {
            return [];
        }

        // Calculer les résultats pour chaque film
        $results = [];
        $totalVotes = 0;

        foreach ($films as $film) {
            $filmId = $film['id_film'];
            
            // Compter le nombre de votes pour ce film
            $stmt = $this->db->prepare("
                SELECT COUNT(*) as nb_votes, AVG(valeur) as note_moyenne
                FROM note 
                WHERE id_film = ?
            ");
            $stmt->execute([$filmId]);
            $stats = $stmt->fetch(PDO::FETCH_ASSOC);
            
            $nbVotes = (int)($stats['nb_votes'] ?? 0);
            $noteMoyenne = $stats['note_moyenne'] ? round((float)$stats['note_moyenne'], 2) : 0;
            
            $results[] = [
                'film' => $film,
                'nb_votes' => $nbVotes,
                'note_moyenne' => $noteMoyenne
            ];
            
            $totalVotes += $nbVotes;
        }

        // Calculer les pourcentages
        foreach ($results as &$result) {
            if ($totalVotes > 0) {
                $result['pourcentage'] = round(($result['nb_votes'] / $totalVotes) * 100, 1);
            } else {
                $result['pourcentage'] = 0;
            }
        }

        // Trier par nombre de votes décroissant
        usort($results, function($a, $b) {
            if ($a['nb_votes'] === $b['nb_votes']) {
                return $b['note_moyenne'] <=> $a['note_moyenne'];
            }
            return $b['nb_votes'] <=> $a['nb_votes'];
        });

        return $results;
    }

    /**
     * Récupère les résultats pour une année spécifique
     */
    public function getResultsByYear(int $year): array
    {
        $today = date('Y-m-d');
        $stmt = $this->db->prepare("
            SELECT * FROM session_vote 
            WHERE annee = :year AND dateFin < :today
            ORDER BY dateFin DESC
            LIMIT 1
        ");
        $stmt->execute([':year' => $year, ':today' => $today]);
        $session = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$session) {
            return [];
        }

        return $this->calculateResultsForSession((int)$session['id_sessionvote']);
    }

    /**
     * Récupère toutes les années avec des résultats disponibles
     */
    public function getAvailableYears(): array
    {
        $today = date('Y-m-d');
        $stmt = $this->db->prepare("
            SELECT DISTINCT annee 
            FROM session_vote 
            WHERE dateFin < :today
            ORDER BY annee DESC
        ");
        $stmt->execute([':today' => $today]);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }
}

