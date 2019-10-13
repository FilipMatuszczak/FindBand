<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Notices
 *
 * @ORM\Table(name="notices", indexes={@ORM\Index(name="instrument_id", columns={"instrument_id"}), @ORM\Index(name="user_id", columns={"user_id"}), @ORM\Index(name="band_id", columns={"band_id"})})
 * @ORM\Entity
 */
class Notice
{
    /**
     * @var int
     *
     * @ORM\Column(name="notice_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $noticeId;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=50, nullable=false)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="details", type="text", length=65535, nullable=false)
     */
    private $details;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_id", referencedColumnName="user_id")
     * })
     */
    private $user;

    /**
     * @var Band
     *
     * @ORM\ManyToOne(targetEntity="Band")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="band_id", referencedColumnName="band_id")
     * })
     */
    private $band;

    /**
     * @var Instrument
     *
     * @ORM\ManyToOne(targetEntity="Instrument")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="instrument_id", referencedColumnName="instrument_id")
     * })
     */
    private $instrument;

    public function getNoticeId(): ?int
    {
        return $this->noticeId;
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

    public function getDetails(): ?string
    {
        return $this->details;
    }

    public function setDetails(string $details): self
    {
        $this->details = $details;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getBand(): ?Band
    {
        return $this->band;
    }

    public function setBand(?Band $band): self
    {
        $this->band = $band;

        return $this;
    }

    public function getInstrument(): ?Instrument
    {
        return $this->instrument;
    }

    public function setInstrument(?Instrument $instrument): self
    {
        $this->instrument = $instrument;

        return $this;
    }


}
