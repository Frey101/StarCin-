<?php

namespace src\Entity;
class User
{
    private $id_utilisateur;
    private $email;
    private $mot_de_passe; // Le mot de passe haché

    // Getters
    public function getIdUtilisateur()
    {
        return $this->id_utilisateur;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getMotDePasse()
    {
        return $this->mot_de_passe;
    }


    public function setIdUtilisateur(int $id)
    {
        $this->id_utilisateur = $id;
    }

    public function setEmail(string $email)
    {
        $this->email = $email;
    }

    public function setMotDePasse(string $mdp_hache)
    {
        $this->mot_de_passe = $mdp_hache;
    }

    public function setRole($role)
    {
    }

    public function getRole()
    {
    }
}

?>