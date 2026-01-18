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
            <a href="<?php echo ROOT_PATH; ?>index.php?action=admin_list_films" class="nav-link active">
                Gérer les Films
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
            <h1>Gestion des Films</h1>
            <div class="breadcrumb">
                <a href="<?php echo ROOT_PATH; ?>index.php?action=admin_dashboard">Tableau de bord</a> / 
                <span>Films</span>
            </div>
        </div>

        <?php if (isset($_GET['status'])): ?>
            <div class="alert alert-success">
                <?php 
                if ($_GET['status'] === 'success') echo 'Film ajouté avec succès !';
                elseif ($_GET['status'] === 'updated') echo 'Film modifié avec succès !';
                elseif ($_GET['status'] === 'deleted') echo 'Film supprimé avec succès !';
                elseif ($_GET['status'] === 'error') echo 'Une erreur est survenue.';
                ?>
            </div>
        <?php endif; ?>

        <section class="admin-section">
            <div class="section-header">


            
            <div class="table-container">
                <h2>Liste des Films</h2>
                <a href="<?php echo ROOT_PATH; ?>index.php?action=admin_add_film" class="btn-add">
                    + Ajouter un film
                </a>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Titre</th>
                            <th>Catégorie</th>
                            <th>Année</th>
                            <th>Note moyenne</th>
                            <th>Votes</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($films)): ?>
                            <tr>
                                <td colspan="7" style="text-align: center; padding: 40px;">
                                    Aucun film enregistré.
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($films as $film): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($film['id_film'] ?? ''); ?></td>
                                    <td><strong><?php echo htmlspecialchars($film['titre'] ?? ''); ?></strong></td>
                                    <td><?php echo htmlspecialchars($film['categorie'] ?? 'N/A'); ?></td>
                                    <td><?php echo htmlspecialchars($film['annee'] ?? 'N/A'); ?></td>
                                    <td>
                                        <?php if (isset($film['note_moyenne']) && $film['note_moyenne'] != null): ?>
                                            <span class="rating"><?php echo number_format($film['note_moyenne'], 1); ?>/5</span>
                                        <?php else: ?>
                                            <span class="no-rating">Pas de note</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo htmlspecialchars($film['nb_votes'] ?? 0); ?></td>
                                    <td class="actions-cell">
                                        <a href="<?php echo ROOT_PATH; ?>index.php?action=admin_edit_film&id=<?php echo $film['id_film']; ?>" class="btn-edit">
                                            Modifier
                                        </a>
                                        <a href="<?php echo ROOT_PATH; ?>index.php?action=admin_delete_film&id=<?php echo $film['id_film']; ?>" 
                                           class="btn-delete"
                                           onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce film ?');">
                                            Supprimer
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            </div>
        </section>
    </main>
</div>

<?php require ROOT_DIR . 'views/layout/footer.php'; ?>

