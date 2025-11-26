<?php
// C'EST CETTE LIGNE QUI MANQUE ET QUI EST NÉCESSAIRE
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="pa.css" />
    <title>StarCiné</title>
</head>

<body class="index">
<nav>
    <ul>
        <li><a href="index.php">Accueil</a></li>
        <?php
        // Vérifie si la session 'utilisateur_connecte' est définie et vraie
        if (isset($_SESSION['utilisateur_connecte']) && $_SESSION['utilisateur_connecte'] === true) {
            // L'utilisateur est connecté : affiche le lien de DÉCONNEXION
            echo '<li><a href="films.php">Film</a></li>';
            echo '<li><a href="vote.php">Vote</a></li>';
            echo '<li><a href="resultat.php">Resultat</a></li>';
            echo '<li><a href="forum.php">Forum</a></li>';

            echo '<li><a href="deconnexion.php">Déconnexion</a></li>';

            // OPTIONNEL : Afficher l'email de l'utilisateur
            // if (isset($_SESSION['email'])) {
            //     echo '<li><span style="color: white;">Bonjour, ' . htmlspecialchars($_SESSION['email']) . '</span></li>';
            // }

        } else {
            // L'utilisateur N'EST PAS connecté : affiche les liens de CONNEXION/INSCRIPTION
            echo '<li><a href="films.php">Film</a></li>';
            echo '<li><a href="connexion.php">Connexion</a></li>';
            echo '<li><a href="inscription.php">Inscription</a></li>';

        }
        ?>
    </ul>
</nav>
</body>
</html>

