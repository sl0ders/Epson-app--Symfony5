<?php

namespace App\Entity;

use App\Repository\ReportRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ReportRepository::class)
 */
class Report
{
    const SUBJECT_PRINT= "report.subject.print";
    const SUBJECT_INK= "report.subject.ink";
    const SUBJECT_ORDER= "report.subject.order";

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $reportCode;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\ManyToOne(targetEntity=company::class, inversedBy="reports")
     * @ORM\JoinColumn(nullable=false)
     */
    private $company;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $subject;

    /**
     * @ORM\Column(type="boolean")
     */
    private $statut;

    /**
     * @ORM\OneToMany(targetEntity=MessageReport::class, mappedBy="report")
     */
    private $messageReports;

    /**
     * @ORM\ManyToOne(targetEntity=Printer::class, inversedBy="reports")
     */
    private $printer;

    /**
     * @ORM\ManyToOne(targetEntity=Cartridge::class, inversedBy="reports")
     */
    private $ink;

    /**
     * @ORM\ManyToOne(targetEntity=orderCartridge::class, inversedBy="reports")
     */
    private $orderCartridge;

    public function __construct()
    {
        $this->messageReports = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReportCode(): ?string
    {
        return $this->reportCode;
    }

    public function setReportCode(string $reportCode): self
    {
        $this->reportCode = $reportCode;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getCompany(): ?company
    {
        return $this->company;
    }

    public function setCompany(?company $company): self
    {
        $this->company = $company;

        return $this;
    }

    public function getSubject(): ?string
    {
        return $this->subject;
    }

    public function setSubject(?string $subject): self
    {
        $this->subject = $subject;

        return $this;
    }

    public function getStatut(): ?bool
    {
        return $this->statut;
    }

    public function setStatut(bool $statut): self
    {
        $this->statut = $statut;

        return $this;
    }

    /**
     * @return Collection|MessageReport[]
     */
    public function getMessageReports(): Collection
    {
        return $this->messageReports;
    }

    public function addMessageReport(MessageReport $messageReport): self
    {
        if (!$this->messageReports->contains($messageReport)) {
            $this->messageReports[] = $messageReport;
            $messageReport->setReport($this);
        }

        return $this;
    }

    public function removeMessageReport(MessageReport $messageReport): self
    {
        if ($this->messageReports->removeElement($messageReport)) {
            // set the owning side to null (unless already changed)
            if ($messageReport->getReport() === $this) {
                $messageReport->setReport(null);
            }
        }

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

    public function getInk(): ?cartridge
    {
        return $this->ink;
    }

    public function setInk(?cartridge $ink): self
    {
        $this->ink = $ink;

        return $this;
    }

    public function getOrderCartridge(): ?orderCartridge
    {
        return $this->orderCartridge;
    }

    public function setOrderCartridge(?orderCartridge $orderCartridge): self
    {
        $this->orderCartridge = $orderCartridge;

        return $this;
    }
}
