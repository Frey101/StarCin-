<?php
// src/Service/IAdminStatsService.php

/**
 * Interface pour le service de statistiques admin
 * Respecte le principe ISP (Interface Segregation)
 */
interface IAdminStatsService
{
    public function getDashboardStats(): array;
    public function getFilmsStats(): array;
    public function getUsersStats(): array;
}

