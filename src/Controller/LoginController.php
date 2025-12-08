<?php
// src/Controller/LoginController.php

// Les chemins d'inclusion utilisent __DIR__ pour remonter au dossier src/
require_once __DIR__ . '/../Model/UserModel.php';
require_once __DIR__ . '/../Entity/User.php';

class LoginController {
    private $userModel;

    public function __construct() {
        // Le constructeur initialise le Modèle pour accéder à la base de données
        $this->userModel = new UserModel();
    }

    /**
     * Gère toutes les requêtes entrantes (GET ou POST) pour les actions login et logout.
     */
    public function handleRequest(string $action): void {
        $message_erreur = null;

        // 1. GESTION DE LA DÉCONNEXION (LOGOUT)
        if ($action === 'logout') {
            session_destroy();
            // Redirection vers l'accueil après déconnexion
            header('Location: ' . ROOT_PATH . 'index.php?action=home');
            exit;
        }

        // 2. GESTION DE LA CONNEXION (LOGIN - Soumission du Formulaire POST)
        if ($action === 'login' && $_SERVER["REQUEST_METHOD"] === "POST") {
            $email = trim($_POST['email'] ?? '');
            $mdp = $_POST['mdp'] ?? '';

            if (!empty($email) && !empty($mdp)) {
                $result = $this->processLogin($email, $mdp);

                // Si la connexion réussit, le Modèle retourne une URL de redirection
                if (isset($result['redirect'])) {
                    header('Location: ' . $result['redirect']);
                    exit;
                }

                // Si la connexion échoue, le message est stocké pour l'affichage
                $message_erreur = $result['message'];
            } else {
                $message_erreur = 'Veuillez remplir tous les champs.';
            }
        }

        // 3. AFFICHAGE DE LA VUE (Si c'est un GET ou si le POST a échoué)
        // Les Vues sont incluses en utilisant ROOT_DIR (chemin du système de fichiers)
        require ROOT_DIR . 'views/layout/header.php';
        require ROOT_DIR . 'views/connexion.php';
        require ROOT_DIR . 'views/layout/footer.php';
    }

    /**
     * Tente d'authentifier l'utilisateur.
     */
    private function processLogin(string $email, string $mot_de_passe_saisi): array {
        // 1. Cherche l'utilisateur dans la base de données
        $user = $this->userModel->findByEmail($email);

        // 2. Vérifie le mot de passe (si l'utilisateur existe)
        if ($user && password_verify($mot_de_passe_saisi, $user->getMotDePasse())) {

            // Connexion réussie : Initialisation de la session
            $_SESSION['utilisateur_connecte'] = true;
            $_SESSION['user_id'] = $user->getIdUtilisateur();
            $_SESSION['email'] = $user->getEmail();
            $_SESSION['user_role'] = $user->getRole(); // Stockage du rôle pour la sécurité

            // Détermine la redirection (Admin vers Dashboard, Utilisateur normal vers Accueil)
            $redirect_url = ($user->getRole() === 'admin') ?
                ROOT_PATH . 'index.php?action=admin_dashboard' :
                ROOT_PATH . 'index.php?action=home';

            return ['redirect' => $redirect_url];

        } else {
            // Échec de la connexion
            return ['message' => 'Adresse e-mail ou mot de passe incorrect.'];
        }
    }
}