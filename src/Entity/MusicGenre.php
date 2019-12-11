<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * MusicGenres
 *
 * @ORM\Table(name="music_genres")
 * @ORM\Entity(repositoryClass="App\Repository\MusicGenreRepository")
 */
class MusicGenre
{
    /**
     * @var int
     *
     * @ORM\Column(name="music_genre_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $musicGenreId;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=50, nullable=false)
     */
    private $name;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="User", inversedBy="musicGenre")
     * @ORM\JoinTable(name="users_music_genres",
     *   joinColumns={
     *     @ORM\JoinColumn(name="music_genre_id", referencedColumnName="music_genre_id")
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
     * @ORM\ManyToMany(targetEntity="Band", inversedBy="musicGenre")
     * @ORM\JoinTable(name="bands_music_genres",
     *   joinColumns={
     *     @ORM\JoinColumn(name="music_genre_id", referencedColumnName="music_genre_id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="band_id", referencedColumnName="band_id")
     *   }
     * )
     */
    private $band;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->user = new \Doctrine\Common\Collections\ArrayCollection();
        $this->band = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function getMusicGenreId(): ?int
    {
        return $this->musicGenreId;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

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
     * @return Collection|Band[]
     */
    public function getBand(): Collection
    {
        return $this->band;
    }

    public function addBand(Band $band): self
    {
        if (!$this->band->contains($band)) {
            $this->band[] = $band;
            $band->addMusicGenre($this);
        }

        return $this;
    }

    public function removeBand(Band $band): self
    {
        if ($this->band->contains($band)) {
            $this->band->removeElement($band);
            $band->removeMusicGenre($this);
        }

        return $this;
    }
}
