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
            <a href="<?php echo ROOT_PATH; ?>index.php?action=admin_sessions" class="nav-link">
                Sessions de Vote
            </a>
            <a href="<?php echo ROOT_PATH; ?>index.php?action=admin_users" class="nav-link">
                Utilisateurs
            </a>
            <a href="<?php echo ROOT_PATH; ?>index.php?action=admin_realisateurs" class="nav-link active">
                Réalisateurs
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
            <h1>Gérer les Réalisateurs</h1>
            <div class="breadcrumb">
                <a href="<?php echo ROOT_PATH; ?>index.php?action=admin_dashboard">Tableau de bord</a> /
                <span>Réalisateurs</span>
            </div>
        </div>

        <?php if (isset($_GET['status'])): ?>
            <div class="alert alert-success">
                <?php
                if ($_GET['status'] === 'added') echo 'Réalisateur ajouté avec succès !';
                elseif ($_GET['status'] === 'removed') echo 'Réalisateur supprimé avec succès !';
                ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-error">
                <?php
                if ($_GET['error'] === 'invalid_user') echo 'Utilisateur invalide.';
                elseif ($_GET['error'] === 'add_failed') echo 'Erreur lors de l\'ajout du réalisateur.';
                elseif ($_GET['error'] === 'invalid_id') echo 'ID de réalisateur invalide.';
                elseif ($_GET['error'] === 'remove_failed') echo 'Erreur lors de la suppression du réalisateur.';
                ?>
            </div>
        <?php endif; ?>

        <!-- LISTE DES RÉALISATEURS -->
        <section class="admin-section">
            <div class="section-header">
                <h2>Réalisateurs Actuels</h2>
            </div>

            <div class="table-container">
                <?php if (empty($realisateurs)): ?>
                    <div style="text-align: center; padding: 40px;">
                        <p>Aucun réalisateur enregistré.</p>
                    </div>
                <?php else: ?>
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nom</th>
                                <th>Prénom</th>
                                <th>Email</th>
                                <th>Ville</th>
                                <th class="actions-col">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($realisateurs as $realisateur): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($realisateur['id_realisateur']); ?></td>
                                    <td><?php echo htmlspecialchars($realisateur['nom']); ?></td>
                                    <td><?php echo htmlspecialchars($realisateur['prenom']); ?></td>
                                    <td><?php echo htmlspecialchars($realisateur['email']); ?></td>
                                    <td><?php echo htmlspecialchars($realisateur['ville'] ?? 'N/A'); ?></td>
                                    <td class="actions-cell">
                                        <button class="btn-delete" onclick="confirmRemove(<?php echo $realisateur['id_realisateur']; ?>, '<?php echo htmlspecialchars($realisateur['prenom'] . ' ' . $realisateur['nom']); ?>')" title="Supprimer">
                                            Supprimer
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </section>

        <!-- AJOUTER UN RÉALISATEUR -->
        <section class="admin-section">
            <div class="section-header">
                <h2>Ajouter un Réalisateur</h2>
            </div>

            <?php if (empty($nonRealisateurs)): ?>
                <div style="text-align: center; padding: 40px;">
                    <p>Tous les utilisateurs sont déjà réalisateurs.</p>
                </div>
            <?php else: ?>
                <form method="POST" action="<?php echo ROOT_PATH; ?>index.php?action=admin_add_realisateur">
                    <div class="form-group">
                        <label for="user_id">Sélectionner un utilisateur :</label>
                        <select name="user_id" id="user_id" required style="width: 100%; padding: 10px; border: 2px solid rgba(255, 255, 255, 0.1); border-radius: 8px; background: rgba(0, 0, 0, 0.3); color: white;">
                            <option value="">-- Choisir un utilisateur --</option>
                            <?php foreach ($nonRealisateurs as $user): ?>
                                <option value="<?php echo $user['id_utilisateur']; ?>">
                                    <?php echo htmlspecialchars($user['nom'] . ' ' . $user['prenom'] . ' (' . $user['email'] . ')'); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn-confirm">
                            Ajouter comme réalisateur
                        </button>
                    </div>
                </form>
            <?php endif; ?>
        </section>
    </main>
</div>

<!-- Confirmation Modal -->
<div id="confirmModal" class="modal">
    <div class="modal-content">
        <span class="close-modal">&times;</span>
        <h3>Confirmation</h3>
        <p id="confirmMessage"></p>
        <div class="modal-actions">
            <button id="confirmNo" class="btn-cancel">Annuler</button>
            <button id="confirmYes" class="btn-confirm">Confirmer</button>
        </div>
    </div>
</div>

<script>
function confirmRemove(id, name) {
    const modal = document.getElementById('confirmModal');
    const message = document.getElementById('confirmMessage');
    const confirmBtn = document.getElementById('confirmYes');

    message.textContent = `Êtes-vous sûr de vouloir supprimer "${name}" de la liste des réalisateurs ? Cette action est irréversible.`;

    confirmBtn.onclick = function() {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '<?php echo ROOT_PATH; ?>index.php?action=admin_remove_realisateur';

        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'realisateur_id';
        input.value = id;

        form.appendChild(input);
        document.body.appendChild(form);
        form.submit();
    };

    document.getElementById('confirmNo').onclick = function() {
        modal.style.display = 'none';
    };
    document.querySelector('.close-modal').onclick = function() {
        modal.style.display = 'none';
    };

    modal.style.display = 'block';
}

// Fermer la modal en cliquant en dehors
window.onclick = function(event) {
    const modal = document.getElementById('confirmModal');
    if (event.target === modal) {
        modal.style.display = 'none';
    }
};
</script>

<?php require ROOT_DIR . 'views/layout/footer.php'; ?>