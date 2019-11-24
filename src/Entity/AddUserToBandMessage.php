<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AddUserToBandMessage
 *
 * @ORM\Table(name="add_user_to_band_message", indexes={@ORM\Index(name="user_id", columns={"user_id"}), @ORM\Index(name="band_id", columns={"band_id"})})
 * @ORM\Entity(repositoryClass="App\Repository\AddUserToBandMessageRepository")
 */
class AddUserToBandMessage
{
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
     * @ORM\Column(name="options", type="boolean", nullable=false)
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


}
