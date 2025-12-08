<?php
// src/Model/UserModel.php

use src\Entity\User;

require_once __DIR__ . '/../Database/DBConnection.php';
require_once __DIR__ . '/../Entity/User.php';



class UserModel {
    private $pdo;

    public function __construct() {
        $this->pdo = DBConnection::getInstance()->getPDO();
    }

    public function findByEmail(string $email): ?User {
        $requete = $this->pdo->prepare("SELECT id_utilisateur, email, mot_de_passe, role FROM utilisateur WHERE email = :email");
        $requete->execute([':email' => $email]);
        $data = $requete->fetch(PDO::FETCH_ASSOC);

        if (!$data) {
            return null;
        }

        $user = new User();
        $user->setIdUtilisateur((int)$data['id_utilisateur']);
        $user->setEmail($data['email']);
        $user->setMotDePasse($data['mot_de_passe']);
        $user->setRole($data['role']);

        return $user;
    }
}


