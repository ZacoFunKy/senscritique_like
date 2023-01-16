<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Season
 *
 * @ORM\Table(name="season", indexes={@ORM\Index(name="IDX_F0E45BA95278319C", columns={"series_id"})})
 * @ORM\Entity
 */
class Season
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
     * @var int
     *
     * @ORM\Column(name="number", type="integer", nullable=false)
     */
    private $number;

    /**
     * @var \Series
     *
     * @ORM\ManyToOne(targetEntity="Series", inversedBy="seasons")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="series_id", referencedColumnName="id")
     * })
     */
    private $series;

     /**
     * @var \Episode
     *
     * @ORM\OneToMany(targetEntity="Episode", mappedBy="season")
     */
    private $episodes;

    public function __construct()
    {
        $this->episodes = new ArrayCollection();
    }

    /**
     * Permet d'obtenir l'id de la saison
     *
     * @return ?int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Permet d'obtenir le numéro de la saison
     *
     * @return ?int
     */
    public function getNumber(): ?int
    {
        return $this->number;
    }

    /**
     * Permet de définir le numéro de la saison
     *
     * @param int $number le numéro de la saison
     *
     * @return self
     */
    public function setNumber(int $number): self
    {
        $this->number = $number;

        return $this;
    }

    /**
     * Permet d'obtenir la série lié à la saison
     *
     * @return ?Series
     */
    public function getSeries(): ?Series
    {
        return $this->series;
    }

    /**
     * Permet de définir la série de la saison
     *
     * @param ?Series $series la série lié à la saison
     *
     * @return self
     */
    public function setSeries(?Series $series): self
    {
        $this->series = $series;

        return $this;
    }

    /**
     * Permet d'obtenir la collection d'épisode de la saison
     *
     * @return Collection<int, Episode>
     */
    public function getEpisodes(): Collection
    {
        return $this->episodes;
    }

    /**
     * Permet d'ajouter un épisode à la liste des épisodes de la saison
     *
     * @param Episode $episode l'épisode à ajouter à la liste
     *
     * @return self
     */
    public function addEpisode(Episode $episode): self
    {
        if (!$this->episodes->contains($episode)) {
            $this->episodes->add($episode);
            $episode->setSeason($this);
        }

        return $this;
    }

    /**
     * Permet de supprimer un épisode de la liste des épisodes de la saison
     *
     * @param Episode $episode
     *
     * @return self
     */
    public function removeEpisode(Episode $episode): self
    {
        if ($this->episodes->removeElement($episode)) {
            // set the owning side to null (unless already changed) REVOIR
            if ($episode->getSeason() === $this) {
                $episode->setSeason(null);
            }
        }
        return $this;
    }


}
