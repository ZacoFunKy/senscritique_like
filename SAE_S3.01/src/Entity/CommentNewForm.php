<?php

namespace App\Entity;

class CommentNewForm
{
   private $number;

    /**
     * Permet de récupérer le nombre de faux commentaires générer
     *
     * @return ?int
     */
    public function getNumber(): ?int
    {
        return $this->number;
    }

    /**
     * Permet de définir le nombre de faux commentaires.
     *
     * @param int $number le nombre de faux commentaires
     *
     * @return self
     */
    public function setNumber(int $number): self
    {
        $this->number = $number;

        return $this;
    }


}