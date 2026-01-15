<?php
require_once ROOT_DIR . 'src/Service/ResultatService.php';
require_once ROOT_DIR . 'src/Repository/AdminRepository.php';

/**
 * Contrôleur pour l'affichage des résultats de vote
 * Respecte le principe SRP : gère uniquement l'affichage des résultats
 */
class ResultatController
{
    private ResultatService $resultatService;
    private AdminRepository $repository;

    public function __construct()
    {
        $this->resultatService = new ResultatService();
        $this->repository = new AdminRepository();
    }

    public function showResults(): void
    {
        // Récupérer les années disponibles
        $availableYears = $this->resultatService->getAvailableYears();
        
        // Récupérer les résultats par année
        $resultsByYear = [];
        foreach ($availableYears as $year) {
            $results = $this->resultatService->getResultsByYear((int)$year);
            if (!empty($results)) {
                // Ajouter les chemins d'images
                foreach ($results as &$result) {
                    if (isset($result['film']['id_film'])) {
                        $result['film']['image'] = $this->repository->getFilmImagePath($result['film']['id_film']);
                    }
                }
                $resultsByYear[$year] = $results;
            }
        }

        // Passer les variables à la vue
        $resultsByYear = $resultsByYear;
        $availableYears = $availableYears;

        require ROOT_DIR . 'views/layout/header.php';
        require ROOT_DIR . 'views/resultat.php';
        require ROOT_DIR . 'views/layout/footer.php';
    }
}

