<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../public/vote.css">
    <title>Votes</title>
</head>



<body>

<h1>Vote : Choisissez votre film préféré ! </h1>

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

