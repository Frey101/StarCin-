<?php

$is_logged_in = isset($_SESSION['utilisateur_connecte']) && $_SESSION['utilisateur_connecte'] === true;
$is_admin = $is_logged_in && ($_SESSION['user_role'] ?? 'user') === 'admin';

// CSRF token
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />

    <link rel="stylesheet" href="<?php echo ROOT_PATH; ?>public/pa.css" />
    <link rel="stylesheet" href="<?php echo ROOT_PATH; ?>public/vote.css" />
    <link rel="stylesheet" href="<?php echo ROOT_PATH; ?>public/resultat.css" />
    <link rel="stylesheet" href="<?php echo ROOT_PATH; ?>public/forum.css" />
    <link rel="stylesheet" href="<?php echo ROOT_PATH; ?>public/contact.css" />
    <link rel="stylesheet" href="<?php echo ROOT_PATH; ?>public/connexion.css" />

    <meta name="viewport" content="width=device-width, initial-scale=1.0" />


    <title>StarCiné</title>
</head>

<body class="index">
<header>
    <nav>
        <ul>
            <img src="<?php echo ROOT_PATH; ?>public/image/logo.png" alt="logo">

            <li><a href="<?php echo ROOT_PATH; ?>index.php?action=home">Accueil</a></li>

            <li><a href="<?php echo ROOT_PATH; ?>index.php?action=films_list">Film</a></li>
            <li><a href="<?php echo ROOT_PATH; ?>index.php?action=contact_page">Contact</a></li>

            <?php if ($is_logged_in): ?>

                <li><a href="<?php echo ROOT_PATH; ?>index.php?action=vote_page">Vote</a></li>
                <li><a href="<?php echo ROOT_PATH; ?>index.php?action=resultat_page">Resultat</a></li>
                <li><a href="<?php echo ROOT_PATH; ?>index.php?action=forum_page">Forum</a></li>


                <?php if ($is_admin): ?>
                    <li style="font-weight: bold;"><a href="<?php echo ROOT_PATH; ?>index.php?action=admin_dashboard">ADMIN</a></li>
                <?php endif; ?>

                <li><a href="<?php echo ROOT_PATH; ?>index.php?action=logout">Déconnexion</a></li>


            <?php else: ?>

                <li><a href="<?php echo ROOT_PATH; ?>index.php?action=login">Connexion</a></li>
                <li><a href="<?php echo ROOT_PATH; ?>index.php?action=inscription">Inscription</a></li>

            <?php endif; ?>
        </ul>
    </nav>
</header>