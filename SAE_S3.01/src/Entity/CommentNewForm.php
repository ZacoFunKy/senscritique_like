<?php

namespace App\Entity;

class CommentNewForm
{

   private $serie;

   private $number;

   public function getUser(): ?User
   {
       return $this->user;
   }

    public function setUser(?User $user): self
    {
         $this->user = $user;
    
         return $this;
    }

    public function getSeries(): ?Series
    {
        return $this->serie;
    }

    public function setSeries(?Series $series): self
    {
        $this->serie = $series;

        return $this;
    }

    public function getNumber(): ?int
    {
        return $this->number;
    }

    public function setNumber(int $number): self
    {
        $this->number = $number;

        return $this;
    }


}