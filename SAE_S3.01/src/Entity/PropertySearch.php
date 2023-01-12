<?php

namespace App\Entity;

class PropertySearch
{

    private $nom;
    private $anneeDepart;
    private $anneeFin;
    private $genre;
    private $avis;
   
    public function getNom(): ?string
    {
        return $this->nom;
    }
 
    public function setNom(string $nom): self
    {
        $this->nom = $nom    ;
 
        return $this;
    }

    public function getAnneeDepart(): ?int
    {
        return $this->anneeDepart;
    }
 
    public function setAnneeDepart(int $anneeDepart): self
    {
        $this->anneeDepart = $anneeDepart    ;
 
        return $this;
    }

    public function getAnneeFin(): ?int
    {
        return $this->anneeFin;
    }
 
    public function setAnneeFin(int $anneeFin): self
    {
        $this->anneeFin = $anneeFin    ;
 
        return $this;
    }

    public function getGenre(): ?string
    {
        return $this->genre;
    }
 
    public function setGenre(string $genre): self
    {
        $this->genre = $genre;
 
        return $this;
    }
    public function getAvis(): ?string
    {
         return $this->avis;
    }

    public function setAvis(string $avis): self
    {
        $this->avis = $avis;

        return $this;
    }




}