<?php
// index.php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 1. DÉFINITION DES CHEMINS GLOBAUX
// L'ordre est important : définissez les constantes une seule fois, au début.

// ROOT_DIR : Chemin ABSOLU du système de fichiers pour les inclusions (require/include)
// __DIR__ est le dossier où se trouve index.php.
if (!defined('ROOT_DIR')) {
    define('ROOT_DIR', __DIR__ . '/');
}

// ROOT_PATH : Chemin URL (relatif au domaine) pour les liens et les redirections header('Location: ...')
if (!defined('ROOT_PATH')) {
    // IMPORTANT : Utilisez le chemin du sous-dossier si l'application n'est pas à la racine du domaine.
    // Votre chemin est /StarCin--main/
    define('ROOT_PATH', '/StarCin--main/');
}


// 2. Inclusion de TOUTES les classes nécessaires (utilise ROOT_DIR)
require_once ROOT_DIR . 'src/Database/DBConnection.php';
require_once ROOT_DIR . 'src/Controller/SecurityController.php';
require_once ROOT_DIR . 'src/Controller/LoginController.php';
require_once ROOT_DIR . 'src/Controller/Admin/AdminController.php';
require_once ROOT_DIR . 'src/Entity/User.php';
require_once ROOT_DIR . 'src/Entity/Film.php';


$action = $_GET['action'] ?? 'home';
$adminFilmController = null;

if (strpos($action, 'admin_') === 0) {
    require_once ROOT_DIR . 'src/Controller/Admin/AdminController.php';
    $adminFilmController = new AdminController();
}



if ($action === 'home') {
    require ROOT_DIR . 'views/layout/header.php';
    require ROOT_DIR . 'views/home.php';
    require ROOT_DIR . 'views/layout/footer.php';

    //Ajout de la gestion de l'action 'films_list'

} else if ($action === 'films_list') {

    require ROOT_DIR . 'views/layout/header.php';
    require ROOT_DIR . 'views/films.php';
    require ROOT_DIR . 'views/layout/footer.php';


}
else if ($action === 'vote_page') {
    // Ici, vous pourriez instancier un FilmController pour charger les données
    // require_once ROOT_DIR . 'src/Controller/FilmController.php';
    // $filmController = new FilmController();
    // $data = $filmController->getFilms();
    require ROOT_DIR . 'views/layout/header.php';
    require ROOT_DIR . 'views/vote.php';
    require ROOT_DIR . 'views/layout/footer.php';
}

else if ($action === 'resultat_page') {
    // Ici, vous pourriez instancier un FilmController pour charger les données
    // require_once ROOT_DIR . 'src/Controller/FilmController.php';
    // $filmController = new FilmController();
    // $data = $filmController->getFilms();

    require ROOT_DIR . 'views/layout/header.php';
    require ROOT_DIR . 'views/resultat.php';
    require ROOT_DIR . 'views/layout/footer.php';
}

else if ($action === 'forum_page') {
    // Ici, vous pourriez instancier un FilmController pour charger les données
    // require_once ROOT_DIR . 'src/Controller/FilmController.php';
    // $filmController = new FilmController();
    // $data = $filmController->getFilms();

    require ROOT_DIR . 'views/layout/header.php';
    require ROOT_DIR . 'views/forum.php';
    require ROOT_DIR . 'views/layout/footer.php';
}

else if ($action === 'contact_page') {

    require ROOT_DIR . 'views/layout/header.php';
    require ROOT_DIR . 'views/contact.php';
    require ROOT_DIR . 'views/layout/footer.php';
}

else if ($action === 'inscription') {

    require ROOT_DIR . 'views/layout/header.php';
    require ROOT_DIR . 'views/inscription.php';
    require ROOT_DIR . 'views/layout/footer.php';
}

 else if ($action === 'login' || $action === 'logout') {

    // Le LoginController est instancié ici
    $loginController = new LoginController();
    $loginController->handleRequest($action);
}


else if (strpos($action, 'admin_') === 0) {
    // --- PARTIE ADMINISTRATION ---

    // Le Contrôleur de Sécurité gère la restriction d'accès
    if ($action === 'admin_list_films') {
        $adminFilmController->listFilms();
    } else if ($action === 'admin_add_film' || $action === 'admin_edit_film' || $action === 'admin_handle_film_form') {
        $adminFilmController->handleFilmForm();
    } else if ($action === 'admin_delete_film') {
        $adminFilmController->deleteFilm();
    } else if ($action === 'admin_dashboard') {
        SecurityController::restrictAccess();
        require ROOT_DIR . 'views/layout/header.php';
        require ROOT_DIR . 'views/admin/dashboard.php';
        require ROOT_DIR . 'views/layout/footer.php';
    } else {
        header("HTTP/1.0 404 Not Found");
        echo "404 Page d'administration non trouvée.";
    }

} else {
    header("HTTP/1.0 404 Not Found");
    echo "404 Page non trouvée.";
}
?>