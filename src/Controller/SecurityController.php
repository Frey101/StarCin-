<?php
// src/Controller/SecurityController.php
//verifie si l'utilisateur est admin 
class SecurityController {

    public static function isAdmin(): bool {
        return isset($_SESSION['utilisateur_connecte'])
            && ($_SESSION['user_role'] ?? 'user') === 'admin';
    }
//et restreint l'accès si nécessaire
    public static function restrictAccess(): void {
        if (!self::isAdmin()) {
            // Redirection vers la page de connexion
            header('Location: ' . ROOT_PATH . 'index.php?action=login&error=unauthorized');
            exit;
        }
    }
}