<?php

namespace App\Entity;

class userCreateForm
{

   private $name;
   private $email;
   private $number;

    public function getName(): ?string
    {
         return $this->name;
    }

    public function setName(?string $name): self
    {
         $this->name = $name;

         return $this;
    }

    public function getEmail(): ?string
    {
         return $this->email;
    }

    public function setEmail(?string $email): self
    {
         $this->email = $email;

         return $this;
    }

    public function getNumber(): ?int
    {
         return $this->number;
    }

    public function setNumber(?int $number): self
    {
         $this->number = $number;

         return $this;
    }

}