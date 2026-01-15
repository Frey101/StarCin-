<?php

class SessionVote
{
    private ?int $id_sessionvote = null;
    private string $dateDebut = '';
    private string $dateFin = '';
    private int $duree = 0;
    private int $annee = 0;
    private ?int $id_admin = null;

    public function getIdSessionvote(): ?int
    {
        return $this->id_sessionvote;
    }

    public function setIdSessionvote(int $id): void
    {
        $this->id_sessionvote = $id;
    }

    public function getDateDebut(): string
    {
        return $this->dateDebut;
    }

    public function setDateDebut(string $date): void
    {
        $this->dateDebut = $date;
    }

    public function getDateFin(): string
    {
        return $this->dateFin;
    }

    public function setDateFin(string $date): void
    {
        $this->dateFin = $date;
    }

    public function getDuree(): int
    {
        return $this->duree;
    }

    public function setDuree(int $duree): void
    {
        $this->duree = $duree;
    }

    public function getAnnee(): int
    {
        return $this->annee;
    }

    public function setAnnee(int $annee): void
    {
        $this->annee = $annee;
    }

    public function getIdAdmin(): ?int
    {
        return $this->id_admin;
    }

    public function setIdAdmin(int $id): void
    {
        $this->id_admin = $id;
    }
}

