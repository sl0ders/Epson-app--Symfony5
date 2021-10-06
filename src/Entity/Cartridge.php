<?php

namespace App\Entity;

use App\Repository\CartridgeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CartridgeRepository::class)
 */
class Cartridge
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $size;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $serial_number;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $color;

    /**
     * @ORM\ManyToOne(targetEntity=Printer::class, inversedBy="cartridge")
     * @ORM\JoinColumn(nullable=false)
     */
    private $printer;

    /**
     * @ORM\Column(type="bigint", nullable=true)
     */
    private $printAverage;

    /**
     * @ORM\OneToMany(targetEntity=OrderCartridge::class, mappedBy="cartridge", orphanRemoval=true)
     */
    private $orderCartridges;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $rest_days;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $rest_prints;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $useByDay;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $rest_print_bw;

    /**
     * @return mixed
     */
    public function getRestPrintColor()
    {
        return $this->rest_print_color;
    }

    /**
     * @param mixed $rest_print_color
     * @return Cartridge
     */
    public function setRestPrintColor($rest_print_color)
    {
        $this->rest_print_color = $rest_print_color;
        return $this;
    }

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $rest_print_color;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $rest_ink_percent;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $colorCode;

    /**
     * @ORM\OneToMany(targetEntity=Report::class, mappedBy="ink")
     */
    private $reports;

    public function __construct()
    {
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

    public function getSize(): ?string
    {
        return $this->size;
    }

    public function setSize(string $size): self
    {
        $this->size = $size;

        return $this;
    }

    public function getSerialNumber(): ?string
    {
        return $this->serial_number;
    }

    public function setSerialNumber(?string $serial_number): self
    {
        $this->serial_number = $serial_number;

        return $this;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(string $color): self
    {
        $this->color = $color;

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


    public function getPrintAverage(): ?string
    {
        return $this->printAverage;
    }

    public function setPrintAverage(?string $printAverage): self
    {
        $this->printAverage = $printAverage;

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
            $orderCartridge->setCartridge($this);
        }

        return $this;
    }

    public function removeOrderCartridge(OrderCartridge $orderCartridge): self
    {
        if ($this->orderCartridges->removeElement($orderCartridge)) {
            // set the owning side to null (unless already changed)
            if ($orderCartridge->getCartridge() === $this) {
                $orderCartridge->setCartridge(null);
            }
        }

        return $this;
    }

    public function getRestDays(): ?float
    {
        return $this->rest_days;
    }

    public function setRestDays(?float $rest_days): self
    {
        $this->rest_days = $rest_days;

        return $this;
    }

    public function getRestPrints(): ?float
    {
        return $this->rest_prints;
    }

    public function setRestPrints(?float $rest_prints): self
    {
        $this->rest_prints = $rest_prints;

        return $this;
    }

    public function getUseByDay(): ?float
    {
        return $this->useByDay;
    }

    public function setUseByDay(?float $useByDay): self
    {
        $this->useByDay = $useByDay;

        return $this;
    }

    public function getRestInkPercent(): ?int
    {
        return $this->rest_ink_percent;
    }

    public function setRestInkPercent(int $rest_ink_percent): self
    {
        $this->rest_ink_percent = $rest_ink_percent;

        return $this;
    }

    public function getColorCode(): ?string
    {
        return $this->colorCode;
    }

    public function setColorCode(string $colorCode): self
    {
        $this->colorCode = $colorCode;

        return $this;
    }

    /**
     * @param mixed $rest_print_bw
     * @return Cartridge
     */
    public function setRestPrintBw($rest_print_bw): Cartridge
    {
        $this->rest_print_bw = $rest_print_bw;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getRestPrintBw()
    {
        return $this->rest_print_bw;
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
            $report->setInk($this);
        }

        return $this;
    }

    public function removeReport(Report $report): self
    {
        if ($this->reports->removeElement($report)) {
            // set the owning side to null (unless already changed)
            if ($report->getInk() === $this) {
                $report->setInk(null);
            }
        }

        return $this;
    }
}
