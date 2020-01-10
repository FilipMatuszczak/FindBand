<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AddUserToBandMessage
 *
 * @ORM\Table(name="add_user_to_band_message", indexes={@ORM\Index(name="user_id", columns={"user_id"}), @ORM\Index(name="band_id", columns={"band_id"})})
 * @ORM\Entity
 */
class AddUserToBandMessage
{
    const OPTION_NEW = 1;
    const OPTION_ACCEPTED = 2;
    const OPTION_DECLINED = 4;

    /**
     * @var int
     *
     * @ORM\Column(name="add_user_to_band_message_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $addUserToBandMessageId;

    /**
     * @var string
     *
     * @ORM\Column(name="reason", type="text", length=65535, nullable=false)
     */
    private $reason;

    /**
     * @var int
     *
     * @ORM\Column(name="options", type="integer", nullable=false)
     */
    private $options = '0';

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
     * @return int
     */
    public function getAddUserToBandMessageId(): int
    {
        return $this->addUserToBandMessageId;
    }

    /**
     * @return Band
     */
    public function getBand(): Band
    {
        return $this->band;
    }

    /**
     * @return string
     */
    public function getReason(): string
    {
        return $this->reason;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param int $addUserToBandMessageId
     */
    public function setAddUserToBandMessageId(int $addUserToBandMessageId): void
    {
        $this->addUserToBandMessageId = $addUserToBandMessageId;
    }

    /**
     * @param Band $band
     */
    public function setBand(Band $band): void
    {
        $this->band = $band;
    }

    /**
     * @param int $options
     */
    public function setOptions(int $options): void
    {
        $this->options = $options;
    }

    /**
     * @param string $reason
     */
    public function setReason(string $reason): void
    {
        $this->reason = $reason;
    }

    /**
     * @param User $user
     */
    public function setUser(User $user): void
    {
        $this->user = $user;
    }
}
