<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * Rating
 *
 * @ORM\Table(name="rating", indexes={@ORM\Index(name="IDX_D8892622A76ED395", columns={"user_id"}), @ORM\Index(name="IDX_D88926225278319C", columns={"series_id"})})
 * @ORM\Entity
 */
class Rating
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
     * @var float
     *
     *
     * @ORM\Column(name="value", type="float", nullable=false)
     */
    private $value;



     /**
     * @var bool
     *
     * @ORM\Column(name="verified", type="boolean", nullable=true)
     */
    private $verified = 0;


    /**
     * @var string|null
     *
     * @ORM\Column(name="comment", type="text", length=0, nullable=true)
     */
    private $comment;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime", nullable=false)
     */
    private $date;

    /**
     * @var \User
     *
     * @ORM\ManyToOne(targetEntity="User", inversedBy="rating")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * })
     */
    private $user;

    /**
     * @var \Series
     *
     * @ORM\ManyToOne(targetEntity="Series", inversedBy="rating")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="series_id", referencedColumnName="id")
     * })
     */
    private $series;

    /**
     * Permet d'obtenir l'id de la notation
     *
     * @return ?int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Permet d'obtenir la valeur de la notation
     *
     * @return ?string
     */
    public function getValue(): ?float
    {
        return $this->value;
    }

    /**
     * Permet de définir la valeur de la notation
     *
     * @param float $value la valeur de la notation
     *
     * @return self
     */
    public function setValue(float $value): self
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Permet d'obtenir les commentaires de la notation
     *
     * @return ?string
     */
    public function getComment(): ?string
    {
        return $this->comment;
    }

    /**
     * Permet de définir le commentaire de la notation
     *
     * @param ?string $comment le commentaire de la notation
     *
     * @return self
     */
    public function setComment(?string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * Permet d'obtenir la date de la notation
     *
     * @return ?\DateTimeInterface
     */
    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    /**
     * Permet de définir la date de la notation
     *
     * @param \DateTimeInterface $date la date de la notation
     *
     * @return self
     */
    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Permet d'obtenir l'utilisateur ayant publié la notation
     *
     * @return ?User
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * Permet de définir l'utilisateur de la notation
     *
     * @param ?User $user lutilisateur de la notation
     *
     * @return self
     */
    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Permet d'obtenir la série qui a été noté
     *
     * @return ?Series
     */
    public function getSeries(): ?Series
    {
        return $this->series;
    }

    /**
     * Permet de définir la série qui a été noté
     *
     * @param ?Series $series la série noté
     *
     * @return self
     */
    public function setSeries(?Series $series): self
    {
        $this->series = $series;

        return $this;
    }

    /**
     * Permet d'obtenir l'état de la vérification du commentaire
     *
     * @return ?bool
     */
    public function getVerified(): ?bool
    {
        return $this->verified;
    }

    /**
     * Permet de définir l'état du commentaire
     * @param bool $verified vrai si le commentaire est vérifié, sinon faux
     *
     * @return self
     */
    public function setVerified(bool $verified): self
    {
        $this->verified = $verified;

        return $this;
    }

    /**
     * Permet de d'obtenir le nom de l'utilisateur qui à créer la notation
     *
     * @return ?string
     */
    public function getUserName(): ?string
    {
        return $this->user->getName();
    }

    /**
     * Permet de définir le nom de la série qui a été noté
     *
     * @return ?string
     */
    public function getSeriesName(): ?string
    {
        return $this->series->getTitle();
    }
}
