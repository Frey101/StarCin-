<?php
// Variables passées par le contrôleur
$sessionsWithFilms = $sessionsWithFilms ?? [];
$error = $error ?? $_GET['error'] ?? null;
$status = $_GET['status'] ?? null;
$isAdmin = isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
?>

<h1 class="page-title">Vote : Choisissez votre film préféré !</h1>

<?php if ($isAdmin): ?>
    <div class="alert alert-warning" style="max-width: 800px; margin: 20px auto; padding: 15px; background: rgba(255, 193, 7, 0.2); border: 2px solid #ffc107; border-radius: 8px; color: white;">
        ⚠️ Les administrateurs ne peuvent pas voter.
    </div>
<?php endif; ?>

<?php if ($error): ?>
    <div class="alert alert-error" style="max-width: 800px; margin: 20px auto; padding: 15px; background: rgba(220, 38, 38, 0.2); border: 2px solid #dc2626; border-radius: 8px; color: white;">
        <?php echo htmlspecialchars($error); ?>
    </div>
<?php endif; ?>

<?php if ($status === 'vote_success'): ?>
    <div class="alert alert-success" style="max-width: 800px; margin: 20px auto; padding: 15px; background: rgba(34, 197, 94, 0.2); border: 2px solid #22c55e; border-radius: 8px; color: white;">
        ✔️ Votre vote a été enregistré avec succès !
    </div>
<?php endif; ?>

<?php if (empty($sessionsWithFilms)): ?>
    <div class="no-sessions" style="text-align: center; padding: 60px 20px; color: white;">
        <p style="font-size: 1.2em; margin-bottom: 20px;">Aucune session de vote active pour le moment.</p>
        <p>Revenez plus tard pour voter sur les films proposés.</p>
    </div>
<?php else: ?>
    <?php foreach ($sessionsWithFilms as $sessionData): 
        $session = $sessionData['session'];
        $films = $sessionData['films'];
        $dateDebut = date('d/m/Y', strtotime($session->getDateDebut()));
        $dateFin = date('d/m/Y', strtotime($session->getDateFin()));
    ?>
        <div class="vote-session" data-session-id="<?php echo $session->getIdSessionvote(); ?>" style="max-width: 1000px; margin: 30px auto; padding: 30px; background: rgba(0, 0, 0, 0.6); border-radius: 15px; box-shadow: 0px 0px 20px 13px rgb(189 0 0);">
            <div class="session-header" style="margin-bottom: 25px; padding-bottom: 15px; border-bottom: 2px solid #b61f0a;">
                <h2 style="color: #FFB042; margin: 0 0 10px 0;">Session de vote <?php echo $session->getAnnee(); ?></h2>
                <p style="color: rgba(255, 255, 255, 0.8); margin: 0;">
                    Du <?php echo $dateDebut; ?> au <?php echo $dateFin; ?> 
                    (<?php echo $session->getDuree(); ?> jours)
                </p>
            </div>

            <?php if (empty($films)): ?>
                <p style="color: white; text-align: center; padding: 20px;">
                    Aucun film dans cette session pour le moment.
                </p>
            <?php else: ?>
                <div class="films-vote-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 20px; margin-top: 20px;">
                    <?php foreach ($films as $film): 
                        $imagePath = ROOT_PATH . 'public/image/film_' . $film['id_film'] . '.jpg';
                        $extensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                        $foundImage = false;
                        foreach ($extensions as $ext) {
                            $testPath = ROOT_DIR . 'public/image/film_' . $film['id_film'] . '.' . $ext;
                            if (file_exists($testPath)) {
                                $imagePath = ROOT_PATH . 'public/image/film_' . $film['id_film'] . '.' . $ext;
                                $foundImage = true;
                                break;
                            }
                        }
                        if (!$foundImage) {
                            $imagePath = ROOT_PATH . 'public/image/Ineception.jpg';
                        }
                    ?>
                        <div class="film-vote-card" 
                             data-film-id="<?php echo $film['id_film']; ?>"
                             style="background: white; border-radius: 12px; overflow: hidden; cursor: pointer; transition: all 0.3s ease; box-shadow: 0 4px 15px rgba(0,0,0,0.3);"
                             onclick="selectFilm(<?php echo $film['id_film']; ?>, '<?php echo htmlspecialchars($film['titre']); ?>')">
                            <img src="<?php echo htmlspecialchars($imagePath); ?>" 
                                 alt="<?php echo htmlspecialchars($film['titre']); ?>"
                                 style="width: 100%; height: 250px; object-fit: cover;">
                            <div style="padding: 15px;">
                                <h3 style="margin: 0 0 10px 0; color: #000; font-size: 1.1em;">
                                    <?php echo htmlspecialchars($film['titre']); ?>
                                </h3>
                                <div class="rating-stars" style="display: flex; gap: 5px; justify-content: center;">
                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                        <span class="star" data-rating="<?php echo $i; ?>" 
                                              style="font-size: 24px; cursor: pointer; color: #ddd;"
                                              onclick="event.stopPropagation(); setRating(<?php echo $film['id_film']; ?>, <?php echo $i; ?>);">
                                            ⭐
                                        </span>
                                    <?php endfor; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <?php 
                $hasVoted = $sessionData['hasVoted'] ?? false;
                if (!$isAdmin): 
                    if ($hasVoted):
                ?>
                    <div style="margin-top: 30px; text-align: center; padding: 20px; background: rgba(34, 197, 94, 0.2); border: 2px solid #22c55e; border-radius: 8px;">
                        <p style="color: white; font-size: 1.1em; margin: 0;">
                            ✅ Vous avez déjà voté dans cette session de vote.
                        </p>
                    </div>
                <?php else: ?>
                    <form id="voteForm_<?php echo $session->getIdSessionvote(); ?>" class="vote-form" method="POST" action="<?php echo ROOT_PATH; ?>index.php?action=submit_vote" style="margin-top: 30px; text-align: center;">
                        <input type="hidden" class="selected-film-id" name="film_id" value="">
                        <input type="hidden" class="selected-rating" name="note" value="">
                        <input type="hidden" name="session_id" value="<?php echo $session->getIdSessionvote(); ?>">
                        <button type="submit" class="vote-btn" 
                                style="padding: 15px 40px; background: #b61f0a; color: white; border: none; border-radius: 8px; font-size: 1.1em; font-weight: 600; cursor: pointer; opacity: 0.5; transition: all 0.3s ease;"
                                disabled>
                            Valider mon vote
                        </button>
                    </form>
                <?php endif; endif; ?>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
<?php endif; ?>

<script>
// Stocker les sélections par session
const sessionSelections = {};

function selectFilm(filmId, filmTitle) {
    // Trouver la session parente
    const card = document.querySelector(`[data-film-id="${filmId}"]`);
    if (!card) return;
    
    const sessionCard = card.closest('.vote-session');
    if (!sessionCard) return;
    
    const sessionId = sessionCard.dataset.sessionId || 'default';
    
    // Initialiser la session si nécessaire
    if (!sessionSelections[sessionId]) {
        sessionSelections[sessionId] = { filmId: null, rating: 0 };
    }
    
    sessionSelections[sessionId].filmId = filmId;
    
    // Réinitialiser la sélection visuelle dans cette session
    sessionCard.querySelectorAll('.film-vote-card').forEach(c => {
        c.style.border = 'none';
        c.style.transform = 'scale(1)';
    });
    
    // Mettre en évidence la carte sélectionnée
    card.style.border = '3px solid #FFB042';
    card.style.transform = 'scale(1.05)';
    
    // Mettre à jour le champ hidden du formulaire de cette session
    const form = sessionCard.querySelector('.vote-form');
    if (form) {
        const filmInput = form.querySelector('.selected-film-id');
        if (filmInput) {
            filmInput.value = filmId;
        }
    }
    
    // Activer le bouton si une note est sélectionnée
    updateVoteButton(sessionId);
}

function setRating(filmId, rating) {
    // Trouver la session parente
    const card = document.querySelector(`[data-film-id="${filmId}"]`);
    if (!card) return;
    
    const sessionCard = card.closest('.vote-session');
    if (!sessionCard) return;
    
    const sessionId = sessionCard.dataset.sessionId || 'default';
    
    // Initialiser la session si nécessaire
    if (!sessionSelections[sessionId]) {
        sessionSelections[sessionId] = { filmId: null, rating: 0 };
    }
    
    // Si le film n'est pas sélectionné, le sélectionner
    if (sessionSelections[sessionId].filmId !== filmId) {
        selectFilm(filmId, '');
    }
    
    sessionSelections[sessionId].rating = rating;
    
    // Mettre à jour le champ hidden du formulaire
    const form = sessionCard.querySelector('.vote-form');
    if (form) {
        const ratingInput = form.querySelector('.selected-rating');
        if (ratingInput) {
            ratingInput.value = rating;
        }
    }
    
    // Mettre à jour l'affichage des étoiles
    const stars = card.querySelectorAll('.star');
    stars.forEach((star, index) => {
        if (index < rating) {
            star.style.color = '#FFB042';
        } else {
            star.style.color = '#ddd';
        }
    });
    
    updateVoteButton(sessionId);
}

function updateVoteButton(sessionId) {
    const sessionCard = document.querySelector(`[data-session-id="${sessionId}"]`);
    if (!sessionCard) return;
    
    const voteBtn = sessionCard.querySelector('.vote-btn');
    const selection = sessionSelections[sessionId];
    
    if (voteBtn && selection && selection.filmId && selection.rating > 0) {
        voteBtn.disabled = false;
        voteBtn.style.opacity = '1';
        voteBtn.style.cursor = 'pointer';
        voteBtn.style.background = '#b61f0a';
    } else if (voteBtn) {
        voteBtn.disabled = true;
        voteBtn.style.opacity = '0.5';
        voteBtn.style.cursor = 'not-allowed';
    }
}

// Empêcher la soumission si les champs ne sont pas remplis
document.querySelectorAll('.vote-form').forEach(form => {
    form.addEventListener('submit', function(e) {
        const sessionCard = this.closest('.vote-session');
        const sessionId = sessionCard?.dataset.sessionId || 'default';
        const selection = sessionSelections[sessionId];
        
        if (!selection || !selection.filmId || !selection.rating) {
            e.preventDefault();
            alert('Veuillez sélectionner un film et lui attribuer une note.');
            return false;
        }
        
        // Vérifier que les champs hidden sont bien remplis
        const filmInput = this.querySelector('.selected-film-id');
        const ratingInput = this.querySelector('.selected-rating');
        
        if (!filmInput || !filmInput.value || !ratingInput || !ratingInput.value) {
            e.preventDefault();
            alert('Erreur : veuillez réessayer.');
            return false;
        }
    });
});
</script>
