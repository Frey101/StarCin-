<?php
// index.php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 1. DÉFINITION DES CHEMINS GLOBAUX
if (!defined('ROOT_DIR')) {
    define('ROOT_DIR', __DIR__ . '/');
}

if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', '/StarCin--main/');
}

// 2. Inclusion des classes nécessaires
require_once ROOT_DIR . 'src/Database/DBConnection.php';
require_once ROOT_DIR . 'src/Controller/SecurityController.php';
require_once ROOT_DIR . 'src/Controller/LoginController.php';
require_once ROOT_DIR . 'src/Controller/Admin/AdminFilmController.php';
require_once ROOT_DIR . 'src/Entity/User.php';
require_once ROOT_DIR . 'src/Entity/Film.php';

// 3. Définir l'action demandée
$action = $_GET['action'] ?? 'home';
$adminFilmController = new AdminFilmController();

// 4. LOGIQUE DE ROUTAGE
if ($action === 'home') {
    require ROOT_DIR . 'views/layout/header.php';
    require ROOT_DIR . 'views/home.php';
    require ROOT_DIR . 'views/layout/footer.php';

} else if ($action === 'films_list') {
    require ROOT_DIR . 'views/layout/header.php';
    require ROOT_DIR . 'views/films.php';
    require ROOT_DIR . 'views/layout/footer.php';

} else if ($action === 'vote_page') {
    require ROOT_DIR . 'views/layout/header.php';
    require ROOT_DIR . 'views/vote.php';
    require ROOT_DIR . 'views/layout/footer.php';

} else if ($action === 'resultat_page') {
    require ROOT_DIR . 'views/layout/header.php';
    require ROOT_DIR . 'views/resultat.php';
    require ROOT_DIR . 'views/layout/footer.php';

} else if ($action === 'forum_page') {
    require ROOT_DIR . 'views/layout/header.php';
    require ROOT_DIR . 'views/forum.php';
    require ROOT_DIR . 'views/layout/footer.php';

} else if ($action === 'login' || $action === 'logout') {
    $loginController = new LoginController();
    $loginController->handleRequest($action);

} else if ($action === 'inscription') {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        require_once ROOT_DIR . 'src/Model/inscriptionModel.php';
    }
    require ROOT_DIR . 'views/layout/header.php';
    require ROOT_DIR . 'views/inscription.php';
    require ROOT_DIR . 'views/layout/footer.php';

} else if (strpos($action, 'admin_') === 0) {
    SecurityController::restrictAccess();

    if ($action === 'admin_list_films') {
        $adminFilmController->listFilms();
    } else if ($action === 'admin_add_film' || $action === 'admin_edit_film' || $action === 'admin_handle_film_form') {
        $adminFilmController->handleFilmForm();
    } else if ($action === 'admin_delete_film') {
        $adminFilmController->deleteFilm();
    } else if ($action === 'admin_dashboard') {
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
