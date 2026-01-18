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

        </div>
    </aside>

    <!-- CONTENT -->
    <main class="content">
        <div class="content-header">
            <h1>Gérer les Sessions de Vote</h1>
            <div class="breadcrumb">
                <a href="<?php echo ROOT_PATH; ?>index.php?action=admin_dashboard">Tableau de bord</a> / 
                <span>Sessions de Vote</span>
            </div>
        </div>

        <?php if (isset($_GET['status'])): ?>
            <div class="alert alert-success">
                <?php
                if ($_GET['status'] === 'created') echo 'Session de vote créée avec succès !';
                elseif ($_GET['status'] === 'deleted') echo 'Session de vote supprimée avec succès !';
                elseif ($_GET['status'] === 'error') echo 'Une erreur est survenue.';
                ?>
            </div>
        <?php endif; ?>

        <section class="admin-section">

            
            <div class="table-container">

                <div class="section-header">
                    <h2>Liste des Sessions de Vote</h2>
                    <a href="<?php echo ROOT_PATH; ?>index.php?action=admin_create_session" class="btn-add">
                        + Créer une session de vote
                    </a>
                </div>
                <?php if (empty($sessions)): ?>
                    <div style="text-align: center; padding: 40px;">
                        <p>Aucune session de vote enregistrée.</p>
                    </div>
                <?php else: ?>
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Année</th>
                                <th>Date de début</th>
                                <th>Date de fin</th>
                                <th>Durée (jours)</th>
                                <th>Statut</th>
                                <th class="actions-col">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($sessions as $session): 
                                $today = date('Y-m-d');
                                $dateDebut = $session->getDateDebut();
                                $dateFin = $session->getDateFin();
                                $isActive = ($dateDebut <= $today && $dateFin >= $today);
                                $isPast = ($dateFin < $today);
                                $isFuture = ($dateDebut > $today);
                            ?>
                                <tr>
                                    <td><?php echo $session->getIdSessionvote(); ?></td>
                                    <td><?php echo htmlspecialchars($session->getAnnee()); ?></td>
                                    <td><?php echo date('d/m/Y', strtotime($dateDebut)); ?></td>
                                    <td><?php echo date('d/m/Y', strtotime($dateFin)); ?></td>
                                    <td><?php echo htmlspecialchars($session->getDuree()); ?></td>
                                    <td>
                                        <?php if ($isActive): ?>
                                            <span class="badge-status" style="background: #22c55e; color: white;">Active</span>
                                        <?php elseif ($isPast): ?>
                                            <span class="badge-status" style="background: #6b7280; color: white;">Terminée</span>
                                        <?php else: ?>
                                            <span class="badge-status" style="background: #3b82f6; color: white;">À venir</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="actions-cell">
                                        <button class="btn-delete" onclick="confirmDelete(<?php echo $session->getIdSessionvote(); ?>, 'Session <?php echo $session->getAnnee(); ?>')" title="Supprimer">
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
function confirmDelete(id, name) {
    const modal = document.getElementById('confirmModal');
    const message = document.getElementById('confirmMessage');
    const confirmBtn = document.getElementById('confirmYes');
    
    message.textContent = `Êtes-vous sûr de vouloir supprimer la session "${name}" ? Cette action est irréversible.`;
    
    confirmBtn.onclick = function() {
        window.location.href = '<?php echo ROOT_PATH; ?>index.php?action=admin_delete_session&id=' + id;
    };
    
    document.getElementById('confirmNo').onclick = function() {
        modal.style.display = 'none';
    };
    document.querySelector('.close-modal').onclick = function() {
        modal.style.display = 'none';
    };
    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = 'none';
        }
    };
    
    modal.style.display = 'block';
}
</script>

<?php require ROOT_DIR . 'views/layout/footer.php'; ?>

