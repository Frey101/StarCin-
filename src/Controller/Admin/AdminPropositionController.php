<?php
// src/Controller/Admin/AdminPropositionController.php

require_once ROOT_DIR . 'src/Controller/SecurityController.php';
require_once ROOT_DIR . 'src/Service/PropositionFilmService.php';

/**
 * Contrôleur pour la gestion des propositions en admin
 * Respecte le principe SRP : gère uniquement les actions liées aux propositions
 */
class AdminPropositionController
{
    private PropositionFilmService $propositionService;

    public function __construct()
    {
        SecurityController::restrictAccess();
        $this->propositionService = new PropositionFilmService();
    }

    /**
     * Liste toutes les propositions
     */
    public function listPropositions(): void
    {
        $propositions = $this->propositionService->getAllPropositions();
        require ROOT_DIR . 'views/layout/header.php';
        require ROOT_DIR . 'views/admin/proposition_list.php';
        // Footer déjà inclus dans proposition_list.php
    }

    /**
     * Accepte une proposition
     */
    public function acceptProposition(): void
    {
        if (isset($_GET['id']) && is_numeric($_GET['id'])) {
            $id = (int)$_GET['id'];
            if ($this->propositionService->acceptProposition($id)) {
                header('Location: ' . ROOT_PATH . 'index.php?action=admin_propositions&status=accepted');
            } else {
                header('Location: ' . ROOT_PATH . 'index.php?action=admin_propositions&status=error');
            }
            exit;
        }
        header('Location: ' . ROOT_PATH . 'index.php?action=admin_propositions');
        exit;
    }

    /**
     * Refuse une proposition
     */
    public function refuseProposition(): void
    {
        if (isset($_GET['id']) && is_numeric($_GET['id'])) {
            $id = (int)$_GET['id'];
            if ($this->propositionService->refuseProposition($id)) {
                header('Location: ' . ROOT_PATH . 'index.php?action=admin_propositions&status=refused');
            } else {
                header('Location: ' . ROOT_PATH . 'index.php?action=admin_propositions&status=error');
            }
            exit;
        }
        header('Location: ' . ROOT_PATH . 'index.php?action=admin_propositions');
        exit;
    }
}

