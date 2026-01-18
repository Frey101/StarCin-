<?php require ROOT_DIR . 'views/layout/header.php'; ?>

<div class="admin-container">

    <!-- SIDEBAR -->
    <aside class="sidebar">
        <div class="sidebar-header">
            <h2>Administration</h2>
            <p class="admin-welcome">Bienvenue, <?php echo htmlspecialchars($_SESSION['user_email'] ?? 'Admin'); ?></p>
        </div>
        <nav class="sidebar-nav">
            <a href="<?php echo ROOT_PATH; ?>index.php?action=admin_dashboard" class="nav-link active">
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
            <a href="<?php echo ROOT_PATH; ?>index.php?action=admin_realisateurs" class="nav-link">
                Réalisateurs
            </a>
            <a href="<?php echo ROOT_PATH; ?>index.php?action=admin_propositions" class="nav-link">
                Propositions
                <?php if (isset($stats['propositions']) && $stats['propositions'] > 0): ?>
                    <span class="badge"><?php echo $stats['propositions']; ?></span>
                <?php endif; ?>
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
            <h1>Tableau de bord Administrateur</h1>
            <div class="breadcrumb">
                <a href="<?php echo ROOT_PATH; ?>index.php?action=home">Accueil</a> / 
                <span>Administration</span>
            </div>
        </div>

        <?php if (isset($_GET['status'])): ?>
            <div class="alert alert-success">
                <?php 
                if (in_array($_GET['status'], ['success', 'updated', 'deleted', 'comment_deleted', 'promoted', 'accepted', 'refused'])) {
                    echo 'Opération réussie avec succès !';
                } elseif ($_GET['status'] === 'error') {
                    echo 'Une erreur est survenue.';
                }
                ?>
            </div>
        <?php endif; ?>

        <!-- Résumé -->
        <section class="stats-cards">
            <div class="card">
                <div class="card-content">
                    <h3>Films</h3>
                    <p class="card-number"><?php echo $stats['films'] ?? 0; ?></p>
                    <span class="card-label">films enregistrés</span>
                </div>
            </div>

            <div class="card">
                <div class="card-content">
                    <h3>Utilisateurs</h3>
                    <p class="card-number"><?php echo $stats['users'] ?? 0; ?></p>
                    <span class="card-label">inscrits</span>
                </div>
            </div>

            <div class="card">
                <div class="card-content">
                    <h3>Propositions</h3>
                    <p class="card-number"><?php echo $stats['propositions'] ?? 0; ?></p>
                    <span class="card-label">en attente</span>
                </div>
            </div>

            <div class="card">
                <div class="card-content">
                    <h3>Votes</h3>
                    <p class="card-number"><?php echo number_format($stats['votes'] ?? 0, 0, ',', ' '); ?></p>
                    <span class="card-label">notes données</span>
                </div>
            </div>
        </section>

        <!-- Gérer les films -->
        <section id="films" class="admin-section">

            
            <div class="table-container">
                <div class="section-header">
                    <h2>Gérer les Films</h2>
                    <a href="<?php echo ROOT_PATH; ?>index.php?action=admin_add_film" class="btn-add">
                        + Ajouter un film
                    </a>
                </div>
                <div class="table-actions">
                    <input type="text" id="searchFilms" class="search-input" placeholder="Rechercher un film...">
                </div>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Titre</th>
                            <th>Catégorie</th>
                            <th>Note moyenne</th>
                            <th class="actions-col">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($films)): ?>
                            <tr>
                                <td colspan="5" class="empty-state">Aucun film enregistré</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($films as $film): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($film['id_film'] ?? ''); ?></td>
                                    <td><strong><?php echo htmlspecialchars($film['titre'] ?? 'Sans titre'); ?></strong></td>
                                    <td><span class="badge-category"><?php echo htmlspecialchars($film['categorie'] ?? 'Non définie'); ?></span></td>
                                    <td>
                                        <?php if (isset($film['note_moyenne']) && $film['note_moyenne'] != null): ?>
                                            <span class="rating"><?php echo number_format($film['note_moyenne'], 1); ?> / 5</span>
                                        <?php else: ?>
                                            <span class="no-rating">Aucune note</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="actions-cell">
                                        <a href="<?php echo ROOT_PATH; ?>index.php?action=admin_edit_film&id=<?php echo $film['id_film']; ?>" class="btn-edit" title="Modifier">
                                            Modifier
                                        </a>
                                        <a href="<?php echo ROOT_PATH; ?>index.php?action=admin_delete_film&id=<?php echo $film['id_film']; ?>" 
                                           class="btn-delete"
                                           onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce film ?');"
                                           title="Supprimer">
                                            Supprimer
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>

        <!-- Gérer les utilisateurs -->
        <section id="users" class="admin-section">

            <div class="table-container">
                <div class="section-header">
                    <h2>Utilisateurs</h2>
                </div>

                <input type="text" id="searchUsers" class="search-input" placeholder="Rechercher un utilisateur...">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Email</th>
                            <th>Rôle</th>
                            <th class="actions-col">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($users)): ?>
                            <tr>
                                <td colspan="4" class="empty-state">Aucun utilisateur</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($users as $user): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($user['id_utilisateur'] ?? ''); ?></td>
                                    <td><?php echo htmlspecialchars($user['email'] ?? ''); ?></td>
                                    <td>
                                        <span class="badge-role <?php echo ($user['role'] ?? 'user') === 'admin' ? 'badge-admin' : 'badge-user'; ?>">
                                            <?php echo ($user['role'] ?? 'user') === 'admin' ? 'Admin' : 'Utilisateur'; ?>
                                        </span>
                                    </td>
                                    <td class="actions-cell">
                                        <?php if (($user['role'] ?? 'user') !== 'admin'): ?>
                                            <button class="btn-edit" onclick="promoteUser(<?php echo $user['id_utilisateur']; ?>)" title="Promouvoir admin">
                                                Promouvoir
                                            </button>
                                        <?php endif; ?>
                                        <a href="<?php echo ROOT_PATH; ?>index.php?action=admin_delete_user&id=<?php echo $user['id_utilisateur']; ?>" 
                                           class="btn-delete"
                                           onclick="return confirmDeleteUser(<?php echo $user['id_utilisateur']; ?>, '<?php echo htmlspecialchars($user['email'] ?? ''); ?>');"
                                           title="Supprimer">
                                            Supprimer
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>

        <!-- Propositions -->
        <section id="propositions" class="admin-section">

            
            <div class="table-container">
                <div class="section-header">
                    <h2>Propositions de Films</h2>
                </div>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Réalisateur</th>
                            <th>Email</th>
                            <th>Commentaire</th>
                            <th>Statut</th>
                            <th class="actions-col">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($propositions)): ?>
                            <tr>
                                <td colspan="6" class="empty-state">Aucune proposition en attente</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($propositions as $prop): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($prop['id_propositionFilm'] ?? ''); ?></td>
                                    <td><strong><?php 
                                        $realisateurName = '';
                                        if (!empty($prop['nom']) && !empty($prop['prenom'])) {
                                            $realisateurName = htmlspecialchars($prop['prenom'] . ' ' . $prop['nom']);
                                        } elseif (!empty($prop['utilisateur_email'])) {
                                            $realisateurName = htmlspecialchars($prop['utilisateur_email']);
                                        } else {
                                            $realisateurName = 'N/A';
                                        }
                                        echo $realisateurName;
                                    ?></strong></td>
                                    <td><?php echo htmlspecialchars($prop['utilisateur_email'] ?? 'N/A'); ?></td>
                                    <td><?php echo htmlspecialchars($prop['commentaire'] ?? 'N/A'); ?></td>
                                    <td><span class="badge-status"><?php echo htmlspecialchars($prop['statut'] ?? 'en_attente'); ?></span></td>
                                    <td class="actions-cell">
                                        <a href="<?php echo ROOT_PATH; ?>index.php?action=admin_accept_proposition&id=<?php echo $prop['id_propositionFilm']; ?>" 
                                           class="btn-confirm"
                                           onclick="return acceptProposition(<?php echo $prop['id_propositionFilm']; ?>);"
                                           title="Accepter">
                                            Accepter
                                        </a>
                                        <a href="<?php echo ROOT_PATH; ?>index.php?action=admin_refuse_proposition&id=<?php echo $prop['id_propositionFilm']; ?>" 
                                           class="btn-delete"
                                           onclick="return refuseProposition(<?php echo $prop['id_propositionFilm']; ?>);"
                                           title="Refuser">
                                            Refuser
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>

        <!-- Commentaires -->
        <section id="comments" class="admin-section">

            
            <div class="table-container">
                <div class="section-header">
                    <h2>Commentaires</h2>
                </div>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Utilisateur</th>
                            <th>Commentaire</th>
                            <th>Forum</th>
                            <th class="actions-col">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($comments)): ?>
                            <tr>
                                <td colspan="5" class="empty-state">Aucun commentaire</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($comments as $comment): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($comment['id_message'] ?? ''); ?></td>
                                    <td><?php echo htmlspecialchars($comment['email'] ?? 'Anonyme'); ?></td>
                                    <td class="comment-text"><?php echo htmlspecialchars(substr($comment['contenu'] ?? '', 0, 100)) . (strlen($comment['contenu'] ?? '') > 100 ? '...' : ''); ?></td>
                                    <td><?php echo htmlspecialchars($comment['forum_titre'] ?? 'N/A'); ?></td>
                                    <td class="actions-cell">
                                        <a href="<?php echo ROOT_PATH; ?>index.php?action=admin_delete_comment&id=<?php echo $comment['id_message']; ?>" 
                                           class="btn-delete"
                                           onclick="return confirmDeleteComment(<?php echo $comment['id_message']; ?>);"
                                           title="Supprimer">
                                            Supprimer
                                        </a>
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

<!-- Modal de confirmation -->
<div id="confirmModal" class="modal">
    <div class="modal-content">
        <span class="close-modal">&times;</span>
        <h3>Confirmation</h3>
        <p id="confirmMessage"></p>
        <div class="modal-actions">
            <button id="confirmYes" class="btn-confirm">Oui, confirmer</button>
            <button id="confirmNo" class="btn-cancel">Annuler</button>
        </div>
    </div>
</div>

<script>
// Recherche dans les tableaux
document.getElementById('searchFilms')?.addEventListener('input', function(e) {
    filterTable(e.target.value, 'films');
});

document.getElementById('searchUsers')?.addEventListener('input', function(e) {
    filterTable(e.target.value, 'users');
});

function filterTable(searchTerm, tableType) {
    const table = document.querySelector(`#${tableType === 'films' ? 'films' : 'users'} .data-table tbody`);
    if (!table) return;
    
    const rows = table.querySelectorAll('tr');
    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(searchTerm.toLowerCase()) ? '' : 'none';
    });
}

// Confirmations de suppression
function confirmDelete(id, name) {
    const modal = document.getElementById('confirmModal');
    const message = document.getElementById('confirmMessage');
    const confirmBtn = document.getElementById('confirmYes');
    
    message.textContent = `Êtes-vous sûr de vouloir supprimer le film "${name}" ? Cette action est irréversible.`;
    
    confirmBtn.onclick = function() {
        window.location.href = '<?php echo ROOT_PATH; ?>index.php?action=admin_delete_film&id=' + id;
    };
    
    modal.style.display = 'block';
}

function confirmDeleteUser(id, email) {
    if (confirm('Êtes-vous sûr de vouloir supprimer l\'utilisateur "' + email + '" ? Cette action est irréversible.')) {
        window.location.href = '<?php echo ROOT_PATH; ?>index.php?action=admin_delete_user&id=' + id;
    }
    return false;
}

function confirmDeleteComment(id) {
    if (confirm('Êtes-vous sûr de vouloir supprimer ce commentaire ?')) {
        window.location.href = '<?php echo ROOT_PATH; ?>index.php?action=admin_delete_comment&id=' + id;
    }
    return false;
}

function promoteUser(id) {
    return confirm('Promouvoir cet utilisateur au rang d\'administrateur ?');
}

function acceptProposition(id) {
    return confirm('Accepter cette proposition de film ?');
}

function refuseProposition(id) {
    return confirm('Refuser cette proposition de film ?');
}

// Fermer la modal
document.querySelector('.close-modal')?.addEventListener('click', function() {
    document.getElementById('confirmModal').style.display = 'none';
});

document.getElementById('confirmNo')?.addEventListener('click', function() {
    document.getElementById('confirmModal').style.display = 'none';
});

window.onclick = function(event) {
    const modal = document.getElementById('confirmModal');
    if (event.target === modal) {
        modal.style.display = 'none';
    }
}
</script>


