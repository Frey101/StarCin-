<?php
// src/Repository/IAdminRepository.php

/**
 * Interface pour le repository admin
 * Respecte le principe ISP (Interface Segregation) et DIP (Dependency Inversion)
 */
interface IAdminRepository
{
    // Statistiques
    public function countFilms(): int;
    public function countUsers(): int;
    public function countPropositions(): int;
    public function countVotes(): int;
    
    // Films
    public function getFilmsWithRatings(): array;
    public function getFilmById(int $id): ?array;
    public function deleteFilm(int $id): bool;
    
    // Utilisateurs
    public function getUsers(): array;
    public function getUserById(int $id): ?array;
    public function promoteUser(int $id): bool;
    public function deleteUser(int $id): bool;
    
    // Propositions
    public function getPropositions(): array;
    public function updatePropositionStatus(int $id, string $status): bool;
    
    // Commentaires
    public function getCommentsWithDetails(): array;
    public function deleteComment(int $id): bool;
}

