<?php

namespace App\Entity;

class CommentNewForm
{


   private $number;


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