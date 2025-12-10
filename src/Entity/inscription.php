<?php
// DOIT ÊTRE LA PREMIÈRE INSTRUCTION
session_start();


include 'bdd.php';

$message_erreur = '';

// 2. Traitement du formulaire POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = trim($_POST['email'] ?? '');
    $mot_de_passe = $_POST['mdp'] ?? '';
    $confirmation_mdp = $_POST['mdp_confirm'] ?? '';

    // --- Validation des données ---
    if (empty($email) || empty($mot_de_passe) || empty($confirmation_mdp)) {
        $message_erreur = 'Veuillez remplir tous les champs.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message_erreur = 'L\'adresse e-mail n\'est pas valide.';
    } elseif ($mot_de_passe !== $confirmation_mdp) {
        $message_erreur = 'Les mots de passe ne correspondent pas.';
    } elseif (strlen($mot_de_passe) < 8) {
        $message_erreur = 'Le mot de passe doit contenir au moins 8 caractères.';
    } else {
        // --- 4. Vérification si l'utilisateur existe déjà ---
        try {
            $requete_check = $bdd->prepare("SELECT COUNT(id_utilisateur) FROM utilisateur WHERE email = :email");
            $requete_check->bindParam(':email', $email, PDO::PARAM_STR);
            $requete_check->execute();
            $existe = $requete_check->fetchColumn();

            if ($existe > 0) {
                $message_erreur = 'Cette adresse e-mail est déjà utilisée.';
            } else {

                // --- 5. Hachage du mot de passe ---
                $mot_de_passe_hache = password_hash($mot_de_passe, PASSWORD_DEFAULT);

                // --- 6. Insertion du nouvel utilisateur dans la BDD ---
                $requete_insert = $bdd->prepare(
                    "INSERT INTO utilisateur (email, mot_de_passe, nom, prenom) VALUES (:email, :mot_de_passe, '', '')"
                );

                $requete_insert->bindParam(':email', $email, PDO::PARAM_STR);
                $requete_insert->bindParam(':mot_de_passe', $mot_de_passe_hache, PDO::PARAM_STR);

                if ($requete_insert->execute()) {

                    // Connexion automatique et REDIRECTION IMMÉDIATE
                    $_SESSION['utilisateur_connecte'] = true;
                    $_SESSION['user_id'] = $bdd->lastInsertId();
                    $_SESSION['email'] = $email;

                    header('Location: vote.php');
                    exit;

                } else {
                    $message_erreur = 'Erreur lors de l\'enregistrement. Veuillez réessayer.';
                }
            }
        } catch (PDOException $e) {
            $message_erreur = 'Une erreur interne de base de données est survenue.';
        }
    }
}
?>



<?php
include 'header.php';
?>

    <main style="padding: 20px;">




        <h2>Créer un compte StarCiné</h2>

        <?php
        // Affichage des messages d'erreur
        if (!empty($message_erreur)) {
            echo '<p style="color: red; border: 1px solid red; padding: 10px;">' . htmlspecialchars($message_erreur) . '</p>';
        }
        ?>

        <form action="inscription.php" method="POST">
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
            <p>Déjà un compte ? <a href="../../views/connexion.php">Se connecter</a></p>
        </form>
    </main>

<?php
include 'footer.php';
?>