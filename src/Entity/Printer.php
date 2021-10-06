<?php

namespace App\Entity;

use App\Repository\PrinterRepository;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PrinterRepository::class)
 */
class Printer
{

    const STATE_OPERATIONAL = "printer.states.operational";
    const STATE_BROKEN = "printer.states.broken";
    const STATE_AWAITING_MAINTENANCE = "printer.states.awaitingMaintenance";

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255,nullable=true)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255,nullable=true)
     */
    private $office;

    /**
     * @ORM\Column(type="string", length=255,nullable=true)
     */
    private $ip;

    /**
     * @ORM\Column(type="string", length=255,nullable=true)
     */
    private $mac;

    /**
     * @ORM\Column(type="string", length=255,nullable=true)
     */
    private $sn;

    /**
     * @ORM\Column(type="string", length=255,nullable=true)
     */
    private $swv;

    /**
     * @ORM\Column(type="string", length=255,nullable=true)
     */
    private $des;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updateAt;

    /**
     * @ORM\Column(type="boolean")
     */
    private $enable;

    /**
     * @ORM\OneToMany(targetEntity=Consumable::class, mappedBy="print", orphanRemoval=true)
     */
    private $consumables;


    /**
     * @ORM\OneToMany(targetEntity=ConsumableDelta::class, mappedBy="printer")
     */
    private $consumableDeltas;

    /**
     * @ORM\ManyToOne(targetEntity=Company::class, inversedBy="printers")
     * @ORM\JoinColumn(nullable=false)
     */
    private $company;

    /**
     * @ORM\OneToMany(targetEntity=Cartridge::class, mappedBy="printer")
     */
    private $cartridge;

    /**
     * @ORM\Column(name="state", type="string", length=60)
     */
    private $state;

    /**
     * @ORM\OneToOne(targetEntity=RecoveryBac::class, mappedBy="printer", cascade={"persist", "remove"})
     */
    private $recoveryBac;

    /**
     * @ORM\OneToMany(targetEntity=OrderCartridge::class, mappedBy="printer")
     */
    private $orderCartridges;

    /**
     * @ORM\OneToMany(targetEntity=Report::class, mappedBy="printer")
     */
    private $reports;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $subname;


    public function __construct()
    {
        $this->consumables = new ArrayCollection();
        $this->consumableDeltas = new ArrayCollection();
        $this->cartridge = new ArrayCollection();
        $this->orderCartridges = new ArrayCollection();
        $this->reports = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getOffice(): ?string
    {
        return $this->office;
    }

    public function setOffice(string $office): self
    {
        $this->office = $office;

        return $this;
    }

    public function getIp(): ?string
    {
        return $this->ip;
    }

    public function setIp(string $ip): self
    {
        $this->ip = $ip;

        return $this;
    }

    public function getMac(): ?string
    {
        return $this->mac;
    }

    public function setMac(string $mac): self
    {
        $this->mac = $mac;

        return $this;
    }

    public function getSn(): ?string
    {
        return $this->sn;
    }

    public function setSn(string $sn): self
    {
        $this->sn = $sn;

        return $this;
    }

    public function getSwv(): ?string
    {
        return $this->swv;
    }

    public function setSwv(string $swv): self
    {
        $this->swv = $swv;

        return $this;
    }

    public function getDes(): ?string
    {
        return $this->des;
    }

    public function setDes(string $des): self
    {
        $this->des = $des;

        return $this;
    }

    public function getUpdateAt(): ?DateTimeInterface
    {
        return $this->updateAt;
    }

    public function setUpdateAt(DateTimeInterface $updateAt): self
    {
        $this->updateAt = $updateAt;

        return $this;
    }

    public function getEnable(): ?bool
    {
        return $this->enable;
    }

    public function setEnable(bool $enable): self
    {
        $this->enable = $enable;

        return $this;
    }


    /**
     * @return Collection|Consumable[]
     */
    public function getConsumables(): Collection
    {
        return $this->consumables;
    }

    public function addConsumable(Consumable $consumable): self
    {
        if (!$this->consumables->contains($consumable)) {
            $this->consumables[] = $consumable;
            $consumable->setPrint($this);
        }

        return $this;
    }

    public function removeConsumable(Consumable $consumable): self
    {
        if ($this->consumables->contains($consumable)) {
            $this->consumables->removeElement($consumable);
            // set the owning side to null (unless already changed)
            if ($consumable->getPrint() === $this) {
                $consumable->setPrint(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|ConsumableDelta[]
     */
    public function getConsumableDeltas(): Collection
    {
        return $this->consumableDeltas;
    }

    public function addConsumableDelta(ConsumableDelta $consumableDelta): self
    {
        if (!$this->consumableDeltas->contains($consumableDelta)) {
            $this->consumableDeltas[] = $consumableDelta;
            $consumableDelta->setPrinter($this);
        }

        return $this;
    }

    public function removeConsumableDelta(ConsumableDelta $consumableDelta): self
    {
        if ($this->consumableDeltas->contains($consumableDelta)) {
            $this->consumableDeltas->removeElement($consumableDelta);
            // set the owning side to null (unless already changed)
            if ($consumableDelta->getPrinter() === $this) {
                $consumableDelta->setPrinter(null);
            }
        }

        return $this;
    }

    public function getCompany(): ?Company
    {
        return $this->company;
    }

    public function setCompany(?Company $company): self
    {
        $this->company = $company;

        return $this;
    }

    /**
     * @return Collection|Cartridge[]
     */
    public function getCartridge(): Collection
    {
        return $this->cartridge;
    }

    public function addCartridge(Cartridge $cartridge): self
    {
        if (!$this->cartridge->contains($cartridge)) {
            $this->cartridge[] = $cartridge;
            $cartridge->setPrinter($this);
        }

        return $this;
    }

    public function removeCartridge(Cartridge $cartridge): self
    {
        if ($this->cartridge->removeElement($cartridge)) {
            // set the owning side to null (unless already changed)
            if ($cartridge->getPrinter() === $this) {
                $cartridge->setPrinter(null);
            }
        }

        return $this;
    }

    /**
     * @return mixed
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * @param mixed $state
     */
    public function setState($state): void
    {
        $this->state = $state;
    }

    public function getRecoveryBac(): ?RecoveryBac
    {
        return $this->recoveryBac;
    }

    public function setRecoveryBac(RecoveryBac $recoveryBac): self
    {
        // set the owning side of the relation if necessary
        if ($recoveryBac->getPrinter() !== $this) {
            $recoveryBac->setPrinter($this);
        }

        $this->recoveryBac = $recoveryBac;

        return $this;
    }

    /**
     * @return Collection|OrderCartridge[]
     */
    public function getOrderCartridges(): Collection
    {
        return $this->orderCartridges;
    }

    public function addOrderCartridge(OrderCartridge $orderCartridge): self
    {
        if (!$this->orderCartridges->contains($orderCartridge)) {
            $this->orderCartridges[] = $orderCartridge;
            $orderCartridge->setPrinter($this);
        }

        return $this;
    }

    public function removeOrderCartridge(OrderCartridge $orderCartridge): self
    {
        if ($this->orderCartridges->removeElement($orderCartridge)) {
            // set the owning side to null (unless already changed)
            if ($orderCartridge->getPrinter() === $this) {
                $orderCartridge->setPrinter(null);
            }
        }

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
            $report->setPrinter($this);
        }

        return $this;
    }

    public function removeReport(Report $report): self
    {
        if ($this->reports->removeElement($report)) {
            // set the owning side to null (unless already changed)
            if ($report->getPrinter() === $this) {
                $report->setPrinter(null);
            }
        }

        return $this;
    }

    public function getSubname(): ?string
    {
        return $this->subname;
    }

    public function setSubname(?string $subname): self
    {
        $this->subname = $subname;

        return $this;
    }
}
