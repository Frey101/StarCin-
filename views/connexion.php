<main >
        <h2>Connexion à StarCiné</h2>

        <?php
        if (!empty($message_erreur)) {
            echo '<p style="color: red; border: 1px solid red; padding: 10px;">' . htmlspecialchars($message_erreur) . '</p>';
        }
        ?>

        <form action="index.php?action=login" method="POST">
            <div>
                <label for="email">Adresse e-mail :</label><br>
                <input type="email" id="email" name="email" required>
            </div>
            <br>
            <div>
                <label for="mdp">Mot de passe :</label><br>
                <input type="password" id="mdp" name="mdp" required>
            </div>

            <button type="submit">Se connecter</button>
        </form>
    </main>


