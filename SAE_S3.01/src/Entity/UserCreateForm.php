<?php

namespace App\Entity;

class userCreateForm
{

   private $name;
   private $email;
   private $number;

     /**
     * Permet d'obtenir le nom de l'utilisateur dans le formulaire
     *
     * @return ?string
     */
    public function getName(): ?string
    {
         return $this->name;
    }

    /**
     * Permet de définir le nom de l'utilisateur dans le formulaire
     *
     * @param ?string $name le nom de l'utilisateur
     *
     * @return ?string
     */
    public function setName(?string $name): self
    {
         $this->name = $name;

         return $this;
    }

    /**
     * Permet d'obtenir l'email de l'utilisateur dans le formulaire
     *
     * @return ?string
     */
    public function getEmail(): ?string
    {
         return $this->email;
    }

    /**
     * Permet de définir l'email de l'utilisateur dans le formulaire
     *
     * @param ?string $email le nouvel email de l'utilisateur
     *
     * @return self
     */
    public function setEmail(?string $email): self
    {
         $this->email = $email;

         return $this;
    }

    /**
     * Permet d'obtenir le nombre d'utilisateur à créer dans le formulaire
     *
     * @return ?int
     */
    public function getNumber(): ?int
    {
         return $this->number;
    }

    /**
     * Permet de définir le nombre d'utilisateur dans le formulaire
     *
     * @param ?int $number le nombre d'utilisateur à créer
     *
     * @return self
     */
    public function setNumber(?int $number): self
    {
         $this->number = $number;

         return $this;
    }

}