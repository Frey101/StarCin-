<?php
require_once ROOT_DIR . 'src/Model/RealisateurModel.php';
require_once ROOT_DIR . 'src/Controller/SecurityController.php';

/**
 * Contrôleur pour la gestion des réalisateurs (Admin uniquement)
 * Respecte le principe SRP : gère uniquement les actions admin sur les réalisateurs
 */
class RealisateurController
{
    private RealisateurModel $model;

    public function __construct()
    {
        SecurityController::restrictAccess();
        $this->model = new RealisateurModel();
    }

    /**
     * Affiche la liste des réalisateurs
     */
    public function listRealisateurs(): void
    {
        $realisateurs = $this->model->findAll();
        $nonRealisateurs = $this->model->getNonRealisateurs();

        require ROOT_DIR . 'views/layout/header.php';
        require ROOT_DIR . 'views/admin/realisateur_list.php';
        require ROOT_DIR . 'views/layout/footer.php';
    }

    /**
     * Ajoute un utilisateur comme réalisateur
     */
    public function addRealisateur(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . ROOT_PATH . 'index.php?action=admin_realisateurs');
            exit;
        }

        $userId = isset($_POST['user_id']) ? (int)$_POST['user_id'] : 0;

        if ($userId <= 0) {
            header('Location: ' . ROOT_PATH . 'index.php?action=admin_realisateurs&error=invalid_user');
            exit;
        }

        if ($this->model->addRealisateur($userId)) {
            header('Location: ' . ROOT_PATH . 'index.php?action=admin_realisateurs&status=added');
        } else {
            header('Location: ' . ROOT_PATH . 'index.php?action=admin_realisateurs&error=add_failed');
        }
        exit;
    }

    /**
     * Supprime un réalisateur
     */
    public function removeRealisateur(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . ROOT_PATH . 'index.php?action=admin_realisateurs');
            exit;
        }

        $realisateurId = isset($_POST['realisateur_id']) ? (int)$_POST['realisateur_id'] : 0;

        if ($realisateurId <= 0) {
            header('Location: ' . ROOT_PATH . 'index.php?action=admin_realisateurs&error=invalid_id');
            exit;
        }

        if ($this->model->removeRealisateur($realisateurId)) {
            header('Location: ' . ROOT_PATH . 'index.php?action=admin_realisateurs&status=removed');
        } else {
            header('Location: ' . ROOT_PATH . 'index.php?action=admin_realisateurs&error=remove_failed');
        }
        exit;
    }
}