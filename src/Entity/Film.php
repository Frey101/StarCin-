<?php


class Film
{ 
    private ?int $id_film = null;
    private string $titre;
    private string $synopsis;
    private int $duree;
    private ?string $bandeannonce = null;
    private ?string $datediffusion = null; // format Y-m-d
    private ?string $categorie = null;
    private ?int $annee = null;

    /* ===== GETTERS ===== */

    public function getIdFilm(): ?int
    {
        return $this->id_film;
    }

    public function getTitre(): string
    {
        return $this->titre;
    }

    public function getSynopsis(): string
    {
        return $this->synopsis;
    }

    public function getDuree(): int
    {
        return $this->duree;
    }

    public function getBandeAnnonce(): ?string
    {
        return $this->bandeannonce;
    }

    public function getDateDiffusion(): ?string
    {
        return $this->datediffusion;
    }

    public function getCategorie(): ?string
    {
        return $this->categorie;
    }

    public function getAnnee(): ?int
    {
        return $this->annee;
    }

    /* ===== SETTERS ===== */

    public function setIdFilm(int $id): void
    {
        $this->id_film = $id;
    }

    public function setTitre(string $titre): void
    {
        $this->titre = $titre;
    }

    public function setSynopsis(string $synopsis): void
    {
        $this->synopsis = $synopsis;
    }

    public function setDuree(int $duree): void
    {
        $this->duree = $duree;
    }

    public function setBandeAnnonce(?string $url): void
    {
        $this->bandeannonce = $url;
    }

    public function setDateDiffusion(?string $date): void
    {
        $this->datediffusion = $date;
    }

    public function setCategorie(?string $categorie): void
    {
        $this->categorie = $categorie;
    }

    public function setAnnee(?int $annee): void
    {
        $this->annee = $annee;
    }
}

