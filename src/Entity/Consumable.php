<?php

namespace App\Entity;

use App\Repository\ConsumableRepository;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ConsumableRepository::class)
 */
class Consumable
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;


    /**
     * @ORM\Column(type="bigint")
     */
    private $yellow;

    /**
     * @ORM\Column(type="bigint")
     */
    private $magenta;

    /**
     * @ORM\Column(type="bigint")
     */
    private $cyan;

    /**
     * @ORM\Column(type="bigint")
     */
    private $black;

    /**
     * @ORM\Column(type="bigint", nullable=true)
     */
    private $A3M;

    /**
     * @ORM\Column(type="bigint", nullable=true)
     */
    private $A3C;

    /**
     * @ORM\Column(type="bigint", nullable=true)
     */
    private $A4M;

    /**
     * @ORM\Column(type="bigint", nullable=true)
     */
    private $A4C;

    /**
     * @ORM\Column(type="bigint")
     */
    private $PPT;

    /**
     * @ORM\Column(type="bigint")
     */
    private $MPP;

    /**
     * @ORM\Column(type="bigint")
     */
    private $CPP;

    /**
     * @ORM\ManyToOne(targetEntity=Printer::class, inversedBy="consumables")
     * @ORM\JoinColumn(nullable=false)
     */
    private $print;

    /**
     * @ORM\Column(type="bigint", nullable=true)
     */
    private $A3DM;

    /**
     * @ORM\Column(type="bigint", nullable=true)
     */
    private $A4DM;

    /**
     * @ORM\Column(type="bigint", nullable=true)
     */
    private $A3DC;

    /**
     * @ORM\Column(type="bigint", nullable=true)
     */
    private $A4DC;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date_update;

    /**
     * @ORM\OneToMany(targetEntity=ConsumableDelta::class, mappedBy="consumable", orphanRemoval=true)
     */
    private $consumableDeltas;

    /**
     * @ORM\Column(type="bigint", nullable=true)
     */
    private $MBR;

    public function __construct()
    {
        $this->consumableDeltas = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getYellow(): ?string
    {
        return $this->yellow;
    }

    public function setYellow(string $yellow): self
    {
        $this->yellow = $yellow;

        return $this;
    }

    public function getMagenta(): ?string
    {
        return $this->magenta;
    }

    public function setMagenta(string $magenta): self
    {
        $this->magenta = $magenta;

        return $this;
    }

    public function getCyan(): ?string
    {
        return $this->cyan;
    }

    public function setCyan(string $cyan): self
    {
        $this->cyan = $cyan;

        return $this;
    }

    public function getBlack(): ?string
    {
        return $this->black;
    }

    public function setBlack(string $black): self
    {
        $this->black = $black;

        return $this;
    }

    public function getA3M(): ?string
    {
        return $this->A3M;
    }

    public function setA3M(?string $A3M): self
    {
        $this->A3M = $A3M;

        return $this;
    }

    public function getA3C(): ?string
    {
        return $this->A3C;
    }

    public function setA3C(?string $A3C): self
    {
        $this->A3C = $A3C;

        return $this;
    }

    public function getA4M(): ?string
    {
        return $this->A4M;
    }

    public function setA4M(?string $A4M): self
    {
        $this->A4M = $A4M;

        return $this;
    }

    public function getA4C(): ?string
    {
        return $this->A4C;
    }

    public function setA4C(?string $A4C): self
    {
        $this->A4C = $A4C;

        return $this;
    }

    public function getPPT(): ?string
    {
        return $this->PPT;
    }

    public function setPPT(string $PPT): self
    {
        $this->PPT = $PPT;

        return $this;
    }

    public function getMPP(): ?string
    {
        return $this->MPP;
    }

    public function setMPP(string $MPP): self
    {
        $this->MPP = $MPP;

        return $this;
    }

    public function getCPP(): ?string
    {
        return $this->CPP;
    }

    public function setCPP(string $CPP): self
    {
        $this->CPP = $CPP;

        return $this;
    }

    public function getPrint(): ?Printer
    {
        return $this->print;
    }

    public function setPrint(?Printer $print): self
    {
        $this->print = $print;

        return $this;
    }

    public function getA3DM(): ?string
    {
        return $this->A3DM;
    }

    public function setA3DM(?string $A3DM): self
    {
        $this->A3DM = $A3DM;

        return $this;
    }

    public function getA4DM(): ?string
    {
        return $this->A4DM;
    }

    public function setA4DM(?string $A4DM): self
    {
        $this->A4DM = $A4DM;

        return $this;
    }

    public function getA3DC(): ?string
    {
        return $this->A3DC;
    }

    public function setA3DC(?string $A3DC): self
    {
        $this->A3DC = $A3DC;

        return $this;
    }

    public function getA4DC(): ?string
    {
        return $this->A4DC;
    }

    public function setA4DC(?string $A4DC): self
    {
        $this->A4DC = $A4DC;

        return $this;
    }

    public function getDateUpdate(): ?DateTimeInterface
    {
        return $this->date_update;
    }

    public function setDateUpdate(DateTimeInterface $date_update): self
    {
        $this->date_update = $date_update;

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
            $consumableDelta->setConsumable($this);
        }

        return $this;
    }

    public function removeConsumableDelta(ConsumableDelta $consumableDelta): self
    {
        if ($this->consumableDeltas->contains($consumableDelta)) {
            $this->consumableDeltas->removeElement($consumableDelta);
            // set the owning side to null (unless already changed)
            if ($consumableDelta->getConsumable() === $this) {
                $consumableDelta->setConsumable(null);
            }
        }

        return $this;
    }

    public function getMBR(): ?string
    {
        return $this->MBR;
    }

    public function setMBR(?string $MBR): self
    {
        $this->MBR = $MBR;

        return $this;
    }
}
