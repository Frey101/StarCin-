<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}



// ROOT_DIR : Chemin ABSOLU  pour les inclusions (require/include)
// __DIR__ est le dossier où se trouve index.php
if (!defined('ROOT_DIR')) {
    define('ROOT_DIR', __DIR__ . '/');
}

// ROOT_PATH : Chemin URL pour les liens et les redirections header
if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', '/StarCin--main/');
}


// Inclusion des classes nécessaires
require_once ROOT_DIR . 'src/Database/DBConnection.php';
require_once ROOT_DIR . 'src/Controller/SecurityController.php';
require_once ROOT_DIR . 'src/Controller/LoginController.php';
require_once ROOT_DIR . 'src/Controller/InscriptionController.php';
require_once ROOT_DIR . 'src/Controller/Admin/AdminController.php';
require_once ROOT_DIR . 'src/Controller/Admin/AdminFilmController.php';
require_once ROOT_DIR . 'src/Controller/Admin/AdminDashboardController.php';
require_once ROOT_DIR . 'src/Entity/User.php';
require_once ROOT_DIR . 'src/Entity/Film.php';


$action = $_GET['action'] ?? 'home';

$adminController = null;
$adminFilmController = null;
$adminDashboardController = null;

if (strpos($action, 'admin_') === 0) {
    if ($action === 'admin_dashboard') {
        $adminDashboardController = new AdminDashboardController();
    } elseif (in_array($action, ['admin_list_films', 'admin_add_film', 'admin_edit_film', 'admin_handle_film_form', 'admin_delete_film'])) {
        $adminFilmController = new AdminFilmController();
    } else {
        $adminController = new AdminController();
    }
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

    require ROOT_DIR . 'views/layout/header.php';
    require ROOT_DIR . 'views/resultat.php';
    require ROOT_DIR . 'views/layout/footer.php';
}

else if ($action === 'forum_page') {

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

    $inscriptionController = new InscriptionController();
    $inscriptionController->handleRequest();
}

 else if ($action === 'login' || $action === 'logout') {

    // Le LoginController est instancié ici
    $loginController = new LoginController();
    $loginController->handleRequest($action);
}


else if (strpos($action, 'admin_') === 0) {
    //  PARTIE ADMINISTRATION

    if ($action === 'admin_dashboard') {
        $adminDashboardController->dashboard();
    } elseif ($action === 'admin_list_films') {
        $adminFilmController->listFilms();
    } elseif ($action === 'admin_add_film' || $action === 'admin_edit_film' || $action === 'admin_handle_film_form') {
        $adminFilmController->handleFilmForm();
    } elseif ($action === 'admin_delete_film') {
        $adminFilmController->deleteFilm();
    } else {
        // Other admin actions like deleteFilm, promoteUser, etc.
        // For now, assume they are handled by AdminController, but since not implemented, 404
        header("HTTP/1.0 404 Not Found");
        echo "404 Page d'administration non trouvée.";
    }

} else {
    header("HTTP/1.0 404 Not Found");
    echo "404 Page non trouvée.";
}
?>