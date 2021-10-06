<?php

namespace App\Entity;

use App\Repository\UserRepository;
use App\Traits\AddressableTrait;
use App\Traits\IdentityTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(name="`user`")
 * @UniqueEntity(fields={"email"}, message="constraints.login.emailExist")
 */
class User implements UserInterface
{
    use IdentityTrait, AddressableTrait;
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     * @Assert\NotBlank(groups={"registration"})
     * @Assert\Length(min=7, groups={"registration"})
     */
    private $password;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isEmailRecipient;

    /**
     * @ORM\ManyToOne(targetEntity=Company::class, inversedBy="users")
     */
    private $company;

    /**
     * @ORM\OneToMany(targetEntity=OrderCartridge::class, mappedBy="user")
     */
    private $orderCartridges;

    /**
     * User constructor
     */
    public function __construct()
    {
        $this->orderCartridges = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }


    public function getIsEmailRecipient(): ?bool
    {
        return $this->isEmailRecipient;
    }

    public function setIsEmailRecipient(bool $isEmailRecipient): self
    {
        $this->isEmailRecipient = $isEmailRecipient;

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
            $orderCartridge->setUser($this);
        }

        return $this;
    }

    public function removeOrderCartridge(OrderCartridge $orderCartridge): self
    {
        if ($this->orderCartridges->removeElement($orderCartridge)) {
            // set the owning side to null (unless already changed)
            if ($orderCartridge->getUser() === $this) {
                $orderCartridge->setUser(null);
            }
        }

        return $this;
    }

}
