<?php
// src/Controller/InscriptionController.php

require_once __DIR__ . '/../Database/DBConnection.php';

class InscriptionController {
    public function handleRequest() {
        $bdd = DBConnection::getInstance()->getPDO();
        $message_erreur = '';

        // Traitement du formulaire POST
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // CSRF check
            if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
                $message_erreur = 'Token CSRF invalide.';
            } else {
                $nom = trim($_POST['nom'] ?? '');
                $prenom = trim($_POST['prenom'] ?? '');
                $email = trim($_POST['email'] ?? '');
                $mot_de_passe = $_POST['mdp'] ?? '';
                $confirmation_mdp = $_POST['mdp_confirm'] ?? '';

            // Validation des données
            if (empty($nom) || empty($prenom) || empty($email) || empty($mot_de_passe) || empty($confirmation_mdp)) {
                $message_erreur = 'Veuillez remplir tous les champs.';
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $message_erreur = 'L\'adresse e-mail n\'est pas valide.';
            } elseif ($mot_de_passe !== $confirmation_mdp) {
                $message_erreur = 'Les mots de passe ne correspondent pas.';
            } elseif (strlen($mot_de_passe) < 8) {
                $message_erreur = 'Le mot de passe doit contenir au moins 8 caractères.';
            } else {
                // Vérification si l'utilisateur existe déjà
                try {
                    $requete_check = $bdd->prepare("SELECT COUNT(id_utilisateur) FROM utilisateur WHERE email = :email");
                    $requete_check->bindParam(':email', $email, PDO::PARAM_STR);
                    $requete_check->execute();
                    $existe = $requete_check->fetchColumn();

                    if ($existe > 0) {
                        $message_erreur = 'Cette adresse e-mail est déjà utilisée.';
                    } else {
                        // Hachage du mot de passe
                        $mot_de_passe_hache = password_hash($mot_de_passe, PASSWORD_DEFAULT);

                        // Insertion du nouvel utilisateur dans la BDD
                        $requete_insert = $bdd->prepare(
                            "INSERT INTO utilisateur (email, mot_de_passe, nom, prenom, role) VALUES (:email, :mot_de_passe, :nom, :prenom, 'user')"
                        );

                        $requete_insert->bindParam(':email', $email, PDO::PARAM_STR);
                        $requete_insert->bindParam(':mot_de_passe', $mot_de_passe_hache, PDO::PARAM_STR);
                        $requete_insert->bindParam(':nom', $nom, PDO::PARAM_STR);
                        $requete_insert->bindParam(':prenom', $prenom, PDO::PARAM_STR);

                        if ($requete_insert->execute()) {
                            // Connexion automatique et REDIRECTION IMMÉDIATE
                            $_SESSION['utilisateur_connecte'] = true;
                            $_SESSION['user_id'] = $bdd->lastInsertId();
                            $_SESSION['email'] = $email;
                            $_SESSION['user_role'] = 'user';

                            header('Location: ' . ROOT_PATH . 'index.php?action=vote_page');
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
        }

        // Affichage de la vue
        require ROOT_DIR . 'views/layout/header.php';
        require ROOT_DIR . 'views/inscription.php';
        require ROOT_DIR . 'views/layout/footer.php';
    }
}
?>