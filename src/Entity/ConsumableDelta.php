<?php

namespace App\Entity;

use App\Repository\ConsumableDeltaRepository;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ConsumableDeltaRepository::class)
 */
class ConsumableDelta
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="bigint", nullable=true)
     */
    private $PPT_delta;

    /**
     * @ORM\Column(type="bigint", nullable=true)
     */
    private $MPP_delta;

    /**
     * @ORM\Column(type="bigint", nullable=true)
     */
    private $CPP_delta;

    /**
     * @ORM\ManyToOne(targetEntity=Consumable::class, inversedBy="consumableDeltas")
     */
    private $consumable;

    /**
     * @ORM\Column(type="bigint", nullable=true)
     */
    private $A3M_delta;

    /**
     * @ORM\Column(type="bigint", nullable=true)
     */
    private $A3C_delta;

    /**
     * @ORM\Column(type="bigint", nullable=true)
     */
    private $A4M_delta;

    /**
     * @ORM\Column(type="bigint", nullable=true)
     */
    private $A4C_delta;

    /**
     * @ORM\Column(type="bigint")
     */
    private $yellow_delta;

    /**
     * @ORM\Column(type="bigint")
     */
    private $magenta_delta;

    /**
     * @ORM\Column(type="bigint")
     */
    private $black_delta;

    /**
     * @ORM\Column(type="bigint")
     */
    private $cyan_delta;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updateAt;

    /**
     * @ORM\Column(type="bigint", nullable=true)
     */
    private $MBR_delta;

    /**
     * @ORM\ManyToOne(targetEntity=Printer::class, inversedBy="consumableDeltas")
     */
    private $printer;

    /**
     * @ORM\Column(type="bigint", nullable=true)
     */
    private $A4DM_delta;

    /**
     * @ORM\Column(type="bigint", nullable=true)
     */
    private $A4DC_delta;

    /**
     * @ORM\Column(type="bigint", nullable=true)
     */
    private $A3DC_delta;

    /**
     * @ORM\Column(type="bigint", nullable=true)
     */
    private $A3DM_delta;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPPTDelta(): ?string
    {
        return $this->PPT_delta;
    }

    public function setPPTDelta(string $PPT_delta): self
    {
        $this->PPT_delta = $PPT_delta;

        return $this;
    }

    public function getMPPDelta(): ?string
    {
        return $this->MPP_delta;
    }

    public function setMPPDelta(?string $MPP_delta): self
    {
        $this->MPP_delta = $MPP_delta;

        return $this;
    }

    public function getCPPDelta(): ?string
    {
        return $this->CPP_delta;
    }

    public function setCPPDelta(?string $CPP_delta): self
    {
        $this->CPP_delta = $CPP_delta;

        return $this;
    }

    public function getConsumable(): ?consumable
    {
        return $this->consumable;
    }

    public function setConsumable(?consumable $consumable): self
    {
        $this->consumable = $consumable;

        return $this;
    }

    public function getA3MDelta(): ?string
    {
        return $this->A3M_delta;
    }

    public function setA3MDelta(?string $A3M_delta): self
    {
        $this->A3M_delta = $A3M_delta;

        return $this;
    }

    public function getA3CDelta(): ?string
    {
        return $this->A3C_delta;
    }

    public function setA3CDelta(?string $A3C_delta): self
    {
        $this->A3C_delta = $A3C_delta;

        return $this;
    }

    public function getA4MDelta(): ?string
    {
        return $this->A4M_delta;
    }

    public function setA4MDelta(?string $A4M_delta): self
    {
        $this->A4M_delta = $A4M_delta;

        return $this;
    }

    public function getA4CDelta(): ?string
    {
        return $this->A4C_delta;
    }

    public function setA4CDelta(?string $A4C_delta): self
    {
        $this->A4C_delta = $A4C_delta;

        return $this;
    }

    public function getYellowDelta(): ?string
    {
        return $this->yellow_delta;
    }

    public function setYellowDelta(string $yellow_delta): self
    {
        $this->yellow_delta = $yellow_delta;

        return $this;
    }

    public function getMagentaDelta(): ?string
    {
        return $this->magenta_delta;
    }

    public function setMagentaDelta(string $magenta_delta): self
    {
        $this->magenta_delta = $magenta_delta;

        return $this;
    }

    public function getBlackDelta(): ?string
    {
        return $this->black_delta;
    }

    public function setBlackDelta(string $black_delta): self
    {
        $this->black_delta = $black_delta;

        return $this;
    }

    public function getCyanDelta(): ?string
    {
        return $this->cyan_delta;
    }

    public function setCyanDelta(string $cyan_delta): self
    {
        $this->cyan_delta = $cyan_delta;

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

    public function getMBRDelta(): ?string
    {
        return $this->MBR_delta;
    }

    public function setMBRDelta(?string $MBR_delta): self
    {
        $this->MBR_delta = $MBR_delta;

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

    public function getA4DMDelta(): ?string
    {
        return $this->A4DM_delta;
    }

    public function setA4DMDelta(?string $A4DM_delta): self
    {
        $this->A4DM_delta = $A4DM_delta;

        return $this;
    }

    public function getA4DCDelta(): ?string
    {
        return $this->A4DC_delta;
    }

    public function setA4DCDelta(?string $A4DC_delta): self
    {
        $this->A4DC_delta = $A4DC_delta;

        return $this;
    }

    public function getA3DCDelta(): ?string
    {
        return $this->A3DC_delta;
    }

    public function setA3DCDelta(?string $A3DC_delta): self
    {
        $this->A3DC_delta = $A3DC_delta;

        return $this;
    }

    public function getA3DMDelta(): ?string
    {
        return $this->A3DM_delta;
    }

    public function setA3DMDelta(?string $A3DM_delta): self
    {
        $this->A3DM_delta = $A3DM_delta;

        return $this;
    }
}
