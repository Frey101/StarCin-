<?php
require_once ROOT_DIR . 'src/Service/PropositionFilmService.php';
require_once ROOT_DIR . 'src/Controller/SecurityController.php';

/**
 * Contrôleur pour la gestion des propositions de films
 * Respecte le principe SRP : gère uniquement les actions de proposition
 */
class PropositionController
{
    private PropositionFilmService $propositionService;

    public function __construct()
    {
        $this->propositionService = new PropositionFilmService();
    }

    public function showPropositionForm(): void
    {
        // Vérifier que l'utilisateur est connecté
        if (!isset($_SESSION['utilisateur_connecte']) || !$_SESSION['utilisateur_connecte']) {
            header('Location: ' . ROOT_PATH . 'index.php?action=login');
            exit;
        }

        $userId = $_SESSION['user_id'] ?? null;
        $message = null;
        $error = null;

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $commentaire = trim($_POST['commentaire'] ?? '');
            
            if (!$userId) {
                $error = "Utilisateur non identifié.";
            } else {
                $result = $this->propositionService->createProposition($userId, $commentaire);
                if ($result === null) {
                    $message = "Votre proposition a été envoyée avec succès !";
                } else {
                    $error = $result;
                }
            }
        }

        require ROOT_DIR . 'views/layout/header.php';
        require ROOT_DIR . 'views/proposition_form.php';
        require ROOT_DIR . 'views/layout/footer.php';
    }
}

