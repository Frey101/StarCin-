<?php
// src/Service/AdminFilmService.php

require_once ROOT_DIR . 'src/Repository/IAdminRepository.php';

/**
 * Service pour la gestion des films en admin
 * Respecte le principe SRP : gère uniquement la logique métier des films
 */
class AdminFilmService
{
    private IAdminRepository $repository;

    public function __construct(IAdminRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getAllFilms(): array
    {
        return $this->repository->getFilmsWithRatings();
    }

    public function getFilm(int $id): ?array
    {
        return $this->repository->getFilmById($id);
    }

    public function deleteFilm(int $id): bool
    {
        return $this->repository->deleteFilm($id);
    }

    public function validateFilmData(array $data): array
    {
        $errors = [];

        if (empty(trim($data['title'] ?? ''))) {
            $errors[] = 'Le titre est requis';
        }

        if (empty(trim($data['synopsis'] ?? ''))) {
            $errors[] = 'Le synopsis est requis';
        }

        if (empty($data['release_year'] ?? '') || !is_numeric($data['release_year'])) {
            $errors[] = 'L\'année de sortie est requise et doit être un nombre';
        }

        return $errors;
    }
}

