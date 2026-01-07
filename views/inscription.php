
    <main>
        <h2>Créer un compte StarCiné</h2>

        <?php
        // Affichage des messages d'erreur
        if (!empty($message_erreur)) {
            echo '<p style="color: red; border: 1px solid red; padding: 10px;">' . htmlspecialchars($message_erreur) . '</p>';
        }
        ?>

        <form action="<?= ROOT_PATH ?>index.php?action=inscription" method="POST">
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
            <div>
                <label for="nom">Nom :</label><br>
                <input type="text" id="nom" name="nom" required>
            </div>
            <br>
            <div>
                <label for="prenom">Prénom :</label><br>
                <input type="text" id="prenom" name="prenom" required>
            </div>
            <br>
            <div>
                <label for="email">Adresse e-mail :</label><br>
                <input type="email" id="email" name="email" value="<?= htmlspecialchars($email ?? '') ?>" required>
            </div>
            <br>
            <div>
                <label for="mdp">Mot de passe :</label><br>
                <input type="password" id="mdp" name="mdp" required>
            </div>
            <br>
            <div>
                <label for="mdp_confirm">Confirmer mot de passe :</label><br>
                <input type="password" id="mdp_confirm" name="mdp_confirm" required>
            </div>
            <br>
            <button type="submit">S'inscrire</button>
            <div class="connex">
            <p>Deja un compte ? </p>
                <button><a href="<?= ROOT_PATH ?>index.php?action=login">Se connecter</a></button>
            </div>


        </form>
    </main>
