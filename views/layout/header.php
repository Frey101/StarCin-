<?php
// views/layout/header.php

// Note : Assurez-vous que session_start() est appelé dans index.php et que ROOT_PATH est défini.

$is_logged_in = isset($_SESSION['utilisateur_connecte']) && $_SESSION['utilisateur_connecte'] === true;
$is_admin = $is_logged_in && ($_SESSION['user_role'] ?? 'user') === 'admin';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />

    <link rel="stylesheet" href="<?php echo ROOT_PATH; ?>public/pa.css" />
    <link rel="stylesheet" href="<?php echo ROOT_PATH; ?>public/vote.css" />
    <link rel="stylesheet" href="<?php echo ROOT_PATH; ?>public/resultat.css" />
    <link rel="stylesheet" href="<?php echo ROOT_PATH; ?>public/forum.css" />

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

            <?php if ($is_logged_in): ?>

                <li><a href="<?php echo ROOT_PATH; ?>index.php?action=vote_page">Vote</a></li>
                <li><a href="<?php echo ROOT_PATH; ?>index.php?action=resultat_page">Resultat</a></li>
                <li><a href="<?php echo ROOT_PATH; ?>index.php?action=forum_page">Forum</a></li>

                <?php if ($is_admin): ?>
                    <li style="font-weight: bold;"><a href="<?php echo ROOT_PATH; ?>index.php?action=admin_dashboard">ADMIN</a></li>
                <?php endif; ?>

                <li><a href="<?php echo ROOT_PATH; ?>index.php?action=logout">Déconnexion</a></li>

                <?php if (isset($_SESSION['email'])): ?>
                    <li><span style="color: white; padding: 0 10px;">Bonjour, <?= htmlspecialchars($_SESSION['email']) ?></span></li>
                <?php endif; ?>

            <?php else: ?>

                <li><a href="<?php echo ROOT_PATH; ?>index.php?action=login">Connexion</a></li>
                <li><a href="<?php echo ROOT_PATH; ?>index.php?action=inscription">Inscription</a></li>

            <?php endif; ?>
        </ul>
    </nav>
</header>