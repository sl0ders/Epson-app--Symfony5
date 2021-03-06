<?php

namespace App\Entity;

use App\Repository\NotificationRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=NotificationRepository::class)
 */
class Notification
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="text")
     */
    private $message;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isEnabled;

    /**
     * @ORM\ManyToOne(targetEntity=Company::class, inversedBy="notifications")
     */
    private $sender;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="readAt", type="datetime", nullable=true)
     */
    private $readAt;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="expirationDate", type="datetime", nullable=true)
     */
    private $expirationDate;

    /**
     * @ORM\ManyToOne(targetEntity=company::class, inversedBy="adminNotification")
     */
    private $receiver;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $path;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $idPath;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreatedAt(): ?DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTime $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }

    public function getIsEnabled(): ?bool
    {
        return $this->isEnabled;
    }

    public function setIsEnabled(bool $isEnabled): self
    {
        $this->isEnabled = $isEnabled;

        return $this;
    }

    public function getSender(): ?Company
    {
        return $this->sender;
    }

    public function setSender(?Company $sender): self
    {
        $this->sender = $sender;

        return $this;
    }

    /**
     * @return DateTime
     */
    public function getReadAt(): ?DateTime
    {
        return $this->readAt;
    }

    /**
     * @param DateTime $readAt
     * @return Notification
     */
    public function setReadAt(DateTime $readAt): ?Notification
    {
        $this->readAt = $readAt;
        return $this;
    }

    /**
     * @return DateTime
     */
    public function getExpirationDate(): DateTime
    {
        return $this->expirationDate;
    }

    /**
     * @param DateTime $expirationDate
     * @return Notification
     */
    public function setExpirationDate(DateTime $expirationDate): Notification
    {
        $this->expirationDate = $expirationDate;
        return $this;
    }

    public function getReceiver(): ?company
    {
        return $this->receiver;
    }

    public function setReceiver(?company $receiver): self
    {
        $this->receiver = $receiver;

        return $this;
    }

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function setPath(?string $path): self
    {
        $this->path = $path;

        return $this;
    }

    public function getIdPath(): ?int
    {
        return $this->idPath;
    }

    public function setIdPath(?int $idPath): self
    {
        $this->idPath = $idPath;

        return $this;
    }
}
