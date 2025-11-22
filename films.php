<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="pa.css" />
    <title>Document</title>
</head>

<body class="films">

<?php
// On inclut le fichier d'en-tête (header)
require 'header.php';
?>

<h1 class="page-title">Tous les films</h1>

<div class="films-grid">

    <!-- Film 1 -->
    <a href="film.html" class="film-card">
        <img src="image/idiana.jpeg" alt="Inception">
        <h3>Inception</h3>
        <p>⭐ 4.5 / 5</p>
    </a>

    <!-- Film 2 -->
    <a href="film.html" class="film-card">
        <img src="image/idiana.jpeg" alt="Interstellar">
        <h3>Interstellar</h3>
        <p>⭐ 4.7 / 5</p>
    </a>

    <!-- Film 3 -->
    <a href="film.html" class="film-card">
        <img src="image/indiana.jpeg" alt="Tenet">
        <h3>Tenet</h3>
        <p>⭐ 4.1 / 5</p>
    </a>

    <!-- Ajoute autant de films que tu veux -->
</div>

<?php
// On inclut le fichier de pied de page (footer)
require 'footer.php';
?>
</body>

</html>