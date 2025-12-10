
    <main style="padding: 20px;">



        <h2>Créer un compte StarCiné</h2>

        <?php
        // Affichage des messages d'erreur
        if (!empty($message_erreur)) {
            echo '<p style="color: red; border: 1px solid red; padding: 10px;">' . htmlspecialchars($message_erreur) . '</p>';
        }
        ?>

        <form action="<?= ROOT_PATH ?>index.php?action=inscription" method="POST">
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
            <a href="<?= ROOT_PATH ?>index.php?action=login">Se connecter</a>
        </form>
    </main>
