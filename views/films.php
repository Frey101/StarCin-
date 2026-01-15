<?php
// Récupérer tous les films depuis la base de données
require_once ROOT_DIR . 'src/Model/FilmModel.php';
require_once ROOT_DIR . 'src/Repository/AdminRepository.php';

$filmModel = new FilmModel();
$adminRepository = new AdminRepository();
$films = $adminRepository->getFilmsWithRatings();
?>

<h1 class="page-title">Tous les films</h1>

<div class="films-grid">
    <?php if (empty($films)): ?>
        <p style="grid-column: 1 / -1; text-align: center; color: white; padding: 40px;">
            Aucun film disponible pour le moment.
        </p>
    <?php else: ?>
        <?php foreach ($films as $film): ?>
            <div class="film-card" onclick="openFilmModal(<?php echo htmlspecialchars(json_encode($film)); ?>)">
                <?php if (!empty($film['image'])): ?>
                    <img src="<?php echo htmlspecialchars($film['image']); ?>" 
                         alt="<?php echo htmlspecialchars($film['titre']); ?>"
                         onerror="this.src='<?php echo ROOT_PATH; ?>public/image/Ineception.jpg'">
                <?php else: ?>
                    <img src="<?php echo ROOT_PATH; ?>public/image/Ineception.jpg" 
                         alt="<?php echo htmlspecialchars($film['titre']); ?>">
                <?php endif; ?>
                <h3><?php echo htmlspecialchars($film['titre']); ?></h3>
                <p>
                    <?php if (isset($film['note_moyenne']) && $film['note_moyenne'] != null): ?>
                        ⭐ <?php echo number_format($film['note_moyenne'], 1); ?> / 5
                    <?php else: ?>
                        ⭐ Pas encore noté
                    <?php endif; ?>
                </p>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<!-- Modal pour afficher les détails du film -->
<div id="filmModal" class="film-modal">
    <div class="film-modal-content">
        <span class="film-modal-close">&times;</span>
        <div class="film-modal-header">
            <h2 id="modalFilmTitle"></h2>
            <div class="film-modal-meta">
                <span id="modalFilmCategory"></span>
                <span id="modalFilmYear"></span>
                <span id="modalFilmRating"></span>
            </div>
        </div>
        <div class="film-modal-body">
            <div class="film-modal-image">
                <img id="modalFilmImage" src="" alt="">
            </div>
            <div class="film-modal-info">
                <h3>Synopsis</h3>
                <p id="modalFilmSynopsis"></p>
            </div>
        </div>
    </div>
</div>

<script>
function openFilmModal(film) {
    const modal = document.getElementById('filmModal');
    const title = document.getElementById('modalFilmTitle');
    const category = document.getElementById('modalFilmCategory');
    const year = document.getElementById('modalFilmYear');
    const rating = document.getElementById('modalFilmRating');
    const image = document.getElementById('modalFilmImage');
    const synopsis = document.getElementById('modalFilmSynopsis');
    
    // Remplir les informations
    title.textContent = film.titre || 'Titre non disponible';
    category.textContent = film.categorie ? `📁 ${film.categorie}` : '';
    year.textContent = film.annee ? `📅 ${film.annee}` : '';
    rating.textContent = film.note_moyenne ? `⭐ ${parseFloat(film.note_moyenne).toFixed(1)} / 5` : '⭐ Pas encore noté';
    
    image.src = film.image || '<?php echo ROOT_PATH; ?>public/image/Ineception.jpg';
    image.alt = film.titre || 'Image du film';
    
    synopsis.textContent = film.synopsis || 'Aucun synopsis disponible.';
    
    modal.style.display = 'block';
}

// Fermer la modal
document.querySelector('.film-modal-close').addEventListener('click', function() {
    document.getElementById('filmModal').style.display = 'none';
});

window.onclick = function(event) {
    const modal = document.getElementById('filmModal');
    if (event.target === modal) {
        modal.style.display = 'none';
    }
}
</script>
