<?php

namespace App\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * User
 *
 * @ORM\Table(name="users", indexes={@ORM\Index(name="city_id", columns={"city_id"})})
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User implements UserInterface, \Serializable
{
    const COLUMN_USERNAME = 'username';
    const COLUMN_USER_ID = 'userId';
    const COLUMN_PASSWORD = 'password';
    const COLUMN_SALT = 'salt';

    const USER_VERIFIED = 1;
    const USER_CHANGING_PASSWORD = 2;
    const USER_NEWSLETTER = 4;
    const USER_ADMIN = 8;

    /**
     * @var int
     *
     * @ORM\Column(name="user_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $userId;

    /**
     * @var string
     *
     * @ORM\Column(name="firstname", type="string", length=50, nullable=false)
     */
    private $firstname;

    /**
     * @var string
     *
     * @ORM\Column(name="lastname", type="string", length=50, nullable=false)
     */
    private $lastname;

    /**
     * @var string
     *
     * @ORM\Column(name="username", type="string", length=50, nullable=false)
     */
    private $username;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=50, nullable=false)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=128, nullable=false, options={"fixed"=true})
     */
    private $password;

    /**
     * @var string
     *
     * @ORM\Column(name="salt", type="string", length=128, nullable=false, options={"fixed"=true})
     */
    private $salt;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="date_of_birth", type="datetime", nullable=true)
     */
    private $dateOfBirth;

    /**
     * @var string|null
     *
     * @ORM\Column(name="info", type="text", length=65535, nullable=true)
     */
    private $info;

    /**
     * @var resource|null
     *
     * @ORM\Column(name="photo", type="string", length=255 , nullable=true)
     */
    private $photo;

    /**
     * @var string|null
     *
     * @ORM\Column(name="authentication_link", type="string", length=128, nullable=true, options={"fixed"=true})
     */
    private $authenticationLink;

    /**
     * @var string|null
     *
     * @ORM\Column(name="change_password_link", type="string", length=128, nullable=true, options={"fixed"=true})
     */
    private $changePasswordLink;

    /**
     * @var int|null
     *
     * @ORM\Column(name="options", type="integer", nullable=true)
     */
    private $options = '0';

    /**
     * @var City
     *
     * @ORM\ManyToOne(targetEntity="City")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="city_id", referencedColumnName="city_id")
     * })
     */
    private $city;

    /**
     * @var Collection
     *
     * @ORM\ManyToMany(targetEntity="Band", mappedBy="users")
     */
    private $bands;

    /**
     * @var Collection
     *
     * @ORM\ManyToMany(targetEntity="Instrument", mappedBy="users")
     */
    private $instruments;

    /**
     * @var Collection
     *
     * @ORM\ManyToMany(targetEntity="MusicGenre", mappedBy="user")
     */
    private $musicGenre;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="change_password_link_expiration_date", type="datetime", nullable=true)
     */
    private $changePasswordLinkExpirationDate;

    /**
     * @var int
     *
     * @ORM\Column(name="login_attempts_failed", type="integer", nullable=false)
     */
    private $loginAttemptsFailed = 0;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="last_login_failed_date", type="datetime", nullable=true)
     */
    private $lastLoginFailedDate;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->bands = new \Doctrine\Common\Collections\ArrayCollection();
        $this->instruments = new \Doctrine\Common\Collections\ArrayCollection();
        $this->musicGenre = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function getUserId(): ?int
    {
        return $this->userId;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getSalt(): ?string
    {
        return $this->salt;
    }

    public function setSalt(string $salt): self
    {
        $this->salt = $salt;

        return $this;
    }

    public function getDateOfBirth(): ?\DateTimeInterface
    {
        return $this->dateOfBirth;
    }

    public function getAgeInYears(): int
    {
        if ($this->dateOfBirth) {
            return date_diff(new \DateTime(), $this->dateOfBirth)->y;
        }

        return 0;
    }

    public function getLastLoginFailedDate(): ?\DateTime
    {
        return $this->lastLoginFailedDate;
    }

    public function setLastLoginFailedDate(?\DateTimeInterface $lastLoginFailedDate): self
    {
        $this->lastLoginFailedDate = $lastLoginFailedDate;

        return $this;
    }

    public function setDateOfBirth(?\DateTimeInterface $dateOfBirth): self
    {
        $this->dateOfBirth = $dateOfBirth;

        return $this;
    }

    public function getInfo(): ?string
    {
        return $this->info;
    }

    public function setInfo(?string $info): self
    {
        $this->info = $info;

        return $this;
    }

    public function getPhoto()
    {
        return $this->photo;
    }

    public function setPhoto($photo): self
    {
        $this->photo = $photo;

        return $this;
    }

    public function getAuthenticationLink(): ?string
    {
        return $this->authenticationLink;
    }

    public function setAuthenticationLink(?string $authenticationLink): self
    {
        $this->authenticationLink = $authenticationLink;

        return $this;
    }

    public function getChangePasswordLink(): ?string
    {
        return $this->changePasswordLink;
    }

    public function setChangePasswordLink(?string $changePasswordLink): self
    {
        $this->changePasswordLink = $changePasswordLink;

        return $this;
    }

    public function getOptions(): ?int
    {
        return $this->options;
    }

    public function setOptions(?int $options): self
    {
        $this->options = $options;

        return $this;
    }

    public function getLoginAttemptsFailed(): ?int
    {
        return $this->loginAttemptsFailed;
    }

    public function setLoginAttemptsFailed(?int $loginAttemptsFailed): self
    {
        $this->loginAttemptsFailed = $loginAttemptsFailed;

        return $this;
    }

    public function getCity(): ?City
    {
        return $this->city;
    }

    public function setCity(?City $city): self
    {
        $this->city = $city;

        return $this;
    }

    /**
     * @return Collection|Band[]
     */
    public function getBand(): Collection
    {
        return $this->bands;
    }

    public function addBand(Band $band): self
    {
        if (!$this->bands->contains($band)) {
            $this->bands[] = $band;
            $band->addUser($this);
        }

        return $this;
    }

    public function removeBand(Band $band): self
    {
        if ($this->bands->contains($band)) {
            $this->bands->removeElement($band);
            $band->removeUser($this);
        }

        return $this;
    }

    public function isPartOfBand(Band $band)
    {
        if ($this->bands->contains($band)) {
            return true;
        }

        return false;
    }

    /**
     * @return Collection|Instrument[]
     */
    public function getInstrument(): Collection
    {
        return $this->instruments;
    }

    public function addInstrument(Instrument $instrument): self
    {
        if (!$this->instruments->contains($instrument)) {
            $this->instruments->add($instrument);
            $instrument->addUser($this);
        }

        return $this;
    }

    public function removeInstrument(Instrument $instrument): self
    {
        if ($this->instruments->contains($instrument)) {
            $this->instruments->removeElement($instrument);
            $instrument->removeUser($this);
        }

        return $this;
    }

    /**
     * @return Collection|MusicGenre[]
     */
    public function getMusicGenres(): Collection
    {
        return $this->musicGenre;
    }

    public function addMusicGenre(MusicGenre $musicGenre): self
    {
        if (!$this->musicGenre->contains($musicGenre)) {
            $this->musicGenre[] = $musicGenre;
            $musicGenre->addUser($this);
        }

        return $this;
    }

    public function removeMusicGenre(MusicGenre $musicGenre): self
    {
        if ($this->musicGenre->contains($musicGenre)) {
            $this->musicGenre->removeElement($musicGenre);
            $musicGenre->removeUser($this);
        }

        return $this;
    }

    public function getChangePasswordLinkExpirationDate(): ?\DateTimeInterface
    {
        return $this->changePasswordLinkExpirationDate;
    }

    public function setChangePasswordLinkExpirationDate(\DateTimeInterface $changePasswordLinkExpirationDate): self
    {
        $this->changePasswordLinkExpirationDate = $changePasswordLinkExpirationDate;

        return $this;
    }

    public function addOption($option)
    {
        $this->options = $this->options | $option;
    }

    public function hasOption($option)
    {
        return boolval($this->options & $option);
    }

    public function unsetOption($option)
    {
        $this->options = $this->options ^ $option;
    }

    /** @see \Serializable::serialize() */
    public function serialize()
    {
        return serialize(array(
            $this->userId,
            $this->username,
            $this->password,
            $this->salt,
        ));
    }

    /** @see \Serializable::unserialize() */
    public function unserialize($serialized)
    {
        list (
            $this->userId,
            $this->username,
            $this->password,
            $this->salt
            ) = unserialize($serialized);
    }

    /**
     * @return array (Role|string)[] The user roles
     */
    public function getRoles()
    {
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /** */
    public function eraseCredentials(): void
    {
        return;
    }
}
