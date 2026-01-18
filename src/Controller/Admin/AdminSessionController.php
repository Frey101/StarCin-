<?php
require_once ROOT_DIR . 'src/Controller/SecurityController.php';
require_once ROOT_DIR . 'src/Service/SessionVoteService.php';
require_once ROOT_DIR . 'src/Repository/AdminRepository.php';

/**
 * Contrôleur pour la gestion des sessions de vote (Admin)
 * Respecte le principe SRP : gère uniquement les actions admin sur les sessions
 */
class AdminSessionController
{
    private SessionVoteService $sessionService;
    private AdminRepository $repository;

    public function __construct()
    {
        SecurityController::restrictAccess();
        $this->sessionService = new SessionVoteService();
        $this->repository = new AdminRepository();
    }

    public function listSessions(): void
    {
        $sessions = $this->sessionService->getAllSessions();
        $films = $this->repository->getFilmsWithRatings();
        
        require ROOT_DIR . 'views/layout/header.php';
        require ROOT_DIR . 'views/admin/session_list.php';
    }

    public function createSession(): void
    {
        $films = $this->repository->getFilmsWithRatings();
        $message = null;
        $errors = [];

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $data = $_POST;
            $adminId = $this->getAdminId(); 
            
            if (!$adminId) {
                $message = "Erreur : administrateur non trouvé.";
            } else {
                $result = $this->sessionService->createSession($data, $adminId);
                if ($result === null) {
                    header('Location: ' . ROOT_PATH . 'index.php?action=admin_sessions&status=created');
                    exit;
                } else {
                    $message = $result;
                }
            }
        }

        require ROOT_DIR . 'views/layout/header.php';
        require ROOT_DIR . 'views/admin/session_form.php';
    }

    public function deleteSession(): void
    {
        if (isset($_GET['id']) && is_numeric($_GET['id'])) {
            $id = (int)$_GET['id'];
            if ($this->sessionService->deleteSession($id)) {
                header('Location: ' . ROOT_PATH . 'index.php?action=admin_sessions&status=deleted');
            } else {
                header('Location: ' . ROOT_PATH . 'index.php?action=admin_sessions&status=error');
            }
            exit;
        }
        header('Location: ' . ROOT_PATH . 'index.php?action=admin_sessions');
        exit;
    }
//chercher l'id de l'admin connecté afin de créer une session de vote
    private function getAdminId(): ?int
    {
        $userId = $_SESSION['user_id'] ?? null;
        if (!$userId) {
            return null;
        }

        $db = DBConnection::getInstance()->getPDO();
        $stmt = $db->prepare("SELECT id_admin FROM administrateur WHERE id_utilisateur = ?");
        $stmt->execute([$userId]);
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $admin ? (int)$admin['id_admin'] : null;
    }
}

