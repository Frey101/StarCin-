<?php
// src/Model/FilmModel.php

require_once __DIR__ . '/../Database/DBConnection.php';
require_once __DIR__ . '/../Entity/Film.php';

class FilmModel
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = DBConnection::getInstance()->getPDO();
    }

    /**

     * CREATE ou UPDATE un film
     */
    public function save(Film $film): bool
    {
        if ($film->getIdFilm() === null) {
            // INSERT
            $sql = "
                INSERT INTO film 
                (titre, synopsis, duree, bandeannonce, datediffusion, categorie, annee)
                VALUES 
                (:titre, :synopsis, :duree, :bandeannonce, :datediffusion, :categorie, :annee)
            ";
        } else {
            // UPDATE
            $sql = "
                UPDATE film SET
                    titre = :titre,
                    synopsis = :synopsis,
                    duree = :duree,
                    bandeannonce = :bandeannonce,
                    datediffusion = :datediffusion,
                    categorie = :categorie,
                    annee = :annee
                WHERE id_film = :id_film
            ";
        }

        $stmt = $this->pdo->prepare($sql);

        $params = [
            ':titre'          => $film->getTitre(),
            ':synopsis'       => $film->getSynopsis(),
            ':duree'          => $film->getDuree(),
            ':bandeannonce'   => $film->getBandeAnnonce(),
            ':datediffusion'  => $film->getDateDiffusion(),
            ':categorie'      => $film->getCategorie(),
            ':annee'          => $film->getAnnee(),
        ];

        if ($film->getIdFilm() !== null) {
            $params[':id_film'] = $film->getIdFilm();
        }

        $result = $stmt->execute($params);
        
        // Si c'est un INSERT, récupérer l'ID généré pour renommer l'image
        if ($result && $film->getIdFilm() === null) {
            $newId = (int)$this->pdo->lastInsertId();
            $film->setIdFilm($newId);
            // L'image sera renommée dans le contrôleur après l'insertion
        }

        return $result;
    }

    /**

     * Retourne tous les films
     * @return Film[]
     */
    public function findAll(): array
    {
        $stmt = $this->pdo->query("SELECT * FROM film ORDER BY titre ASC");
        $films = [];

        while ($data = $stmt->fetch(PDO::FETCH_ASSOC)) {

            $films[] = $this->hydrateFilm($data);
        }

        return $films;
    }

    /**

     * Retourne un film par ID
     */
    public function find(int $id): ?Film
    {
        $stmt = $this->pdo->prepare("SELECT * FROM film WHERE id_film = :id");
        $stmt->execute([':id' => $id]);

        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        return $data ? $this->hydrateFilm($data) : null;
    }

    /**
     * Supprime un film
     */
    public function delete(int $id): bool
    {

        $stmt = $this->pdo->prepare("DELETE FROM film WHERE id_film = :id");
        return $stmt->execute([':id' => $id]);
    }

    /**

     * Hydratation Film
     */
    private function hydrateFilm(array $data): Film
    {
        $film = new Film();

        $film->setIdFilm((int) $data['id_film']);
        $film->setTitre($data['titre']);
        $film->setSynopsis($data['synopsis']);
        $film->setDuree((int) $data['duree']);
        $film->setBandeAnnonce($data['bandeannonce'] ?? null);
        $film->setDateDiffusion($data['datediffusion'] ?? null);
        $film->setCategorie($data['categorie'] ?? null);
        $film->setAnnee($data['annee'] !== null ? (int)$data['annee'] : null);
        
        // Image seulement si la colonne existe
        if (isset($data['image'])) {
            $film->setImage($data['image']);
        }

        return $film;
    }
}

