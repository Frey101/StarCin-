
<?php
// On inclut le fichier d'en-tête (header)
require 'header.php';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Vote - StarCiné</title>

    <style>
        body {
            font-family: "Segoe UI", sans-serif;
            background: #f0f2f5;
            padding: 40px;
        }

        h1 {
            text-align: center;
            margin-bottom: 25px;
        }

        .container {
            width: 60%;
            margin: auto;
            background: white;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }

        .film {
            border: 2px solid transparent;
            background: #fafafa;
            padding: 18px;
            margin: 12px 0;
            border-radius: 12px;
            cursor: pointer;
            transition: 0.25s;
        }

        .film:hover {
            background: #e8f1ff;
        }

        .film.selected {
            border-color: #0078ff;
            background: #e5f0ff;
        }

        .film-title {
            font-size: 18px;
            font-weight: bold;
        }

        .film-desc {
            font-size: 14px;
            color: #555;
            margin-top: 5px;
        }

        #voteBtn {
            width: 100%;
            margin-top: 25px;
            padding: 15px;
            font-size: 18px;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            background: #0078ff;
            color: white;
            transition: 0.25s;
            opacity: 0.5;
        }

        #voteBtn.enabled {
            opacity: 1;
        }

        #voteBtn:hover.enabled {
            background: #005fcc;
        }

        #confirmation {
            margin-top: 20px;
            background: #d4f8d4;
            color: #1a7d1a;
            padding: 15px;
            border-radius: 10px;
            display: none;
            text-align: center;
            font-weight: bold;
        }
    </style>
</head>

<body>

<h1>Vote : Choisissez votre film préféré 🎬</h1>

<div class="container">

    <div class="film" data-film="Lumière d’Hiver">
        <div class="film-title">Lumière d’Hiver</div>
        <div class="film-desc">Drame — 1h52 — Une histoire touchante sur la reconstruction.</div>
    </div>

    <div class="film" data-film="Le Dernier Signal">
        <div class="film-title">Le Dernier Signal</div>
        <div class="film-desc">Thriller — 1h40 — Un mystère captivant autour d’une station abandonnée.</div>
    </div>

    <div class="film" data-film="Vent du Nord">
        <div class="film-title">Vent du Nord</div>
        <div class="film-desc">Aventure — 1h48 — Un voyage initiatique en pleine nature.</div>
    </div>

    <button id="voteBtn">Valider mon vote</button>

    <div id="confirmation"></div>

</div>


<script>
    let selectedFilm = null;

    const films = document.querySelectorAll(".film");
    const voteBtn = document.getElementById("voteBtn");
    const confirmationBox = document.getElementById("confirmation");

    films.forEach(film => {
        film.addEventListener("click", () => {

            films.forEach(f => f.classList.remove("selected"));
            film.classList.add("selected");

            selectedFilm = film.dataset.film;

            voteBtn.classList.add("enabled");
        });
    });

    voteBtn.addEventListener("click", () => {
        if (!selectedFilm) return;

        confirmationBox.style.display = "block";
        confirmationBox.innerHTML = "✔️ Votre vote pour <strong>" + selectedFilm + "</strong> a bien été enregistré !";

        voteBtn.classList.remove("enabled");

        films.forEach(f => f.classList.remove("selected"));
        selectedFilm = null;
    });
</script>

</body>
</html>

<?php
    // On inclut le fichier de pied de page (footer)
    require 'footer.php';
    ?>