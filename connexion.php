<?php
// DOIT ÊTRE LA PREMIÈRE INSTRUCTION
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


// Inclure le fichier de connexion à la BDD
include 'bdd.php';

$message_erreur = '';

// 2. Traitement du formulaire POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = trim($_POST['email'] ?? '');
    $mot_de_passe = $_POST['mdp'] ?? '';

    if (!empty($email) && !empty($mot_de_passe)) {

        try {
            // Requête PRÉPARÉE : Sélectionne l'utilisateur par email
            // ATTENTION : Remplacement de 'password' par 'mot_de_passe'
            $requete = $bdd->prepare("SELECT id_utilisateur, email, mot_de_passe FROM utilisateur WHERE email = :email");

            $requete->bindParam(':email', $email, PDO::PARAM_STR);
            $requete->execute();

            $utilisateur = $requete->fetch();

            if ($utilisateur) {
                // Vérification du mot de passe : mot de passe tapé vs mot de passe HACHÉ
                // ATTENTION : Utilisation de $utilisateur['mot_de_passe']
                if (password_verify($mot_de_passe, $utilisateur['mot_de_passe'])) {

                    // Connexion réussie
                    $_SESSION['utilisateur_connecte'] = true;
                    $_SESSION['user_id'] = $utilisateur['id_utilisateur']; // Utilisation de id_utilisateur
                    $_SESSION['email'] = $utilisateur['email'];

                    header('Location: index.php'); // Redirection vers l'accueil après connexion
                    exit;

                } else {
                    $message_erreur = 'Adresse e-mail ou mot de passe incorrect.';
                }
            } else {
                $message_erreur = 'Adresse e-mail ou mot de passe incorrect.';
            }

        } catch (PDOException $e) {
            $message_erreur = 'Une erreur interne est survenue. Veuillez réessayer.';
        }
    } else {
        $message_erreur = 'Veuillez remplir tous les champs.';
    }
}

// ----------------------------------------------------------------------
// Inclusion du header APRÈS le traitement du POST pour éviter les erreurs de headers
// ----------------------------------------------------------------------
?>

<?php
include 'header.php';
?>

    <main >
        <h2>Connexion à StarCiné</h2>

        <?php
        if (!empty($message_erreur)) {
            echo '<p style="color: red; border: 1px solid red; padding: 10px;">' . htmlspecialchars($message_erreur) . '</p>';
        }
        ?>

        <form action="connexion.php" method="POST">
            <div>
                <label for="email">Adresse e-mail :</label><br>
                <input type="email" id="email" name="email" required>
            </div>
            <br>
            <div>
                <label for="mdp">Mot de passe :</label><br>
                <input type="password" id="mdp" name="mdp" required>
            </div>
            <br>
            <button type="submit">Se connecter</button>
            <p>Pas encore de compte ? <a href="inscription.php">Créer un compte</a></p>
        </form>
    </main>

<?php
include 'footer.php';
?>