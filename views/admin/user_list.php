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
            <a href="<?php echo ROOT_PATH; ?>index.php?action=admin_users" class="nav-link active">
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
            <h1>Gestion des Utilisateurs</h1>
            <div class="breadcrumb">
                <a href="<?php echo ROOT_PATH; ?>index.php?action=admin_dashboard">Tableau de bord</a> / 
                <span>Utilisateurs</span>
            </div>
        </div>

        <?php if (isset($_GET['status'])): ?>
            <div class="alert alert-success">
                <?php 
                if ($_GET['status'] === 'promoted') echo 'Utilisateur promu administrateur avec succès !';
                elseif ($_GET['status'] === 'deleted') echo 'Utilisateur supprimé avec succès !';
                elseif ($_GET['status'] === 'error') echo 'Une erreur est survenue.';
                ?>
            </div>
        <?php endif; ?>

        <section class="admin-section">

            
            <div class="table-container">
                <div class="section-header">
                    <h2>Liste des Utilisateurs</h2>
                </div>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Email</th>
                            <th>Nom</th>
                            <th>Prénom</th>
                            <th>Ville</th>
                            <th>Rôle</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($users)): ?>
                            <tr>
                                <td colspan="7" style="text-align: center; padding: 40px;">
                                    Aucun utilisateur enregistré.
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($users as $user): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($user['id_utilisateur'] ?? ''); ?></td>
                                    <td><strong><?php echo htmlspecialchars($user['email'] ?? ''); ?></strong></td>
                                    <td><?php echo htmlspecialchars($user['nom'] ?? 'N/A'); ?></td>
                                    <td><?php echo htmlspecialchars($user['prenom'] ?? 'N/A'); ?></td>
                                    <td><?php echo htmlspecialchars($user['ville'] ?? 'N/A'); ?></td>
                                    <td>
                                        <?php if (($user['role'] ?? '') === 'admin'): ?>
                                            <span class="badge badge-admin">Admin</span>
                                        <?php else: ?>
                                            <span class="badge badge-user">Utilisateur</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="actions-cell">
                                        <?php if (($user['role'] ?? '') !== 'admin'): ?>
                                            <a href="<?php echo ROOT_PATH; ?>index.php?action=admin_promote_user&id=<?php echo $user['id_utilisateur']; ?>" 
                                               class="btn-edit"
                                               onclick="return confirm('Promouvoir cet utilisateur au rang d\'administrateur ?');">
                                                Promouvoir
                                            </a>
                                        <?php endif; ?>
                                        <?php if (($user['id_utilisateur'] ?? 0) != ($_SESSION['user_id'] ?? 0)): ?>
                                            <a href="<?php echo ROOT_PATH; ?>index.php?action=admin_delete_user&id=<?php echo $user['id_utilisateur']; ?>" 
                                               class="btn-delete"
                                               onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?');">
                                                Supprimer
                                            </a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>
</div>

<?php require ROOT_DIR . 'views/layout/footer.php'; ?>

