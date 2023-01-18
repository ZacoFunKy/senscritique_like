<?php

namespace App\Entity;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Doctrine\DBAL\Types\Types;


use Doctrine\ORM\Mapping as ORM;

/**
 * User
 *
 * @ORM\Table(name="user", uniqueConstraints={@ORM\UniqueConstraint(name="UNIQ_8D93D649E7927C74", columns={"email"})}, indexes={@ORM\Index(name="IDX_8D93D649F92F3E70", columns={"country_id"})})
 * @ORM\Entity
 */
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
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
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=128, nullable=false)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=128, nullable=false)
     */
    private $password;

    /**
     * @var array
     *
     * @ORM\Column(name="roles", type="json", nullable=false)
     */
    private $roles = ['ROLE_USER'];

    /**
     * @var bool
     *
     * @ORM\Column(name="admin", type="boolean", nullable=false)
     */
    private $admin = 0;

      /**
     * @var bool
     *
     * @ORM\Column(name="super_admin", type="boolean", nullable=false)
     */
    private $isSuperAdmin = 0;
   /**
     * @var bool
     *
     * @ORM\Column(name="Suspendu", type="boolean", nullable=true)
     */
    private $isSuspendu = 0;

    /**
     * @var bool
     *
     * @ORM\Column(name="fake_account", type="boolean", nullable=true)
     */
    private $isBot= 0;
    
    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="register_date", type="datetime", nullable=true)
     */
    private $registerDate;

    /**
     * @var string|null
     *
     * @ORM\Column(name="poster", type="blob", length=0, nullable=true)
     */
    private $photo;

    /**
     * @var string|null
     *
     * @ORM\Column(name="user_id", type="string", length=128, nullable=true)
     */
    private $userId;

    /**
     * @var \Country
     *
     * @ORM\ManyToOne(targetEntity="Country")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="country_id", referencedColumnName="id")
     * })
     */
    private $country;



    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Series", inversedBy="user")
     * @ORM\JoinTable(name="user_series",
     *   joinColumns={
     *     @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="series_id", referencedColumnName="id")
     *   }
     * )
     */
    private $series = array();

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Episode", inversedBy="user")
     * @ORM\JoinTable(name="user_episode",
     *   joinColumns={
     *     @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="episode_id", referencedColumnName="id")
     *   }
     * )
     */
    private $episode = array();

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->series = new \Doctrine\Common\Collections\ArrayCollection();
        $this->episode = new \Doctrine\Common\Collections\ArrayCollection();
        $this->photo = file_get_contents(__DIR__.'/../../public/images/avatar.png');
    }

    /**
     * Permet d'obtenir l'id de l'utilisateur
     *
     * @return ?int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Permet d'obtenir le nom de l'utilisateur
     *
     * @return ?string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Permet de définir le nom de l'utilisateur
     *
     * @param string $name le nom de l'utilsateur
     *
     * @return self
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Permet d'obtenir l'email de l'utilisateur
     *
     * @return ?string
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * Permet de définir le mail de l'utilisateur
     *
     * @param string $email l'email de l'utilisateur
     *
     * @return self
     */
    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Permet d'obtenir le mot de passe de l'utilisateur
     *
     * @return ?string
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * Permet de changer le mot de passe de l'utilisateur
     *
     * @param string $password le mot de passe de l'utilisateur
     *
     * @return self
     */
    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Permet d'obtenir la date d'inscription de l'utilisateur
     *
     * @return ?\DateTimeInterface
     */
    public function getRegisterDate(): ?\DateTimeInterface
    {
        return $this->registerDate;
    }

    /**
     * Permet de changer la date de création du compte de l'utilisateur
     *
     * @param ?\DateTimeInterface $registerDate la nouvelle date de création du compte de l'utilisateur
     *
     * @return self
     */
    public function setRegisterDate(?\DateTimeInterface $registerDate): self
    {
        $this->registerDate = $registerDate;

        return $this;
    }

    /**
     * Permet d'obtenir le pays de l'utilisateur
     *
     * @return ?Country
     */
    public function getCountry(): ?Country
    {
        return $this->country;
    }

    /**
     * Permet de changer le pays de l'utilisateur
     *
     * @param ?Country $country le pays de l'utilisateur
     *
     * @return self
     */
    public function setCountry(?Country $country): self
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Permet d'obtenir la liste des séries suivis par l'utilisateur
     *
     * @return Collection<int, Series>
     */
    public function getSeries(): Collection
    {
        return $this->series;
    }

    /**
     * Permet d'ajouter une série à la liste des séries suivis par l'utilisateur
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
     * Permet de supprimer une série de la liste des séries suivis par l'utilisateur
     *
     * @param string $password le mot de passe de l'utilisateur
     *
     * @return ?string
     */
    public function removeSeries(Series $series): self
    {
        $this->series->removeElement($series);

        return $this;
    }

    /**
     * Permet d'obtenir la liste des épisodes vus par l'utilisateur
     *
     * @return Collection<int, Episode>
     */
    public function getEpisode(): Collection
    {
        return $this->episode;
    }

    /**
     * Permet d'ajouter un épisode à la liste des épisodes vus par l'utilisateur
     *
     * @param Episode $episode l'épisode à ajouter
     *
     * @return self
     */
    public function addEpisode(Episode $episode): self
    {
        if (!$this->episode->contains($episode)) {
            $this->episode->add($episode);
        }

        return $this;
    }

    /**
     * Permet de supprimer un épisode de la liste des épisodes vus par l'utilisateur
     *
     * @param Episode $episode l'episode à supprimer de la liste
     *
     * @return self
     */
    public function removeEpisode(Episode $episode): self
    {
        $this->episode->removeElement($episode);

        return $this;
    }

    public function getUserIdentifier(): string { return $this->getEmail(); }
    public function eraseCredentials() { }

    /**
     * Permet d'obtenir le rôle d'un utilisateur
     *
     * @return array
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * Permet de définir le ou les rôles d'un utilisateur
     *
     * @param array $roles la liste des rôles à ajouter à l'utilisateur
     *
     * @return self
     */
    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * Permet de savoir si l'utilisateur est un administrateur ou non
     *
     * @return ?bool
     */
    public function getisAdmin(): ?bool
    {
        return $this->admin;
    }

    /**
     * Permet de définir un utilisateur comme adminsitrateur ou non
     *
     * @param bool $admin 1 si l'utilisateur doit etre admin, sinon 0
     *
     * @return self
     */
    public function setisAdmin(bool $admin): self
    {
        // si l'utilisateur est super-admin il est aussi administrateur
        if ($this->getIsSuperAdmin()) {
            $admin = true;
            $this->setRoles(['ROLE_ADMIN', 'ROLE_SUPER_ADMIN']);
            $this->admin = $admin;
            return $this;
        }
        if ($admin) {
            $this->setRoles(['ROLE_ADMIN']);
        } else {
            $this->setRoles(['ROLE_USER']);
        }
        $this->admin = $admin;
        return $this;
    }

    /**
     * Permet de savoir si l'utilisateur est un super-administrateur ou non
     *
     * @return ?bool
     */
    public function getIsSuperAdmin(): ?bool
    {
        return $this->isSuperAdmin;
    }

    /**
     * Permet de définir un utilisateur comme super-adminsitrateur ou non
     *
     * @param bool $isSuperAdmin 1 si l'utilisateur doit etre super-admin, sinon 0
     *
     * @return self
     */
    public function setIsSuperAdmin(bool $isSuperAdmin): self
    {
        $this->isSuperAdmin = $isSuperAdmin;
        return $this;
    }

    /**
     * Permet d'obtenir la photo d'un utilisateur
     */
    public function getPhoto()
    {
        return $this->photo;
    }

    /**
     * Permet de définir la photo d'un utilisateur
     *
     * @param $photo le lien de la photo
     *
     * @return self
     */
    public function setPhoto($photo): self
    {
        $this->photo = $photo;

        return $this;
    }


    /**
     * Permet de savoir si l'utilisateur est un compte fake ou nono
     *
     * @return ?bool
     */
    public function getIsBot(): ?bool
    {
        return $this->isBot;
    }

    /**
     * Permet de définir un utilisateur comme compte fake ou non
     *
     * @param bool $isBot 1 si l'utilisateur est un compte fake, sinon 0
     *
     * @return self
     */
    public function setIsBot(bool $isBot): self
    {
        $this->isBot = $isBot;

        return $this;
    }

    public function __serialize(): array
    {
        return [
            $this->id,
            $this->email,
            $this->password,
            $this->name,
            $this->registerDate,
            $this->userId,
            $this->country,
            $this->series,
            $this->episode,
            $this->roles,
            $this->admin,
            $this->isSuperAdmin,
            $this->photo,
        ];
    }

    public function __unserialize(array $data): void
    {
        [
            $this->id,
            $this->email,
            $this->password,
            $this->name,
            $this->registerDate,
            $this->userId,
            $this->country,
            $this->series,
            $this->episode,
            $this->roles,
            $this->admin,
            $this->isSuperAdmin,
            $this->photo,
        ] = $data;
    }

    /**
     * Permet de définir si un utilisateur a été suspendu ou non
     *
     * @return ?bool
     */
    public function getSuspendu(): ?bool
    {
        return $this->isSuspendu;
    }

    /**
     * Permet de suspendre le compte de l'utilisateur ou de le réactivé
     *
     * @param bool $isSuspendus 1 si l'utilisateur est suspendu, sinon 0
     */
    public function setSuspendu(bool $isSuspendu)
    {
        if ($this->getIsSuperAdmin() || $this->getisAdmin()) {
            $isSuspendu = false;
            $this->isSuspendu = $isSuspendu;
            return $this;
        }
        $this->isSuspendu = $isSuspendu;
        if ($isSuspendu) {
            $this->emptyRoles();
            $this->setRoles(['ROLE_SUSPENDU']);
        } else {
            $this->emptyRoles();
            $this->setRoles(['ROLE_USER']);
        }
        return $this;
    }

    public function emptyRoles()
    {
        $this->roles = [];
    }


    public function setregenPassowrd(string $password): void
    {
        $hash = password_hash($password, PASSWORD_BCRYPT);
        $this->setPassword($hash);
    }

    public function getregenPassowrd(): string
    {
        return $this->password;
    }

}
