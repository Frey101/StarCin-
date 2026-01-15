<?php
// src/Controller/Admin/AdminDashboardController.php

require_once ROOT_DIR . 'src/Controller/SecurityController.php';
require_once ROOT_DIR . 'src/Repository/AdminRepository.php';
require_once ROOT_DIR . 'src/Service/AdminStatsService.php';
require_once ROOT_DIR . 'src/Service/AdminFilmService.php';
require_once ROOT_DIR . 'src/Service/AdminUserService.php';
require_once ROOT_DIR . 'src/Service/PropositionFilmService.php';

/**
 * Contrôleur pour le tableau de bord administrateur
 * Respecte le principe SRP : coordonne uniquement la présentation
 * Respecte le principe DIP : dépend d'interfaces/services
 */
class AdminDashboardController
{
    private IAdminRepository $repository;
    private IAdminStatsService $statsService;
    private AdminFilmService $filmService;
    private AdminUserService $userService;
    private PropositionFilmService $propositionService;

    public function __construct()
    {
        SecurityController::restrictAccess();
        
        // Injection de dépendances (DIP)
        $this->repository = new AdminRepository();
        $this->statsService = new AdminStatsService($this->repository);
        $this->filmService = new AdminFilmService($this->repository);
        $this->userService = new AdminUserService($this->repository);
        $this->propositionService = new PropositionFilmService();
    }

    /**
     * Affiche le tableau de bord administrateur
     */
    public function dashboard(): void
    {
        $stats = $this->statsService->getDashboardStats();
        $films = $this->filmService->getAllFilms();
        $users = $this->userService->getAllUsers();
        $propositions = $this->propositionService->getAllPropositions();
        $comments = $this->repository->getCommentsWithDetails();

        require ROOT_DIR . 'views/layout/header.php';
        require ROOT_DIR . 'views/admin/dashboard.php';
        // Footer déjà inclus dans dashboard.php
    }

    /**
     * Supprime un commentaire
     */
    public function deleteComment(): void
    {
        if (isset($_GET['id']) && is_numeric($_GET['id'])) {
            $id = (int)$_GET['id'];
            if ($this->repository->deleteComment($id)) {
                header('Location: ' . ROOT_PATH . 'index.php?action=admin_dashboard&status=comment_deleted');
            } else {
                header('Location: ' . ROOT_PATH . 'index.php?action=admin_dashboard&status=error');
            }
            exit;
        }
        header('Location: ' . ROOT_PATH . 'index.php?action=admin_dashboard');
        exit;
    }
}
