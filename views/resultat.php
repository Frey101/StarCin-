<?php
// Variables passées par le contrôleur
$resultsByYear = $resultsByYear ?? [];
$availableYears = $availableYears ?? [];
?>

<h1 class="page-title">Résultats du Vote</h1>

<?php if (empty($resultsByYear)): ?>
    <div class="no-results" style="text-align: center; padding: 60px 20px; color: white;">
        <p style="font-size: 1.2em; margin-bottom: 20px;">Aucun résultat disponible pour le moment.</p>
        <p>Les résultats seront affichés après la fin des sessions de vote.</p>
    </div>
<?php else: ?>
    <?php foreach ($resultsByYear as $year => $results): ?>
        <div class="year-block" style="max-width: 1000px; margin: 30px auto; padding: 30px; background: rgba(0, 0, 0, 0.6); border-radius: 15px; box-shadow: 0px 0px 20px 13px rgb(189 0 0);">
            <h2 style="color: #FFB042; margin: 0 0 25px 0; font-size: 2em; text-align: center; border-bottom: 3px solid #b61f0a; padding-bottom: 15px;">
                Résultats <?php echo $year; ?>
            </h2>

            <?php if (empty($results)): ?>
                <p style="color: white; text-align: center; padding: 20px;">
                    Aucun résultat disponible pour cette année.
                </p>
            <?php else: ?>
                <?php 
                $position = 1;
                foreach ($results as $result): 
                    $film = $result['film'];
                    $nbVotes = $result['nb_votes'];
                    $pourcentage = $result['pourcentage'];
                    $noteMoyenne = $result['note_moyenne'];
                    
                    // Déterminer l'image
                    $imagePath = $film['image'] ?? ROOT_PATH . 'public/image/Ineception.jpg';
                ?>
                    <div class="film-result" style="display: flex; align-items: center; background: rgba(255, 255, 255, 0.1); border-radius: 12px; padding: 20px; margin-bottom: 15px; transition: all 0.3s ease;">
                        <div class="film-position" style="font-size: 3em; font-weight: bold; color: #FFB042; margin-right: 20px; min-width: 60px; text-align: center;">
                            <?php echo $position; ?>️
                        </div>
                        
                        <div class="film-image" style="margin-right: 20px; flex-shrink: 0;">
                            <img src="<?php echo htmlspecialchars($imagePath); ?>" 
                                 alt="<?php echo htmlspecialchars($film['titre']); ?>"
                                 style="width: 120px; height: 160px; object-fit: cover; border-radius: 8px; border: 3px solid #FFB042;">
                        </div>
                        
                        <div class="film-info" style="flex-grow: 1;">
                            <h3 style="color: white; margin: 0 0 10px 0; font-size: 1.5em;">
                                <?php echo htmlspecialchars($film['titre']); ?>
                            </h3>
                            <?php if (!empty($film['categorie'])): ?>
                                <p style="color: rgba(255, 255, 255, 0.7); margin: 0 0 5px 0;">
                                    <?php echo htmlspecialchars($film['categorie']); ?>
                                </p>
                            <?php endif; ?>
                            <?php if ($noteMoyenne > 0): ?>
                                <p style="color: #FFB042; margin: 5px 0; font-weight: 600;">
                                    ⭐ Note moyenne : <?php echo number_format($noteMoyenne, 1); ?> / 5
                                </p>
                            <?php endif; ?>
                        </div>
                        
                        <div class="film-stats" style="text-align: right; min-width: 150px;">
                            <div class="stats-votes" style="color: white; font-size: 1.2em; margin-bottom: 10px;">
                                <strong><?php echo $nbVotes; ?></strong> vote<?php echo $nbVotes > 1 ? 's' : ''; ?>
                            </div>
                            <div class="stats-percent" style="color: #FFB042; font-size: 2em; font-weight: bold;">
                                <?php echo number_format($pourcentage, 1); ?>%
                            </div>
                            <div class="progress-bar" style="width: 100%; height: 8px; background: rgba(255, 255, 255, 0.2); border-radius: 4px; margin-top: 10px; overflow: hidden;">
                                <div class="progress-fill" style="width: <?php echo $pourcentage; ?>%; height: 100%; background: linear-gradient(90deg, #FFB042 0%, #b61f0a 100%); transition: width 0.5s ease;"></div>
                            </div>
                        </div>
                    </div>
                <?php 
                    $position++;
                endforeach; ?>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
<?php endif; ?>

<style>
.film-result:hover {
    background: rgba(255, 255, 255, 0.15) !important;
    transform: translateX(5px);
}

@media (max-width: 768px) {
    .film-result {
        flex-direction: column !important;
        text-align: center !important;
    }
    
    .film-position {
        margin-right: 0 !important;
        margin-bottom: 10px !important;
    }
    
    .film-image {
        margin-right: 0 !important;
        margin-bottom: 15px !important;
    }
    
    .film-stats {
        text-align: center !important;
        margin-top: 15px !important;
    }
}
</style>
