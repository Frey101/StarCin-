<?php
// src/Controller/Admin/AdminFilmController.php

require_once __DIR__ . '/../SecurityController.php';
require_once __DIR__ . '/../../Model/FilmModel.php';
require_once __DIR__ . '/../../Entity/Film.php';

class AdminFilmController {
    private $filmModel;

    public function __construct() {
        $this->filmModel = new FilmModel();
    } 

    public function listFilms() {
        SecurityController::restrictAccess();
        $films = $this->filmModel->findAll();
        include ROOT_PATH . 'views/admin/film_list.php';
    }

    public function handleFilmForm() {
        SecurityController::restrictAccess();

        $film = new Film();
        $message = null;

        // Gère la modification (READ pour pré-remplir)
        if (isset($_GET['id']) && is_numeric($_GET['id'])) {
            $film = $this->filmModel->find((int)$_GET['id']);
            if (!$film) {
                $film = new Film();
                $message = "Film non trouvé. Vous créez un nouveau film.";
            }
        }

        // Traitement de la soumission du formulaire (POST - SAVE)
        // Ligne corrigée ci-dessous
        if ($_SERVER["REQUEST_METHOD"] === "POST") { // <-- C'est la ligne corrigée !
            try {
                if (isset($_POST['id_film']) && is_numeric($_POST['id_film'])) {
                    // S'assurer que l'ID du film est conservé pour l'UPDATE
                    $film->setIdFilm((int)$_POST['id_film']);
                }

                // Hydratation de l'objet Film
                $film->setTitle(trim($_POST['title']));
                $film->setDirector(trim($_POST['director']));
                $film->setReleaseYear((int)$_POST['release_year']);
                $film->setSynopsis(trim($_POST['synopsis']));

                if ($this->filmModel->save($film)) {
                    // Si l'ID était null, c'est un INSERT (success), sinon c'est un UPDATE (updated)
                    $status = ($film->getIdFilm() === null) ? 'success' : 'updated';
                    header('Location: ' . ROOT_PATH . 'index.php?action=admin_list_films&status=' . $status);
                    exit;
                } else {
                    $message = "Erreur lors de l'enregistrement en base de données.";
                }

            } catch (\Throwable $e) {
                $message = "Erreur de traitement : " . $e->getMessage();
                // En mode debug, vous pourriez utiliser $message = $e->getMessage();
            }
        }

        // Afficher la Vue du formulaire
        // Les variables $film et $message sont disponibles dans la vue film_form.php
        include ROOT_PATH . 'views/admin/film_form.php';
    }

    public function deleteFilm()
    {
    }
}