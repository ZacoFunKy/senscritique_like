<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ExternalRating
 *
 * @ORM\Table(name="external_rating", indexes={@ORM\Index(name="IDX_AC0AB9CB5278319C", columns={"series_id"}), @ORM\Index(name="IDX_AC0AB9CB953C1C61", columns={"source_id"})})
 * @ORM\Entity
 */
class ExternalRating
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
     * @ORM\Column(name="value", type="string", length=255, nullable=false)
     */
    private $value;

    /**
     * @var int|null
     *
     * @ORM\Column(name="votes", type="integer", nullable=true)
     */
    private $votes;

    /**
     * @var \Series
     *
     * @ORM\ManyToOne(targetEntity="Series")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="series_id", referencedColumnName="id")
     * })
     */
    private $series;

    /**
     * @var \ExternalRatingSource
     *
     * @ORM\ManyToOne(targetEntity="ExternalRatingSource")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="source_id", referencedColumnName="id")
     * })
     */
    private $source;

    /**
     * Permet d'obtenir l'id de la note
     *
     * @return ?int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Permet d'obtenir la valeur d'une note
     *
     * @return ?string
     */
    public function getValue(): ?string
    {
        return $this->value;
    }

    /**
     * Permet de définir la valeur d'une notation
     *
     *@param string $value la nouvelle valeure de la notation
     *
     * @return self
     */
    public function setValue(string $value): self
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Permet d'obtenir le nombre de votes
     *
     * @return ?int
     */
    public function getVotes(): ?int
    {
        return $this->votes;
    }

    /**
     * Permet de définir le nombre de vote
     *
     *@param ?int $votes le nombre de vote
     *
     * @return self
     */
    public function setVotes(?int $votes): self
    {
        $this->votes = $votes;

        return $this;
    }

    /**
     * Permet d'obtenir la série de la notation
     *
     * @return ?Series
     */
    public function getSeries(): ?Series
    {
        return $this->series;
    }

    /**
     * Permet de définir la série de la notation
     *
     *@param ?Series $series la nouvelle serie de la notation
     *
     * @return self
     */
    public function setSeries(?Series $series): self
    {
        $this->series = $series;

        return $this;
    }

    /**
     * Permet d'obtenir la source de la notation
     *
     * @return ?ExternalRatingSource
     */
    public function getSource(): ?ExternalRatingSource
    {
        return $this->source;
    }

    /**
     * Permet de définir la source de la notation
     *
     *@param ?ExternalRatingSource $source la nouvelle source de la notation
     *
     * @return self
     */
    public function setSource(?ExternalRatingSource $source): self
    {
        $this->source = $source;

        return $this;
    }
}
