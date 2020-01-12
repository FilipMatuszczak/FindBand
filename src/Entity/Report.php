<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Reports
 *
 * @ORM\Table(name="reports", indexes={@ORM\Index(name="post_id", columns={"post_id"}), @ORM\Index(name="user_id", columns={"user_id"}), @ORM\Index(name="notice_id", columns={"notice_id"})})
 * @ORM\Entity(repositoryClass="App\Repository\ReportRepository")
 */
class Report
{
    const OPTIONS_NEW = 0;
    const OPTIONS_USER_BANNED = 1;
    const OPTIONS_CANCELLED = 2;
    const OPTIONS_DELETED = 4;

    /**
     * @var int
     *
     * @ORM\Column(name="report_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $reportId;

    /**
     * @var string
     *
     * @ORM\Column(name="reason", type="text", length=65535, nullable=false)
     */
    private $reason;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="timestamp", type="datetime", nullable=false)
     */
    private $timestamp;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="options", type="integer", nullable=true)
     */
    private $options = '0';

    /**
     * @var Notice
     *
     * @ORM\ManyToOne(targetEntity="Notice")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="notice_id", referencedColumnName="notice_id", onDelete="SET NULL")
     * })
     */
    private $notice;

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
     * @var Post
     *
     * @ORM\ManyToOne(targetEntity="Post")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="post_id", referencedColumnName="post_id", onDelete="SET NULL")
     * })
     */
    private $post;

    public function getReportId(): ?int
    {
        return $this->reportId;
    }

    public function getReason(): ?string
    {
        return $this->reason;
    }

    public function setReason(string $reason): self
    {
        $this->reason = $reason;

        return $this;
    }

    public function getTimestamp(): ?\DateTimeInterface
    {
        return $this->timestamp;
    }

    public function setTimestamp(\DateTimeInterface $timestamp): self
    {
        $this->timestamp = $timestamp;

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

    public function getNotice(): ?Notice
    {
        return $this->notice;
    }

    public function setNotice(?Notice $notice): self
    {
        $this->notice = $notice;

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

    public function getPost(): ?Post
    {
        return $this->post;
    }

    public function setPost(?Post $post): self
    {
        $this->post = $post;

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
}
