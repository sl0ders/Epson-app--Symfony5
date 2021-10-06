<?php


namespace App\Traits;

use Doctrine\ORM\Mapping as ORM;

/**
 * Trait IdentityTrait
 * @package App\Traits
 * @author Quentin <quentin-sommesous@lidem.education>
 */
trait IdentityTrait
{
    /**
     * @ORM\Column(name="firstname", type = "string",  length=30, nullable=true)
     */
    private $firstname;

    /**
     * @ORM\Column(name="lastname", type = "string",  length=30, nullable=true)
     */
    private $lastname;

    /**
     * @ORM\Column(name="phone", type = "string",  length=30, nullable=true)
     */
    private $phone;

    /**
     * @ORM\Column(name="email", type="string", nullable=true)
     */
    private $email;

    /**
     * @return mixed
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param mixed $phone
     * @return IdentityTrait
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     * @return IdentityTrait
     */
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * @param mixed $firstname
     * @return IdentityTrait
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * @param mixed $lastname
     * @return IdentityTrait
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;
        return $this;
    }

    public function getFullName()
    {
        return $this->lastname. " " .$this->firstname;
    }
}
