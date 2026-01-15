<?php
require_once ROOT_DIR . 'src/Service/SessionVoteService.php';
require_once ROOT_DIR . 'src/Service/VoteService.php';
require_once ROOT_DIR . 'src/Controller/SecurityController.php';

/**
 * Contrôleur pour la gestion des votes
 * Respecte le principe SRP : gère uniquement les actions de vote
 */
class VoteController
{
    private SessionVoteService $sessionService;
    private VoteService $voteService;

    public function __construct()
    {
        $this->sessionService = new SessionVoteService();
        $this->voteService = new VoteService();
    }

    public function showVotePage(): void
    {
        // Vérifier que l'utilisateur est connecté
        if (!isset($_SESSION['utilisateur_connecte']) || !$_SESSION['utilisateur_connecte']) {
            header('Location: ' . ROOT_PATH . 'index.php?action=login');
            exit;
        }

        $userId = $_SESSION['user_id'] ?? null;
        $error = null;
        
        // Vérifier que ce n'est pas un admin
        if ($userId && !$this->voteService->canUserVote($userId)) {
            $error = "Les administrateurs ne peuvent pas voter.";
        }

        $activeSessions = $this->sessionService->getActiveSessions();
        $sessionsWithFilms = [];
        $userVotedSessions = []; // Sessions où l'utilisateur a déjà voté

        foreach ($activeSessions as $session) {
            $films = $this->sessionService->getFilmsBySession($session->getIdSessionvote());
            $hasVoted = false;
            
            if ($userId) {
                $hasVoted = $this->voteService->hasUserVotedInSession($userId, $session->getIdSessionvote());
            }
            
            $sessionsWithFilms[] = [
                'session' => $session,
                'films' => $films,
                'hasVoted' => $hasVoted
            ];
            
            if ($hasVoted) {
                $userVotedSessions[] = $session->getIdSessionvote();
            }
        }

        // Passer les variables à la vue
        $sessionsWithFilms = $sessionsWithFilms;
        $error = $error;
        $userVotedSessions = $userVotedSessions;

        require ROOT_DIR . 'views/layout/header.php';
        require ROOT_DIR . 'views/vote.php';
        require ROOT_DIR . 'views/layout/footer.php';
    }

    public function submitVote(): void
    {
        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            header('Location: ' . ROOT_PATH . 'index.php?action=vote_page');
            exit;
        }

        $userId = $_SESSION['user_id'] ?? null;
        if (!$userId) {
            header('Location: ' . ROOT_PATH . 'index.php?action=login');
            exit;
        }

        if (!$this->voteService->canUserVote($userId)) {
            header('Location: ' . ROOT_PATH . 'index.php?action=vote_page&error=admin_cannot_vote');
            exit;
        }

        $filmId = isset($_POST['film_id']) ? (int)$_POST['film_id'] : 0;
        $note = isset($_POST['note']) ? (int)$_POST['note'] : 0;
        $sessionId = isset($_POST['session_id']) ? (int)$_POST['session_id'] : 0;

        if ($filmId <= 0 || $note < 1 || $note > 5 || $sessionId <= 0) {
            header('Location: ' . ROOT_PATH . 'index.php?action=vote_page&error=invalid_data');
            exit;
        }

        // Vérifier si l'utilisateur a déjà voté dans cette session
        if ($this->voteService->hasUserVotedInSession($userId, $sessionId)) {
            header('Location: ' . ROOT_PATH . 'index.php?action=vote_page&error=' . urlencode('Vous avez déjà voté dans cette session.'));
            exit;
        }

        $result = $this->voteService->submitVote($userId, $filmId, $note, $sessionId);
        
        if ($result === null) {
            header('Location: ' . ROOT_PATH . 'index.php?action=vote_page&status=vote_success');
        } else {
            header('Location: ' . ROOT_PATH . 'index.php?action=vote_page&error=' . urlencode($result));
        }
        exit;
    }
}

