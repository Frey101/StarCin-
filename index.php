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
require_once ROOT_DIR . 'src/Controller/Admin/AdminFilmController.php';
require_once ROOT_DIR . 'src/Controller/Admin/AdminDashboardController.php';
require_once ROOT_DIR . 'src/Controller/Admin/AdminUserController.php';
require_once ROOT_DIR . 'src/Controller/Admin/AdminPropositionController.php';
require_once ROOT_DIR . 'src/Controller/Admin/AdminSessionController.php';
require_once ROOT_DIR . 'src/Controller/ResultatController.php';
require_once ROOT_DIR . 'src/Controller/VoteController.php';
require_once ROOT_DIR . 'src/Entity/SessionVote.php';
require_once ROOT_DIR . 'src/Entity/User.php';
require_once ROOT_DIR . 'src/Entity/Film.php';



$action = $_GET['action'] ?? 'home';

$adminFilmController = null;
$adminDashboardController = null;
$adminUserController = null;
$adminPropositionController = null;

if (strpos($action, 'admin_') === 0) {
    if ($action === 'admin_dashboard') {
        $adminDashboardController = new AdminDashboardController();
    } elseif (in_array($action, ['admin_list_films', 'admin_add_film', 'admin_edit_film', 'admin_handle_film_form', 'admin_delete_film'])) {
        $adminFilmController = new AdminFilmController();
    } elseif (in_array($action, ['admin_users', 'admin_promote_user', 'admin_delete_user'])) {
        $adminUserController = new AdminUserController();
    } elseif (in_array($action, ['admin_propositions', 'admin_accept_proposition', 'admin_refuse_proposition'])) {
        $adminPropositionController = new AdminPropositionController();
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


else if ($action === 'mentions') {
    require ROOT_DIR . 'views/layout/header.php';
    require ROOT_DIR . 'views/mentionsleg.php';
    require ROOT_DIR . 'views/layout/footer.php';

    }


else if ($action === 'condition') {

    require ROOT_DIR . 'views/layout/header.php';
    require ROOT_DIR . 'views/cgu.php';
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
else if ($action === 'vote_page' || $action === 'submit_vote') {
    $voteController = new VoteController();
    if ($action === 'submit_vote') {
        $voteController->submitVote();
    } else {
        $voteController->showVotePage();
    }
}


else if (strpos($action, 'admin_') === 0) {
    if ($action === 'admin_dashboard') {
        $adminDashboardController->dashboard();
    }
    elseif ($action === 'admin_list_films') {
        $adminFilmController->listFilms();
    } elseif ($action === 'admin_add_film' || $action === 'admin_edit_film' || $action === 'admin_handle_film_form') {
        $adminFilmController->handleFilmForm();
    }     elseif ($action === 'admin_delete_film') {
        $adminFilmController->deleteFilm();
    }
    elseif ($action === 'admin_users') {
        $adminUserController->listUsers();
    } elseif ($action === 'admin_promote_user') {
        $adminUserController->promoteUser();
    }     elseif ($action === 'admin_delete_user') {
        $adminUserController->deleteUser();
    }
    elseif ($action === 'admin_propositions') {
        $adminPropositionController->listPropositions();
    } elseif ($action === 'admin_accept_proposition') {
        $adminPropositionController->acceptProposition();
    }     elseif ($action === 'admin_refuse_proposition') {
        $adminPropositionController->refuseProposition();
    }
    elseif (in_array($action, ['admin_sessions', 'admin_create_session', 'admin_delete_session'])) {
        $adminSessionController = new AdminSessionController();
        if ($action === 'admin_sessions') {
            $adminSessionController->listSessions();
        } elseif ($action === 'admin_create_session') {
            $adminSessionController->createSession();
        } elseif ($action === 'admin_delete_session') {
            $adminSessionController->deleteSession();
        }
    }
    elseif ($action === 'admin_delete_comment') {
        $adminDashboardController->deleteComment();
    }
    else {
        header("HTTP/1.0 404 Not Found");
        echo "404 Page d'administration non trouvée.";
    }

} else {
    header("HTTP/1.0 404 Not Found");
    echo "404 Page non trouvée.";
}
?>