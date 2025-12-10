<?php
// Fichier : src/Model/FilmModel.php

// Inclure les dépendances (le chemin doit être ajusté si nécessaire)
require_once __DIR__ . '/../Database/DBConnection.php';
require_once __DIR__ . '/../Entity/Film.php';

class FilmModel {
    private $pdo;

    public function __construct() {
        // Initialisation de la connexion PDO
        $this->pdo = DBConnection::getInstance()->getPDO();
    }

    /**
     * Insère un nouveau film ou met à jour un film existant (CREATE ou UPDATE).
     * @param Film $film L'objet Film à enregistrer.
     * @return bool Vrai si l'opération a réussi, Faux sinon.
     */
    public function save(Film $film): bool {

        if ($film->getIdFilm() === null) {
            // C'est un NOUVEAU film (INSERT)
            $sql = "INSERT INTO film (title, director, release_year, synopsis) 
                    VALUES (:title, :director, :release_year, :synopsis)";
        } else {
            // C'est une MODIFICATION (UPDATE)
            $sql = "UPDATE film SET title = :title, director = :director, release_year = :release_year, synopsis = :synopsis 
                    WHERE id_film = :id_film";
        }

        $stmt = $this->pdo->prepare($sql);

        $params = [
            ':title' => $film->getTitle(),
            ':director' => $film->getDirector(),
            ':release_year' => $film->getReleaseYear(),
            ':synopsis' => $film->getSynopsis()
        ];

        if ($film->getIdFilm() !== null) {
            $params[':id_film'] = $film->getIdFilm();
        }

        return $stmt->execute($params);
    }

    /**
     * Récupère TOUS les films de la DB et retourne un tableau d'objets Film.
     * @return array<Film>
     */
    public function findAll(): array {
        $stmt = $this->pdo->query("SELECT * FROM film ORDER BY title ASC");
        $films = [];

        while ($data = $stmt->fetch()) {
            $films[] = $this->hydrateFilm($data);
        }

        return $films;
    }

    /**
     * Récupère un film par son ID et retourne un objet Film.
     * @param int $id
     * @return Film|null
     */
    public function find(int $id): ?Film {
        $stmt = $this->pdo->prepare("SELECT * FROM film WHERE id_film = :id");
        $stmt->execute([':id' => $id]);
        $data = $stmt->fetch();

        if (!$data) {
            return null;
        }

        return $this->hydrateFilm($data);
    }

    /**
     * Supprime un film par son ID.
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool {
        $stmt = $this->pdo->prepare("DELETE FROM film WHERE id_film = :id");
        return $stmt->execute([':id' => $id]);
    }

    /**
     * Méthode privée pour créer un objet Film à partir des données DB (Hydratation).
     * @param array $data Les données du film issues de la base de données.
     * @return Film L'objet film hydraté.
     */
    private function hydrateFilm(array $data): Film {
        $film = new Film();
        $film->setIdFilm((int)$data['id_film']);
        $film->setTitle($data['title']);
        $film->setDirector($data['director']);
        $film->setReleaseYear((int)$data['release_year']);
        $film->setSynopsis($data['synopsis']);
        // Ajouter ici les autres propriétés
        return $film;
    }
}