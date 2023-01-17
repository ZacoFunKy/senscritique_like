<?php

namespace App\Entity;

class UserSearch
{

   private $nom;

    /**
     * Permet d'obtenir le nom de l'utilisateur dans la barre de recherche
     *
     * @return ?string
     */
   public function getNom(): ?string
   {
       return $this->nom;
   }

   /**
     * Permet d'obtenir le nom de l'utilisateur dans le formulaire
     *
     * @param string $nom le nom de l'utilisateur dans la barre de recherche
     *
     * @return self
     */
   public function setNom(string $nom): self
   {
       $this->nom = $nom;

       return $this;
   }
}