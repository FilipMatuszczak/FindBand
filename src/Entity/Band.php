<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Bands
 *
 * @ORM\Table(name="bands")
 * @ORM\Entity(repositoryClass="App\Repository\BandRepository")
 */
class Band
{
    /**
     * @var int
     *
     * @ORM\Column(name="band_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $bandId;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=50, nullable=false)
     */
    private $title;

    /**
     * @var string|null
     *
     * @ORM\Column(name="description", type="text", length=65535, nullable=true)
     */
    private $description;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_date", type="datetime", nullable=false)
     */
    private $createdDate;

    /**
     * @var string|null
     *
     * @ORM\Column(name="photo", type="blob", length=0, nullable=true)
     */
    private $photo;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="User", inversedBy="band")
     * @ORM\JoinTable(name="users_bands",
     *   joinColumns={
     *     @ORM\JoinColumn(name="band_id", referencedColumnName="band_id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="user_id", referencedColumnName="user_id")
     *   }
     * )
     */
    private $user;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="MusicGenre", inversedBy="band")
     * @ORM\JoinTable(name="bands_music_genres",
     *   joinColumns={
     *     @ORM\JoinColumn(name="band_id", referencedColumnName="band_id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="music_genre_id", referencedColumnName="music_genre_id")
     *   }
     * )
     */
    private $musicGenre;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->user = new \Doctrine\Common\Collections\ArrayCollection();
        $this->musicGenre = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function getBandId(): ?int
    {
        return $this->bandId;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getCreatedDate(): ?\DateTimeInterface
    {
        return $this->createdDate;
    }

    public function setCreatedDate(\DateTimeInterface $createdDate): self
    {
        $this->createdDate = $createdDate;

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

    /**
     * @return Collection|User[]
     */
    public function getUser(): Collection
    {
        return $this->user;
    }

    public function addUser(User $user): self
    {
        if (!$this->user->contains($user)) {
            $this->user[] = $user;
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->user->contains($user)) {
            $this->user->removeElement($user);
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
            $musicGenre->addBand($this);
        }

        return $this;
    }

    public function removeMusicGenre(MusicGenre $musicGenre): self
    {
        if ($this->musicGenre->contains($musicGenre)) {
            $this->musicGenre->removeElement($musicGenre);
            $musicGenre->removeBand($this);
        }

        return $this;
    }
}
