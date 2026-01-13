<?php require ROOT_DIR . 'views/layout/header.php'; ?>

<div class="admin-container">
    <!-- SIDEBAR -->
    <aside class="sidebar">
        <div class="sidebar-header">
            <h2>Administration</h2>
            <p class="admin-welcome">Bienvenue, <?php echo htmlspecialchars($_SESSION['user_email'] ?? 'Admin'); ?></p>
        </div>
        <nav class="sidebar-nav">
            <a href="<?php echo ROOT_PATH; ?>index.php?action=admin_dashboard" class="nav-link">
                Tableau de bord
            </a>
            <a href="<?php echo ROOT_PATH; ?>index.php?action=admin_list_films" class="nav-link">
                Gérer les Films
            </a>
            <a href="<?php echo ROOT_PATH; ?>index.php?action=admin_sessions" class="nav-link active">
                Sessions de Vote
            </a>
            <a href="<?php echo ROOT_PATH; ?>index.php?action=admin_users" class="nav-link">
                Utilisateurs
            </a>
            <a href="<?php echo ROOT_PATH; ?>index.php?action=admin_propositions" class="nav-link">
                Propositions
            </a>
        </nav>
        <div class="sidebar-footer">
            <a href="<?php echo ROOT_PATH; ?>index.php?action=home" class="nav-link">
                Retour au site
            </a>
            <a href="<?php echo ROOT_PATH; ?>index.php?action=logout" class="logout">
                Déconnexion
            </a>
        </div>
    </aside>

    <!-- CONTENT -->
    <main class="content">
        <div class="content-header">
            <h1>Créer une Session de Vote</h1>
            <div class="breadcrumb">
                <a href="<?php echo ROOT_PATH; ?>index.php?action=admin_dashboard">Tableau de bord</a> / 
                <a href="<?php echo ROOT_PATH; ?>index.php?action=admin_sessions">Sessions de Vote</a> / 
                <span>Créer</span>
            </div>
        </div>

        <?php if (isset($message) && $message): ?>
            <div class="alert alert-error">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>

        <section class="admin-section">
            <form method="POST" action="<?php echo ROOT_PATH; ?>index.php?action=admin_create_session">
                <div class="form-row">
                    <div class="form-group">
                        <label for="annee">Année *</label>
                        <input type="number" id="annee" name="annee" 
                               value="<?php echo date('Y'); ?>" 
                               min="2020" max="2100" required>
                    </div>

                    <div class="form-group">
                        <label for="duree">Durée (en jours) *</label>
                        <input type="number" id="duree" name="duree" 
                               value="7" 
                               min="1" max="365" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="dateDebut">Date de début *</label>
                        <input type="date" id="dateDebut" name="dateDebut" 
                               value="<?php echo date('Y-m-d'); ?>" 
                               required>
                    </div>

                    <div class="form-group">
                        <label for="dateFin">Date de fin *</label>
                        <input type="date" id="dateFin" name="dateFin" 
                               required>
                    </div>
                </div>

                <div class="form-group">
                    <label>Films à inclure dans la session *</label>
                    <div style="max-height: 400px; overflow-y: auto; border: 2px solid rgba(255, 255, 255, 0.1); border-radius: 8px; padding: 15px; background: rgba(0, 0, 0, 0.3);">
                        <?php if (empty($films)): ?>
                            <p style="color: rgba(255,255,255,0.6);">Aucun film disponible. Créez d'abord des films.</p>
                        <?php else: ?>
                            <?php foreach ($films as $film): ?>
                                <label style="display: flex; align-items: center; padding: 10px; margin: 5px 0; background: rgba(255,255,255,0.05); border-radius: 5px; cursor: pointer;">
                                    <input type="checkbox" name="films[]" value="<?php echo $film['id_film']; ?>" 
                                           style="margin-right: 10px; width: 20px; height: 20px; cursor: pointer;">
                                    <span style="color: white;">
                                        <strong><?php echo htmlspecialchars($film['titre']); ?></strong>
                                        <?php if (!empty($film['annee'])): ?>
                                            (<?php echo htmlspecialchars($film['annee']); ?>)
                                        <?php endif; ?>
                                        <?php if (!empty($film['categorie'])): ?>
                                            - <?php echo htmlspecialchars($film['categorie']); ?>
                                        <?php endif; ?>
                                    </span>
                                </label>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                    <small style="color: rgba(255,255,255,0.6); font-size: 12px; display: block; margin-top: 5px;">
                        Sélectionnez au moins un film pour la session de vote.
                    </small>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn-confirm">
                        Créer la session de vote
                    </button>
                    <a href="<?php echo ROOT_PATH; ?>index.php?action=admin_sessions" class="btn-cancel">
                        Annuler
                    </a>
                </div>
            </form>
        </section>
    </main>
</div>

<script>
// Calculer automatiquement la date de fin en fonction de la date de début et de la durée
document.getElementById('dateDebut')?.addEventListener('change', function() {
    updateDateFin();
});

document.getElementById('duree')?.addEventListener('input', function() {
    updateDateFin();
});

function updateDateFin() {
    const dateDebut = document.getElementById('dateDebut').value;
    const duree = parseInt(document.getElementById('duree').value) || 0;
    
    if (dateDebut && duree > 0) {
        const date = new Date(dateDebut);
        date.setDate(date.getDate() + duree - 1); // -1 car le jour de début compte
        const dateFin = date.toISOString().split('T')[0];
        document.getElementById('dateFin').value = dateFin;
    }
}
</script>

<?php require ROOT_DIR . 'views/layout/footer.php'; ?>

