<?php
// src/Controller/Admin/AdminFilmController.php

require_once ROOT_DIR . 'src/Controller/SecurityController.php';
require_once ROOT_DIR . 'src/Repository/AdminRepository.php';
require_once ROOT_DIR . 'src/Service/AdminFilmService.php';
require_once ROOT_DIR . 'src/Model/FilmModel.php';
require_once ROOT_DIR . 'src/Entity/Film.php';

/**
 * Contrôleur pour la gestion des films en admin
 * Respecte le principe SRP : gère uniquement les actions liées aux films
 */
class AdminFilmController 
{
    private AdminFilmService $filmService;
    private FilmModel $filmModel;

    public function __construct() 
    {
        SecurityController::restrictAccess();
        
        $repository = new AdminRepository();
        $this->filmService = new AdminFilmService($repository);
        $this->filmModel = new FilmModel();
    }

    /**
     * Liste tous les films
     */
    public function listFilms(): void 
    {
        $films = $this->filmService->getAllFilms();
        require ROOT_DIR . 'views/layout/header.php';
        require ROOT_DIR . 'views/admin/film_list.php';
        // Footer déjà inclus dans film_list.php
    }

    /**
     * Gère l'affichage et le traitement du formulaire d'ajout/modification
     */
    public function handleFilmForm(): void 
    {
        $film = $this->loadFilm();
        $message = null;
        $errors = [];

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            // Validation des données du formulaire
            $errors = $this->validateFilmForm($film->getIdFilm());
            
            if (empty($errors)) {
                $message = $this->processFilmForm($film);
                if ($message === null) {
                    $status = ($film->getIdFilm() === null) ? 'success' : 'updated';
                    header('Location: ' . ROOT_PATH . 'index.php?action=admin_list_films&status=' . $status);
                    exit;
                }
            } else {
                $message = implode(', ', $errors);
            }
        }

        require ROOT_DIR . 'views/layout/header.php';
        require ROOT_DIR . 'views/admin/film_form.php';
        // Footer déjà inclus dans film_form.php
    }

    /**
     * Supprime un film
     */
    public function deleteFilm(): void 
    {
        if (isset($_GET['id']) && is_numeric($_GET['id'])) {
            $id = (int)$_GET['id'];
            
            // Supprimer les images du film
            $this->deleteFilmImages($id);
            
            if ($this->filmService->deleteFilm($id)) {
                header('Location: ' . ROOT_PATH . 'index.php?action=admin_list_films&status=deleted');
            } else {
                header('Location: ' . ROOT_PATH . 'index.php?action=admin_list_films&status=error');
            }
            exit;
        }
        header('Location: ' . ROOT_PATH . 'index.php?action=admin_dashboard');
        exit;
    }

    private function loadFilm(): Film 
    {
        $film = new Film();
        
        if (isset($_GET['id']) && is_numeric($_GET['id'])) {
            $filmData = $this->filmModel->find((int)$_GET['id']);
            if ($filmData) {
                return $filmData;
            }
        }
        
        return $film;
    }

    private function processFilmForm(Film $film): ?string 
    {
        try {
            if (isset($_POST['id_film']) && is_numeric($_POST['id_film'])) {
                $film->setIdFilm((int)$_POST['id_film']);
            }

            $film->setTitre(trim($_POST['title'] ?? ''));
            $film->setSynopsis(trim($_POST['synopsis'] ?? ''));
            $film->setAnnee(isset($_POST['release_year']) && is_numeric($_POST['release_year']) ? (int)$_POST['release_year'] : null);
            $film->setCategorie(trim($_POST['categorie'] ?? ''));
            $film->setDuree(isset($_POST['duree']) && is_numeric($_POST['duree']) ? (int)$_POST['duree'] : 0);
            $film->setBandeAnnonce(trim($_POST['bandeannonce'] ?? ''));
            $film->setDateDiffusion(trim($_POST['datediffusion'] ?? ''));

            // Gestion de l'upload d'image (avant la sauvegarde pour les nouveaux films)
            $imageUploadResult = $this->handleImageUpload($film->getIdFilm());
            if ($imageUploadResult === false) {
                return "Erreur lors de l'upload de l'image.";
            }
            if ($imageUploadResult === null && !$film->getIdFilm()) {
                return "L'image est requise pour ajouter un nouveau film.";
            }

            // Sauvegarder le film
            if (!$this->filmModel->save($film)) {
                return "Erreur lors de l'enregistrement en base de données.";
            }

            // Si c'est un nouveau film et qu'une image a été uploadée, renommer l'image avec l'ID
            if ($film->getIdFilm() !== null && isset($_SESSION['temp_film_image'])) {
                $this->renameTempImage($film->getIdFilm());
            }

            return null;
        } catch (\Throwable $e) {
            return "Erreur de traitement : " . $e->getMessage();
        }
    }

    /**
     * Gère l'upload de l'image du film
     * Utilise une convention de nommage basée sur l'ID : film_{id}.{ext}
     * @return bool true si succès, false en cas d'erreur, null si aucune image
     */
    private function handleImageUpload(?int $filmId = null)
    {
        if (!isset($_FILES['image_file']) || $_FILES['image_file']['error'] === UPLOAD_ERR_NO_FILE) {
            return null; // Aucun fichier uploadé
        }

        if ($_FILES['image_file']['error'] !== UPLOAD_ERR_OK) {
            return false; // Erreur d'upload
        }

        $file = $_FILES['image_file'];
        $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
        $maxSize = 5 * 1024 * 1024; // 5MB

        // Vérification du type
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);

        if (!in_array($mimeType, $allowedTypes)) {
            return false;
        }

        // Vérification de la taille
        if ($file['size'] > $maxSize) {
            return false;
        }

        // Génération du nom de fichier basé sur l'ID du film
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        
        // Si c'est un nouveau film, on utilisera un nom temporaire puis on le renommera après l'insertion
        if ($filmId === null) {
            $fileName = 'temp_' . time() . '.' . $extension;
        } else {
            $fileName = "film_{$filmId}.{$extension}";
        }

        // Chemin de destination
        $uploadDir = ROOT_DIR . 'public/image/';
        $destination = $uploadDir . $fileName;

        // Créer le dossier s'il n'existe pas
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        // Supprimer les anciennes images du film (toutes les extensions)
        if ($filmId !== null) {
            $this->deleteFilmImages($filmId);
        }

        // Déplacer le fichier
        if (move_uploaded_file($file['tmp_name'], $destination)) {
            // Si c'était un fichier temporaire, on le garde pour le renommer après l'insertion
            if ($filmId === null) {
                // Stocker le nom temporaire dans une variable de session ou retourner le chemin
                $_SESSION['temp_film_image'] = $destination;
            }
            return true;
        }

        return false;
    }

    /**
     * Supprime toutes les images d'un film (toutes les extensions)
     */
    private function deleteFilmImages(int $filmId): void
    {
        $imageDir = ROOT_DIR . 'public/image/';
        $extensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        
        foreach ($extensions as $ext) {
            $filePath = $imageDir . "film_{$filmId}.{$ext}";
            if (file_exists($filePath) && is_file($filePath)) {
                @unlink($filePath);
            }
        }
    }

    /**
     * Renomme l'image temporaire avec l'ID du film après insertion
     */
    private function renameTempImage(int $filmId): bool
    {
        if (!isset($_SESSION['temp_film_image']) || !file_exists($_SESSION['temp_film_image'])) {
            return false;
        }

        $tempPath = $_SESSION['temp_film_image'];
        $extension = pathinfo($tempPath, PATHINFO_EXTENSION);
        $newFileName = "film_{$filmId}.{$extension}";
        $newPath = ROOT_DIR . 'public/image/' . $newFileName;

        if (rename($tempPath, $newPath)) {
            unset($_SESSION['temp_film_image']);
            return true;
        }

        return false;
    }

    /**
     * Valide les données du formulaire de film
     */
    private function validateFilmForm(?int $filmId = null): array
    {
        $errors = [];

        if (empty(trim($_POST['title'] ?? ''))) {
            $errors[] = 'Le titre est requis';
        }

        if (empty(trim($_POST['synopsis'] ?? ''))) {
            $errors[] = 'Le synopsis est requis';
        }

        if (empty($_POST['release_year'] ?? '') || !is_numeric($_POST['release_year'])) {
            $errors[] = 'L\'année de sortie est requise et doit être un nombre';
        }

        // Vérification de l'image
        if ($filmId === null) {
            // Nouveau film : image requise
            if (!isset($_FILES['image_file']) || $_FILES['image_file']['error'] === UPLOAD_ERR_NO_FILE) {
                $errors[] = 'L\'image est requise pour ajouter un nouveau film';
            }
        }

        // Si une image est uploadée, vérifier son type et sa taille
        if (isset($_FILES['image_file']) && $_FILES['image_file']['error'] === UPLOAD_ERR_OK) {
            $file = $_FILES['image_file'];
            $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
            $maxSize = 5 * 1024 * 1024; // 5MB

            if ($file['size'] > $maxSize) {
                $errors[] = 'L\'image est trop volumineuse (maximum 5MB)';
            }

            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mimeType = finfo_file($finfo, $file['tmp_name']);
            finfo_close($finfo);

            if (!in_array($mimeType, $allowedTypes)) {
                $errors[] = 'Le format d\'image n\'est pas supporté (JPG, PNG, GIF, WebP uniquement)';
            }
        }

        return $errors;
    }
}
