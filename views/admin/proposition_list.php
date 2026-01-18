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
            <a href="<?php echo ROOT_PATH; ?>index.php?action=admin_users" class="nav-link">
                Utilisateurs
            </a>
            <a href="<?php echo ROOT_PATH; ?>index.php?action=admin_propositions" class="nav-link active">
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
            <h1>Gestion des Propositions</h1>
            <div class="breadcrumb">
                <a href="<?php echo ROOT_PATH; ?>index.php?action=admin_dashboard">Tableau de bord</a> / 
                <span>Propositions</span>
            </div>
        </div>

        <?php if (isset($_GET['status'])): ?>
            <div class="alert alert-success">
                <?php 
                if ($_GET['status'] === 'accepted') echo 'Proposition acceptée avec succès !';
                elseif ($_GET['status'] === 'refused') echo 'Proposition refusée.';
                elseif ($_GET['status'] === 'error') echo 'Une erreur est survenue.';
                ?>
            </div>
        <?php endif; ?>

        <section class="admin-section">

            <div class="table-container">
                <div class="section-header">
                    <h2>Propositions en Attente</h2>
                </div>

                <?php if (empty($propositions)): ?>
                    <div style="text-align: center; padding: 40px;">
                        <p>Aucune proposition en attente.</p>
                    </div>
                <?php else: ?>
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Date</th>
                                <th>Commentaire</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($propositions as $proposition): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($proposition['id_propositionFilm'] ?? ''); ?></td>
                                    <td><?php echo htmlspecialchars($proposition['date_proposition'] ?? 'N/A'); ?></td>
                                    <td><?php echo htmlspecialchars($proposition['commentaire'] ?? 'Aucun commentaire'); ?></td>
                                    <td>
                                        <span class="badge badge-status"><?php echo htmlspecialchars($proposition['statut'] ?? 'en_attente'); ?></span>
                                    </td>
                                    <td class="actions-cell">
                                        <a href="<?php echo ROOT_PATH; ?>index.php?action=admin_accept_proposition&id=<?php echo $proposition['id_propositionFilm']; ?>" 
                                           class="btn-confirm"
                                           onclick="return confirm('Accepter cette proposition ?');">
                                            Accepter
                                        </a>
                                        <a href="<?php echo ROOT_PATH; ?>index.php?action=admin_refuse_proposition&id=<?php echo $proposition['id_propositionFilm']; ?>" 
                                           class="btn-delete"
                                           onclick="return confirm('Refuser cette proposition ?');">
                                            Refuser
                                        </a>
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

<?php require ROOT_DIR . 'views/layout/footer.php'; ?>

