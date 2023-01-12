<?php

namespace App\Entity;

class PropertySearch
{

   private $nom;

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