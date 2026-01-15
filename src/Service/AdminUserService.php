<?php
// src/Service/AdminUserService.php

require_once ROOT_DIR . 'src/Repository/IAdminRepository.php';

/**
 * Service pour la gestion des utilisateurs en admin
 * Respecte le principe SRP : gère uniquement la logique métier des utilisateurs
 */
class AdminUserService
{
    private IAdminRepository $repository;

    public function __construct(IAdminRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getAllUsers(): array
    {
        return $this->repository->getUsers();
    }

    public function getUser(int $id): ?array
    {
        return $this->repository->getUserById($id);
    }

    public function promoteToAdmin(int $id): bool
    {
        $user = $this->repository->getUserById($id);
        if (!$user || $user['role'] === 'admin') {
            return false;
        }
        return $this->repository->promoteUser($id);
    }

    public function deleteUser(int $id): bool
    {
        // Empêcher la suppression de son propre compte
        if (isset($_SESSION['user_id']) && (int)$_SESSION['user_id'] === $id) {
            return false;
        }
        return $this->repository->deleteUser($id);
    }
}

