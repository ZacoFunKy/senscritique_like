<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ExternalRatingSource
 *
 * @ORM\Table(name="external_rating_source")
 * @ORM\Entity
 */
class ExternalRatingSource
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     */
    private $name;

    /**
     * Permet d'obtenir l'id de la source qui fait la notation
     *
     * @return ?int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Permet d'obtenir le nom de la source qui fait la notation
     *
     * @return ?string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Permet de définir le nom de la source de la notation
     *
     *@param string $name le nom de la source de la notation
     *
     * @return self
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }
}
