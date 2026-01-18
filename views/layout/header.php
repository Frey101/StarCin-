<?php

$is_logged_in = isset($_SESSION['utilisateur_connecte']) && $_SESSION['utilisateur_connecte'] === true;
$is_admin = $is_logged_in && ($_SESSION['user_role'] ?? 'user') === 'admin';
$is_realisateur = $is_logged_in && !$is_admin && (new \RealisateurModel())->isRealisateur($_SESSION['user_id'] ?? 0);

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
    <link rel="stylesheet" href="<?php echo ROOT_PATH; ?>public/inscription.css" />
    <link rel="stylesheet" href="<?php echo ROOT_PATH; ?>public/connexion.css" />
    <link rel="stylesheet" href="<?php echo ROOT_PATH; ?>public/condigene.css" />
    <link rel="stylesheet" href="<?php echo ROOT_PATH; ?>public/mentions.css" />
    <link rel="stylesheet" href="<?php echo ROOT_PATH; ?>public/cgu.css" />
    <link rel="stylesheet" href="<?php echo ROOT_PATH; ?>public/admin.css" />
    <?php if (strpos($_GET['action'] ?? '', 'admin') === 0): ?>
    <link rel="stylesheet" href="<?php echo ROOT_PATH; ?>public/admin.css" />
    <?php endif; ?>

    <meta name="viewport" content="width=device-width, initial-scale=1.0" />


    <title>StarCiné</title>
</head>

<body class="index">
<header>
    <nav>
        <ul>
            <li><img src="<?php echo ROOT_PATH; ?>public/image/logo.png" alt="logo"></li>

            <li><a href="<?php echo ROOT_PATH; ?>index.php?action=home">Accueil</a></li>

            <li><a href="<?php echo ROOT_PATH; ?>index.php?action=films_list">Film</a></li>
            <li><a href="<?php echo ROOT_PATH; ?>index.php?action=contact_page">Contact</a></li>

            <?php if ($is_logged_in): ?>

                <?php if (!$is_realisateur): ?>
                    <li><a href="<?php echo ROOT_PATH; ?>index.php?action=vote_page">Vote</a></li>
                    <li><a href="<?php echo ROOT_PATH; ?>index.php?action=resultat_page">Resultat</a></li>
                    <li><a href="<?php echo ROOT_PATH; ?>index.php?action=forum_page">Forum</a></li>
                <?php else: ?>
                    <li><a href="<?php echo ROOT_PATH; ?>index.php?action=proposition_form">Proposer un Film</a></li>
                <?php endif; ?>

                <?php if ($is_admin): ?>
                    <li style="font-weight: bold;"><a href="<?php echo ROOT_PATH; ?>index.php?action=admin_dashboard">admin</a></li>
                <?php endif; ?>

                <li><a href="<?php echo ROOT_PATH; ?>index.php?action=logout">Déconnexion</a></li>


            <?php else: ?>

                <li><a href="<?php echo ROOT_PATH; ?>index.php?action=login">Connexion</a></li>
                <li><a href="<?php echo ROOT_PATH; ?>index.php?action=inscription">Inscription</a></li>

            <?php endif; ?>
        </ul>
    </nav>
</header>

