<?php
// src/Service/AdminStatsService.php

require_once ROOT_DIR . 'src/Repository/IAdminRepository.php';
require_once ROOT_DIR . 'src/Service/IAdminStatsService.php';

/**
 * Service pour les statistiques admin
 * Respecte le principe SRP : gère uniquement les statistiques
 * Respecte le principe DIP : dépend de IAdminRepository
 */
class AdminStatsService implements IAdminStatsService
{
    private IAdminRepository $repository;

    public function __construct(IAdminRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getDashboardStats(): array
    {
        return [
            'films' => $this->repository->countFilms(),
            'users' => $this->repository->countUsers(),
            'propositions' => $this->repository->countPropositions(),
            'votes' => $this->repository->countVotes()
        ];
    }

    public function getFilmsStats(): array
    {
        $films = $this->repository->getFilmsWithRatings();
        $totalVotes = $this->repository->countVotes();
        
        return [
            'total' => count($films),
            'with_ratings' => count(array_filter($films, fn($f) => $f['note_moyenne'] !== null)),
            'total_votes' => $totalVotes,
            'films' => $films
        ];
    }

    public function getUsersStats(): array
    {
        $users = $this->repository->getUsers();
        $admins = count(array_filter($users, fn($u) => $u['role'] === 'admin'));
        
        return [
            'total' => count($users),
            'admins' => $admins,
            'regular' => count($users) - $admins,
            'users' => $users
        ];
    }
}

