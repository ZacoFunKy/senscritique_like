<?php

namespace App\Entity;

class PropertySearch
{

    private $nom;
    private $anneeDeSortie;
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

    public function getAnneeDeSortie(): ?string
    {
        return $this->anneeDeSortie;
    }
 
    public function setAnneeDeSortie(string $anneeDeSortie): self
    {
        $this->anneeDeSortie = $anneeDeSortie    ;
 
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