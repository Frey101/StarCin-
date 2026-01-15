<?php
// controllers/AdminController.php

require_once ROOT_DIR . 'src/Model/AdminModel.php';

class AdminController
{
    private AdminModel $model;

    public function __construct()
    { 
        // Sécurité : accès admin uniquement
        if (!isset($_SESSION['utilisateur_connecte']) || ($_SESSION['user_role'] ?? 'user') !== 'admin') {
            header('Location: ' . ROOT_PATH . 'index.php?action=login&error=unauthorized');
            exit;
        }

        $this->model = new AdminModel();
    }

    public function dashboard(): void
    {
        $data = [
            'stats' => [
                'films' => $this->model->countFilms(),
                'users' => $this->model->countUsers(),
                'propositions' => $this->model->countPropositions(),
                'votes' => $this->model->countVotes(),
            ],
            'films' => $this->model->getFilms(),
            'users' => $this->model->getUsers(),
            'propositions' => $this->model->getPropositions(),
            'comments' => $this->model->getComments(),
        ];

        require __DIR__ . '/../views/admin/dashboard.php';
    }

    /* ===== ACTIONS ===== */

    public function deleteFilm(int $id): void
    {
        $this->model->deleteFilm($id);
        header('Location: /admin');
    }

    public function promoteUser(int $id): void
    {
        $this->model->promoteUser($id);
        header('Location: /admin#users');
    }

    public function acceptProposition(int $id): void
    {
        $this->model->updateProposition($id, 'acceptee');
        header('Location: /admin#propositions');
    }

    public function refuseProposition(int $id): void
    {
        $this->model->updateProposition($id, 'refusee');
        header('Location: /admin#propositions');
    }

    public function deleteComment(int $id): void
    {
        $this->model->deleteComment($id);
        header('Location: /admin#comments');
    }
}
