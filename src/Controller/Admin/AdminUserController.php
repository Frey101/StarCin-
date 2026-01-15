<?php
// src/Controller/Admin/AdminUserController.php

require_once ROOT_DIR . 'src/Controller/SecurityController.php';
require_once ROOT_DIR . 'src/Repository/AdminRepository.php';
require_once ROOT_DIR . 'src/Service/AdminUserService.php';

/**
 * Contrôleur pour la gestion des utilisateurs en admin
 * Respecte le principe SRP : gère uniquement les actions liées aux utilisateurs
 */
class AdminUserController
{
    private AdminUserService $userService;

    public function __construct()
    {
        SecurityController::restrictAccess();
        
        $repository = new AdminRepository();
        $this->userService = new AdminUserService($repository);
    }

    /**
     * Liste tous les utilisateurs
     */
    public function listUsers(): void
    {
        $users = $this->userService->getAllUsers();
        require ROOT_DIR . 'views/layout/header.php';
        require ROOT_DIR . 'views/admin/user_list.php';
        // Footer déjà inclus dans user_list.php
    }

    /**
     * Promouvoir un utilisateur au rang d'admin
     */
    public function promoteUser(): void
    {
        if (isset($_GET['id']) && is_numeric($_GET['id'])) {
            $id = (int)$_GET['id'];
            if ($this->userService->promoteToAdmin($id)) {
                header('Location: ' . ROOT_PATH . 'index.php?action=admin_users&status=promoted');
            } else {
                header('Location: ' . ROOT_PATH . 'index.php?action=admin_users&status=error');
            }
            exit;
        }
        header('Location: ' . ROOT_PATH . 'index.php?action=admin_users');
        exit;
    }

    /**
     * Supprime un utilisateur
     */
    public function deleteUser(): void
    {
        if (isset($_GET['id']) && is_numeric($_GET['id'])) {
            $id = (int)$_GET['id'];
            if ($this->userService->deleteUser($id)) {
                header('Location: ' . ROOT_PATH . 'index.php?action=admin_users&status=deleted');
            } else {
                header('Location: ' . ROOT_PATH . 'index.php?action=admin_users&status=error');
            }
            exit;
        }
        header('Location: ' . ROOT_PATH . 'index.php?action=admin_users');
        exit;
    }
}

