<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * Episode
 *
 * @ORM\Table(name="episode", indexes={@ORM\Index(name="IDX_DDAA1CDA4EC001D1", columns={"season_id"})})
 * @ORM\Entity
 */
class Episode
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
     * @ORM\Column(name="title", type="string", length=128, nullable=false)
     */
    private $title;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="date", type="date", nullable=true)
     */
    private $date;

    /**
     * @var string
     *
     * @ORM\Column(name="imdb", type="string", length=128, nullable=false)
     */
    private $imdb;

    /**
     * @var float|null
     *
     * @ORM\Column(name="imdbrating", type="float", precision=10, scale=0, nullable=true)
     */
    private $imdbrating;

    /**
     * @var int
     *
     * @ORM\Column(name="number", type="integer", nullable=false)
     */
    private $number;

    /**
     * @var \Season
     *
     * @ORM\ManyToOne(targetEntity="Season", inversedBy="episodes")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="season_id", referencedColumnName="id")
     * })
     */
    private $season;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="User", mappedBy="episode")
     */
    private $user = array();

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->user = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Permet d'obtenir l'id de l'épisode
     *
     * @return ?int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Permet de récupérer le titre de l'épisode
     *
     * @return ?string
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * Permet définir le titre d'un épisode
     *
     * @param string $title le titre de l'épisode
     *
     * @return self
     */
    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Permet d'obtenir la date de l'épisode
     *
     * @return ?\DateTimeInterface
     */
    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    /**
     * Permet de modifier la date d'un épisode
     *
     * @param ?\DateTimeInterface $date La date de l'épisode
     *
     * @return self
     */
    public function setDate(?\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Permet d'obtenir le lien de la base de données de l'épisode
     *
     * @return ?string
     */
    public function getImdb(): ?string
    {
        return $this->imdb;
    }

    /**
     * Permet de définir le lien de la base de données de l'épisode
     *
     * @param string $imdb Le lien de la base de donnée
     *
     * @return ?string
     */
    public function setImdb(string $imdb): self
    {
        $this->imdb = $imdb;

        return $this;
    }

    /**
     * Permet d'obtenir la note de l'épisode fixer par imbd
     *
     * @return ?float
     */
    public function getImdbrating(): ?float
    {
        return $this->imdbrating;
    }

    /**
     * Permet de définir le lien de la base de données de l'épisode
     *
     * @param ?float $imdbrating La nouvelle note de la base de données
     *
     * @return self
     */
    public function setImdbrating(?float $imdbrating): self
    {
        $this->imdbrating = $imdbrating;

        return $this;
    }

    /**
     * Permet d'obtenir le numéro de l'épisode
     *
     * @return ?int
     */
    public function getNumber(): ?int
    {
        return $this->number;
    }

    /**
     * Permet de définir le numéro de l'épisode
     *
     *@param int $number le nouveau numéro de l'épisode
     *
     * @return self
     */
    public function setNumber(int $number): self
    {
        $this->number = $number;

        return $this;
    }

    /**
     * Permet d'obtenir la saison de l'épisode
     *
     * @return ?Season
     */
    public function getSeason(): ?Season
    {
        return $this->season;
    }

    /**
     * Permet de définir la saison de l'épisode
     *
     *@param ?Season $season la nouvelle saison de l'épisode
     *
     * @return self
     */
    public function setSeason(?Season $season): self
    {
        $this->season = $season;

        return $this;
    }

    /**
     * Permet d'obtenir la liste des utilisateurs qui suivent cet épisode
     *
     * @return Collection<int, User>
     */
    public function getUser(): Collection
    {
        return $this->user;
    }

    /**
     * Permet d'ajouter un user à la liste des utilisateurs ayant vu l'épisode
     *
     *@param User $user ajoute un utilisateur
     *
     * @return self
     */
    public function addUser(User $user): self
    {
        if (!$this->user->contains($user)) {
            $this->user->add($user);
            $user->addEpisode($this);
        }

        return $this;
    }

    /**
     * Permet de supprimer un utilisateur de la liste des utilisateurs ayant vu l'épisode
     *
     *@param int $number le nouveau numéro de l'épisode
     *
     * @return self
     */
    public function removeUser(User $user): self
    {
        if ($this->user->removeElement($user)) {
            $user->removeEpisode($this);
        }

        return $this;
    }

}
