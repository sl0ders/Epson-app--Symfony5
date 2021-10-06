<?php

namespace App\Entity;

use App\Repository\OrderCartridgeRepository;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=OrderCartridgeRepository::class)
 */
class OrderCartridge
{
    const VALIDATE_REPORTED = 'order.cartridge.validateType.reported';
    const VALIDATE_COMMANDED = 'order.cartridge.validateType.commanded';
    const VALIDATE_INPREPARATION = 'order.cartridge.validateType.inPreparation';
    const VALIDATE_SENDED = 'order.cartridge.validateType.sended';

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $note;

    /**
     * @ORM\ManyToOne(targetEntity=Cartridge::class, inversedBy="orderCartridges")
     * @ORM\JoinColumn(nullable=false)
     */
    private $cartridge;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $orderAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="orderCartridges")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\Column(name="state", type="string", length=60)
     */
    private $state;

    /**
     * @ORM\ManyToOne(targetEntity=Company::class, inversedBy="orderCartridges")
     */
    private $client;

    /**
     * @ORM\ManyToOne(targetEntity=Printer::class, inversedBy="orderCartridges")
     */
    private $printer;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $orderCode;

    /**
     * @ORM\Column(type="integer")
     */
    private $quantity;

    /**
     * @ORM\OneToMany(targetEntity=Report::class, mappedBy="orderCartridge")
     */
    private $reports;

    public function __construct()
    {
        $this->reports = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNote(): ?string
    {
        return $this->note;
    }

    public function setNote(?string $note): self
    {
        $this->note = $note;

        return $this;
    }

    public function getCartridge(): ?Cartridge
    {
        return $this->cartridge;
    }

    public function setCartridge(?Cartridge $cartridge): self
    {
        $this->cartridge = $cartridge;

        return $this;
    }

    public function getorderAt(): ?DateTimeInterface
    {
        return $this->orderAt;
    }

    public function setOrderAt(?DateTimeInterface $orderAt): self
    {
        $this->orderAt = $orderAt;

        return $this;
    }

    public function getCreatedAt(): ?DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

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

    public function getState(): ?string
    {
        return $this->state;
    }

    public function setState(string $state): self
    {
        $this->state = $state;

        return $this;
    }

    public function getClient(): ?Company
    {
        return $this->client;
    }

    public function setClient(?Company $client): self
    {
        $this->client = $client;

        return $this;
    }

    public function getPrinter(): ?Printer
    {
        return $this->printer;
    }

    public function setPrinter(?Printer $printer): self
    {
        $this->printer = $printer;

        return $this;
    }

    public function getOrderCode(): ?string
    {
        return $this->orderCode;
    }

    public function setOrderCode(string $orderCode): self
    {
        $this->orderCode = $orderCode;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * @return Collection|Report[]
     */
    public function getReports(): Collection
    {
        return $this->reports;
    }

    public function addReport(Report $report): self
    {
        if (!$this->reports->contains($report)) {
            $this->reports[] = $report;
            $report->setOrderCartridge($this);
        }

        return $this;
    }

    public function removeReport(Report $report): self
    {
        if ($this->reports->removeElement($report)) {
            // set the owning side to null (unless already changed)
            if ($report->getOrderCartridge() === $this) {
                $report->setOrderCartridge(null);
            }
        }

        return $this;
    }
}
