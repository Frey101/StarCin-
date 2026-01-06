<?php require ROOT_DIR . 'views/layout/header.php'; ?>

<div class="admin-container">

    <!-- SIDEBAR -->
    <aside class="sidebar">
        <h2>Admin</h2>
        <a href="#films"> Gérer les Films</a>
        <a href="#users">👤 Utilisateurs</a>
        <a href="#propositions">📨 Propositions</a>
        <a href="#comments">💬 Commentaires</a>
        <a href="#stats"> Statistiques</a>
        <a href="#" class="logout"> Déconnexion</a>
    </aside>

    <!-- CONTENT -->
    <main class="content">

        <h1>Tableau de bord Administrateur</h1>

        <!-- Résumé -->
        <section class="stats-cards">
            <div class="card">
                <h3> Films</h3>
                <p>132 films enregistrés</p>
            </div>

            <div class="card">
                <h3>👥 Utilisateurs</h3>
                <p>482 inscrits</p>
            </div>

            <div class="card">
                <h3>📨 Propositions</h3>
                <p>18 en attente</p>
            </div>

            <div class="card">
                <h3>⭐ Votes</h3>
                <p>3 912 notes données</p>
            </div>
        </section>

        <!-- Gérer les films -->
        <section id="films" class="admin-section">
            <h2>🎬 Gérer les Films</h2>
            <button class="btn-add">+ Ajouter un film</button>

            <table>
                <tr>
                    <th>ID</th>
                    <th>Titre</th>
                    <th>Catégorie</th>
                    <th>Note moyenne</th>
                    <th>Actions</th>
                </tr>

                <tr>
                    <td>1</td>
                    <td>Inception</td>
                    <td>Science-Fiction</td>
                    <td>4.5</td>
                    <td>
                        <button class="btn-edit">Modifier</button>
                        <button class="btn-delete">Supprimer</button>
                    </td>
                </tr>

                <tr>
                    <td>2</td>
                    <td>Interstellar</td>
                    <td>Science-Fiction</td>
                    <td>4.7</td>
                    <td>
                        <button class="btn-edit">Modifier</button>
                        <button class="btn-delete">Supprimer</button>
                    </td>
                </tr>

            </table>
        </section>

        <!-- Gérer les utilisateurs -->
        <section id="users" class="admin-section">
            <h2>👤 Utilisateurs</h2>

            <table>
                <tr>
                    <th>ID</th>
                    <th>Email</th>
                    <th>Rôle</th>
                    <th>Actions</th>
                </tr>

                <tr>
                    <td>12</td>
                    <td>exemple@gmail.com</td>
                    <td>Utilisateur</td>
                    <td>
                        <button class="btn-edit">Promouvoir</button>
                        <button class="btn-delete">Supprimer</button>
                    </td>
                </tr>

            </table>
        </section>

        <!-- Propositions -->
        <section id="propositions" class="admin-section">
            <h2>📨 Propositions de Films</h2>

            <table>
                <tr>
                    <th>ID</th>
                    <th>Titre proposé</th>
                    <th>Utilisateur</th>
                    <th>Actions</th>
                </tr>

                <tr>
                    <td>5</td>
                    <td>Avatar 3</td>
                    <td>lucas123</td>
                    <td>
                        <button class="btn-edit">Accepter</button>
                        <button class="btn-delete">Refuser</button>
                    </td>
                </tr>
            </table>
        </section>

        <!-- Commentaires -->
        <section id="comments" class="admin-section">
            <h2>💬 Commentaires</h2>

            <table>
                <tr>
                    <th>ID</th>
                    <th>Utilisateur</th>
                    <th>Commentaire</th>
                    <th>Film</th>
                    <th>Actions</th>
                </tr>

                <tr>
                    <td>88</td>
                    <td>Tom75</td>
                    <td>Film incroyable !</td>
                    <td>Inception</td>
                    <td>
                        <button class="btn-delete">Supprimer</button>
                    </td>
                </tr>
            </table>
        </section>

    </main>
</div>
