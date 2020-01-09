<?php

namespace App\Entity\Dto;

class MessageShorcutDto
{
    /**
     * @var string
     */
    private $username;

    /**
     * @var string
     */
    private $date;

    /**
     * @var string
     */
    private $lastMessageUsername;

    /**
     * @var string
     */
    private $lastMessageText;

    /**
     * @var int
     */
    private $userId;

    /**
     * @return string
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param string $date
     */
    public function setDate(string $date): void
    {
        $this->date = $date;
    }

    /**
     * @return string
     */
    public function getLastMessageText(): string
    {
        return $this->lastMessageText;
    }

    /**
     * @param string $lastMessageText
     */
    public function setLastMessageText(string $lastMessageText): void
    {
        $this->lastMessageText = $lastMessageText;
    }

    /**
     * @return string
     */
    public function getLastMessageUsername(): string
    {
        return $this->lastMessageUsername;
    }

    /**
     * @param string $lastMessageUsername
     */
    public function setLastMessageUsername(string $lastMessageUsername): void
    {
        $this->lastMessageUsername = $lastMessageUsername;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @param string $username
     */
    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * @param int $userId
     */
    public function setUserId(int $userId): void
    {
        $this->userId = $userId;
    }
}