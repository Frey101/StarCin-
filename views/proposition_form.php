<?php require ROOT_DIR . 'views/layout/header.php'; ?>

<div class="admin-container">
    <!-- SIDEBAR -->
    <aside class="sidebar">
        <div class="sidebar-header">
            <h2>Espace Réalisateur</h2>
            <p class="admin-welcome">Bienvenue, <?php echo htmlspecialchars($_SESSION['user_email'] ?? 'Réalisateur'); ?></p>
        </div>
        <nav class="sidebar-nav">
            <a href="<?php echo ROOT_PATH; ?>index.php?action=proposition_form" class="nav-link active">
                Proposer un Film
            </a>
            <a href="<?php echo ROOT_PATH; ?>index.php?action=home" class="nav-link">
                Retour à l'accueil
            </a>

        </nav>
    </aside>

    <!-- CONTENT -->
    <main class="content">
        <div class="content-header">
            <h1>Proposer un Nouveau Film</h1>
            <div class="breadcrumb">
                <a href="<?php echo ROOT_PATH; ?>index.php?action=home">Accueil</a> /
                <span>Proposition de film</span>
            </div>
        </div>

        <?php if (isset($message) && $message): ?>
            <div class="alert alert-success" style="max-width: 800px; margin: 20px auto; padding: 15px; background: rgba(34, 197, 94, 0.2); border: 2px solid #22c55e; border-radius: 8px; color: white;">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>

        <?php if (isset($error) && $error): ?>
            <div class="alert alert-error" style="max-width: 800px; margin: 20px auto; padding: 15px; background: rgba(220, 38, 38, 0.2); border: 2px solid #dc2626; border-radius: 8px; color: white;">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <section class="admin-section">
            <div class="section-header">
                <h2>Informations du Film</h2>
                <p style="color: rgba(255,255,255,0.7); margin-top: 10px;">
                    Remplissez ce formulaire pour proposer un nouveau film à ajouter à la plateforme StarCiné.
                </p>
            </div>

            <form method="POST" action="<?php echo ROOT_PATH; ?>index.php?action=proposition_form" style="max-width: 800px; margin: 0 auto;">
                <div class="form-group">
                    <label for="commentaire">Commentaire/Description de votre proposition *</label>
                    <textarea id="commentaire" name="commentaire" rows="6"
                              placeholder="Décrivez brièvement votre film : synopsis, genre, acteurs principaux, pourquoi il mérite d'être ajouté..."
                              style="width: 100%; padding: 15px; border: 2px solid rgba(255, 255, 255, 0.1); border-radius: 8px; background: rgba(0, 0, 0, 0.3); color: white; font-family: inherit; resize: vertical;"
                              required></textarea>
                    <small style="color: rgba(255,255,255,0.6); display: block; margin-top: 5px;">
                        Minimum 50 caractères. Soyez le plus détaillé possible pour convaincre les administrateurs.
                    </small>
                </div>

                <div class="form-actions" style="text-align: center; margin-top: 30px;">
                    <button type="submit" class="btn-confirm" style="padding: 15px 40px; background: #b61f0a; color: white; border: none; border-radius: 8px; font-size: 1.1em; font-weight: 600; cursor: pointer; transition: all 0.3s ease;">
                        Soumettre ma proposition
                    </button>
                    <a href="<?php echo ROOT_PATH; ?>index.php?action=home" class="btn-cancel" style="margin-left: 20px; padding: 15px 40px; background: rgba(255,255,255,0.1); color: white; text-decoration: none; border-radius: 8px; font-size: 1.1em; transition: all 0.3s ease; display: inline-block;">
                        Annuler
                    </a>
                </div>
            </form>
        </section>

        <!-- INFO SECTION -->
        <section class="admin-section" style="margin-top: 40px;">
            <div class="section-header">
                <h2>À propos des propositions</h2>
            </div>

            <div style="background: rgba(0, 0, 0, 0.3); padding: 20px; border-radius: 8px; border: 1px solid rgba(255, 255, 255, 0.1);">
                <h3 style="color: #FFB042; margin-top: 0;">Comment ça fonctionne ?</h3>
                <ul style="color: rgba(255,255,255,0.8); line-height: 1.6;">
                    <li><strong>Éligibilité :</strong> Seuls les utilisateurs désignés comme "réalisateurs" peuvent proposer des films.</li>
                    <li><strong>Révision :</strong> Votre proposition sera examinée par les administrateurs de StarCiné.</li>
                    <li><strong>Délai :</strong> Le processus de validation peut prendre quelques jours.</li>
                    <li><strong>Critères :</strong> Le film doit être une œuvre cinématographique existante ou en production.</li>
                    <li><strong>Notification :</strong> Vous serez informé par email de la décision des administrateurs.</li>
                </ul>

                <h3 style="color: #FFB042; margin-top: 20px;">Que doit contenir votre proposition ?</h3>
                <ul style="color: rgba(255,255,255,0.8); line-height: 1.6;">
                    <li>Titre du film</li>
                    <li>Année de sortie (ou prévue)</li>
                    <li>Genre/catégorie</li>
                    <li>Réalisateur</li>
                    <li>Acteurs principaux</li>
                    <li>Synopsis détaillé</li>
                    <li>Pourquoi ce film mérite d'être sur StarCiné</li>
                </ul>
            </div>
        </section>
    </main>
</div>

<script>
// Validation côté client
document.querySelector('form').addEventListener('submit', function(e) {
    const commentaire = document.getElementById('commentaire').value.trim();

    if (commentaire.length < 50) {
        e.preventDefault();
        alert('Votre commentaire doit contenir au moins 50 caractères.');
        return false;
    }

    // Confirmation
    if (!confirm('Êtes-vous sûr de vouloir soumettre cette proposition ? Elle sera examinée par les administrateurs.')) {
        e.preventDefault();
        return false;
    }
});

// Compteur de caractères
document.getElementById('commentaire').addEventListener('input', function() {
    const count = this.value.length;
    const minChars = 50;

    // Créer ou mettre à jour le compteur
    let counter = this.parentNode.querySelector('.char-counter');
    if (!counter) {
        counter = document.createElement('small');
        counter.className = 'char-counter';
        counter.style.cssText = 'display: block; margin-top: 5px;';
        this.parentNode.appendChild(counter);
    }

    counter.textContent = count + ' caractères (minimum ' + minChars + ' requis)';
    counter.style.color = count >= minChars ? '#22c55e' : '#dc2626';
});
</script>

