<?php

namespace App\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Genre
 *
 * @ORM\Table(name="genre")
 * @ORM\Entity
 */
class Genre
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
     * @ORM\ManyToMany(targetEntity="Series", inversedBy="genre")
     * @ORM\JoinTable(name="genre_series",
     *   joinColumns={
     *     @ORM\JoinColumn(name="genre_id", referencedColumnName="id")
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
     * Permet d'obtenir l'id du genre
     *
     * @return ?int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Permet d'obtenir le nom du genre
     *
     * @return ?string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Permet de définir le nom d'un genre
     *
     * @param string $name le nom du genre
     *
     * @return self
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Permet d'obtenir la liste des séries qui ont ce lien
     *
     * @return Collection<int, Series>
     */
    public function getSeries(): Collection
    {
        return $this->series;
    }

    /**
     * Permet d'ajouter une série à la liste des séries possédent ce genre
     *
     *@param Series $series ajoute une série à la liste des séries
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
     * Permet de supprimer une série de la liste
     *
     *@param Series $series la série à supprimer
     *
     * @return self
     */
    public function removeSeries(Series $series): self
    {
        $this->series->removeElement($series);

        return $this;
    }
}
