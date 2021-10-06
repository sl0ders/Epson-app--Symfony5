<?php

namespace App\Entity;

use App\Repository\CompanyRepository;
use App\Traits\AddressableTrait;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Serializable;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass=CompanyRepository::class)
 * @Vich\Uploadable
 */
class Company implements Serializable
{
    use AddressableTrait;

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
     * @ORM\OneToMany(targetEntity=Printer::class, mappedBy="company")
     */
    private $printers;

    /**
     * @ORM\OneToMany(targetEntity=User::class, mappedBy="company")
     */
    private $users;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isEnabled;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Email(message="constraints.company.email.format")
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $phone;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $fax;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $website;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $socialReason;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $siren;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $siret;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $shortDescription;

    /**
     * @ORM\OneToMany(targetEntity=Notification::class, mappedBy="sender")
     */
    private $notifications;

    /**
     * @ORM\Column(type="integer", scale=4)
     */
    private $inkBreakingUpLvl;

    /**
     * @ORM\Column(type="integer", scale=4)
     */
    private $inkBreakingUpDays;

    /**
     * @ORM\Column(type="integer", scale=4)
     */
    private $bacBreakingUpLvl;

    /**
     * @ORM\Column(type="integer", scale=4)
     */
    private $bacBreakingUpDays;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @var string
     */
    private $logo_name;

    /**
     * @Vich\UploadableField(mapping="logo_company", fileNameProperty="logo_name")
     * @Assert\Image(mimeTypes={"image/jpeg", "image/jpg", "image/JPG", "image/JPEG", "image/PNG", "image/png"})
     * @var File|null
     */
    private $logo;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @var DateTime
     */
    private $updatedAt;

    /**
     * @ORM\OneToMany(targetEntity=OrderCartridge::class, mappedBy="client")
     */
    private $orderCartridges;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $code;

    /**
     * @ORM\OneToMany(targetEntity=Notification::class, mappedBy="receiver")
     */
    private $adminNotification;

    /**
     * @ORM\OneToMany(targetEntity=Report::class, mappedBy="company")
     */
    private $reports;

    /**
     * @ORM\OneToMany(targetEntity=MessageReport::class, mappedBy="sender")
     */
    private $messageReports;

    public function __construct()
    {
        $this->printers = new ArrayCollection();
        $this->users = new ArrayCollection();
        $this->notifications = new ArrayCollection();
        $this->orderCartridges = new ArrayCollection();
        $this->adminNotification = new ArrayCollection();
        $this->reports = new ArrayCollection();
        $this->messageReports = new ArrayCollection();
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

    /**
     * @return Collection|Printer[]
     */
    public function getPrinters(): Collection
    {
        return $this->printers;
    }

    public function addPrinter(Printer $printer): self
    {
        if (!$this->printers->contains($printer)) {
            $this->printers[] = $printer;
            $printer->setCompany($this);
        }

        return $this;
    }

    public function removePrinter(Printer $printer): self
    {
        if ($this->printers->removeElement($printer)) {
            // set the owning side to null (unless already changed)
            if ($printer->getCompany() === $this) {
                $printer->setCompany(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->getName();
    }

    /**
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->setCompany($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getCompany() === $this) {
                $user->setCompany(null);
            }
        }

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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getFax(): ?string
    {
        return $this->fax;
    }

    public function setFax(?string $fax): self
    {
        $this->fax = $fax;

        return $this;
    }

    public function getWebsite(): ?string
    {
        return $this->website;
    }

    public function setWebsite(?string $website): self
    {
        $this->website = $website;

        return $this;
    }

    public function getSocialReason(): ?string
    {
        return $this->socialReason;
    }

    public function setSocialReason(?string $socialReason): self
    {
        $this->socialReason = $socialReason;

        return $this;
    }

    public function getSiren(): ?string
    {
        return $this->siren;
    }

    public function setSiren(?string $siren): self
    {
        $this->siren = $siren;

        return $this;
    }

    public function getSiret(): ?string
    {
        return $this->siret;
    }

    public function setSiret(?string $siret): self
    {
        $this->siret = $siret;

        return $this;
    }

    public function getShortDescription(): ?string
    {
        return $this->shortDescription;
    }

    public function setShortDescription(?string $shortDescription): self
    {
        $this->shortDescription = $shortDescription;

        return $this;
    }

    /**
     * @return Collection|Notification[]
     */
    public function getNotifications(): Collection
    {
        return $this->notifications;
    }

    public function addNotification(Notification $notification): self
    {
        if (!$this->notifications->contains($notification)) {
            $this->notifications[] = $notification;
            $notification->setSender($this);
        }
        return $this;
    }

    public function removeNotification(Notification $notification): self
    {
        if ($this->notifications->removeElement($notification)) {
            // set the owning side to null (unless already changed)
            if ($notification->getSender() === $this) {
                $notification->setSender(null);
            }
        }

        return $this;
    }

    /**
     * @return DateTime
     */
    public function getUpdatedAt(): DateTime
    {
        return $this->updatedAt;
    }

    /**
     * @param DateTime $updatedAt
     * @return Company
     */
    public function setUpdatedAt(DateTime $updatedAt): Company
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLogoName()
    {
        return $this->logo_name;
    }

    /**
     * @param mixed $logo_name
     * @return Company
     */
    public function setLogoName($logo_name): Company
    {
        $this->logo_name = $logo_name;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLogo(): ?File
    {
        return $this->logo;
    }

    /**
     * @param mixed $logo
     * @return Company
     */
    public function setLogo($logo): Company
    {
        $this->logo = $logo;
        if ($logo) {
            $this->updatedAt = new DateTime('now');
        }
        return $this;
    }

    public function serialize()
    {
        // TODO: Implement serialize() method.
    }

    public function unserialize($serialized)
    {
        // TODO: Implement unserialize() method.
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
            $orderCartridge->setClient($this);
        }

        return $this;
    }

    public function removeOrderCartridge(OrderCartridge $orderCartridge): self
    {
        if ($this->orderCartridges->removeElement($orderCartridge)) {
            // set the owning side to null (unless already changed)
            if ($orderCartridge->getClient() === $this) {
                $orderCartridge->setClient(null);
            }
        }

        return $this;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    /**
     * @return Collection|Notification[]
     */
    public function getAdminNotification(): Collection
    {
        return $this->adminNotification;
    }

    public function addadminNotification(Notification $adminNotification): self
    {
        if (!$this->adminNotification->contains($adminNotification)) {
            $this->adminNotification[] = $adminNotification;
            $adminNotification->setReceiver($this);
        }

        return $this;
    }

    public function removeadminNotification(Notification $adminNotification): self
    {
        if ($this->adminNotification->removeElement($adminNotification)) {
            // set the owning side to null (unless already changed)
            if ($adminNotification->getReceiver() === $this) {
                $adminNotification->setReceiver(null);
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
            $report->setCompany($this);
        }

        return $this;
    }

    public function removeReport(Report $report): self
    {
        if ($this->reports->removeElement($report)) {
            // set the owning side to null (unless already changed)
            if ($report->getCompany() === $this) {
                $report->setCompany(null);
            }
        }

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
            $messageReport->setSender($this);
        }

        return $this;
    }

    public function removeMessageReport(MessageReport $messageReport): self
    {
        if ($this->messageReports->removeElement($messageReport)) {
            // set the owning side to null (unless already changed)
            if ($messageReport->getSender() === $this) {
                $messageReport->setSender(null);
            }
        }

        return $this;
    }

    /**
     * @return mixed
     */
    public function getInkBreakingUpLvl()
    {
        return $this->inkBreakingUpLvl;
    }

    /**
     * @param mixed $inkBreakingUpLvl
     * @return Company
     */
    public function setInkBreakingUpLvl($inkBreakingUpLvl): Company
    {
        $this->inkBreakingUpLvl = $inkBreakingUpLvl;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getInkBreakingUpDays()
    {
        return $this->inkBreakingUpDays;
    }

    /**
     * @param mixed $inkBreakingUpDays
     * @return Company
     */
    public function setInkBreakingUpDays($inkBreakingUpDays): Company
    {
        $this->inkBreakingUpDays = $inkBreakingUpDays;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getBacBreakingUpLvl()
    {
        return $this->bacBreakingUpLvl;
    }

    /**
     * @param mixed $bacBreakingUpLvl
     * @return Company
     */
    public function setBacBreakingUpLvl($bacBreakingUpLvl): Company
    {
        $this->bacBreakingUpLvl = $bacBreakingUpLvl;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getBacBreakingUpDays()
    {
        return $this->bacBreakingUpDays;
    }

    /**
     * @param mixed $bacBreakingUpDays
     * @return Company
     */
    public function setBacBreakingUpDays($bacBreakingUpDays): Company
    {
        $this->bacBreakingUpDays = $bacBreakingUpDays;
        return $this;
    }


}
