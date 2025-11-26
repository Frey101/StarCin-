<?php
// DOIT ÊTRE LA PREMIÈRE INSTRUCTION
session_start();

// 1. Démarrer/Ouvrir la session si elle existe.

// 2. Détruire TOUTES les variables de session
$_SESSION = array();

// 3. Si vous voulez détruire complètement la session, effacez également le cookie de session.
// Note : Cela détruira la session, et pas seulement les données de session !
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// 4. Finalement, détruire la session.
session_destroy();

// 5. Redirection vers la page d'accueil ou de connexion
header('Location: index.php');
exit;
?>