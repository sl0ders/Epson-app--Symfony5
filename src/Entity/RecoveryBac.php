<?php

namespace App\Entity;

use App\Repository\RecoveryBacRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RecoveryBacRepository::class)
 */
class RecoveryBac
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $useByDay;


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
    private $rest_bac_percent;

    /**
     * @ORM\OneToOne(targetEntity=Printer::class, inversedBy="recoveryBac", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $printer;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $printAverage;


    public function getId(): ?int
    {
        return $this->id;
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

    public function getRestDays(): ?float
    {
        return $this->rest_days;
    }

    public function setRestDays(?float $rest_days): self
    {
        $this->rest_days = $rest_days;

        return $this;
    }

    public function getRestprints(): ?float
    {
        return $this->rest_prints;
    }

    public function setRestprints(?float $rest_prints): self
    {
        $this->rest_prints = $rest_prints;

        return $this;
    }

    public function getPrinter(): ?Printer
    {
        return $this->printer;
    }

    public function setPrinter(Printer $printer): self
    {
        $this->printer = $printer;

        return $this;
    }

    public function getRestBacPercent(): ?float
    {
        return $this->rest_bac_percent;
    }

    public function setRestBacPercent(?float $rest_bac_percent): self
    {
        $this->rest_bac_percent = $rest_bac_percent;

        return $this;
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

    public function getPrintAverage(): ?int
    {
        return $this->printAverage;
    }

    public function setPrintAverage(?int $printAverage): self
    {
        $this->printAverage = $printAverage;

        return $this;
    }
}
