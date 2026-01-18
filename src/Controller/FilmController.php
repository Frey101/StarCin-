<?php
// src/Controller/FilmController.php

require_once __DIR__ . '/../Model/FilmModel.php';

class FilmController {
    private $filmModel;
//permet de récuperer tous les films
    public function __construct() {
        $this->filmModel = new FilmModel();
    }
//affiche la liste des films
    public function listFilms() {
        $films = $this->filmModel->findAll();
        require ROOT_DIR . 'views/layout/header.php';
        require ROOT_DIR . 'views/films.php';
        require ROOT_DIR . 'views/layout/footer.php';
    }
}
