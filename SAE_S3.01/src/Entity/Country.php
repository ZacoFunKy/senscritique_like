<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Country
 *
 * @ORM\Table(name="country")
 * @ORM\Entity
 */
class Country
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
     * @ORM\Column(name="name", type="string", length=128, nullable=false)
     */
    private $name;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Series", inversedBy="country")
     * @ORM\JoinTable(name="country_series",
     *   joinColumns={
     *     @ORM\JoinColumn(name="country_id", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="series_id", referencedColumnName="id")
     *   }
     * )
     */
    private $series = array();

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->series = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Permet de récupérer l'id d'un pays
     *
     * @return ?int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Permet de récupérer le nom d'un pays
     *
     * @return self
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Permet de définir le nom d'un pays
     *
     * @param string $name le nom du pays
     *
     * @return self
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Permet d'obtenir la liste des séries liés au pays
     *
     * @return Collection<int, Series>
     */
    public function getSeries(): Collection
    {
        return $this->series;
    }

    /**
     * Permet d'ajouter une série à la liste des series du pays
     *
     * @param Series $series la série à ajouter
     *
     * @return self
     */
    public function addSeries(Series $series): self
    {
        if (!$this->series->contains($series)) {
            $this->series->add($series);
        }

        return $this;
    }

    /**
     * Permet de supprimer une série à la liste des series du pays
     *
     * @param Series $series La série à supprimer
     *
     * @return self
     */
    public function removeSeries(Series $series): self
    {
        $this->series->removeElement($series);

        return $this;
    }

    public function __toString(): string
    {
        return $this->getName();
    }
}
