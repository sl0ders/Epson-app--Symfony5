<?php


namespace App\Traits;

use Doctrine\ORM\Mapping as ORM;

/**
 * Trait AddressableTrait
 * @package App\Traits
 * @author Quentin <quentin-sommesous@lidem.education>
 */
trait AddressableTrait
{

    /**
     * @ORM\Column(name="zip_code", type="integer", nullable=true)
     */
    private $zipCode;

    /**
     * @ORM\Column(name="city", type="string", nullable=true)
     */
    private $city;

    /**
     * @ORM\Column(name="country", type="string", nullable=true)
     */
    private $country;

    /**
     * @ORM\Column(name="street", type="string", nullable=true)
     */
    private $street;

    /**
     * @ORM\Column(name="street_number", type="bigint", nullable=true)
     */
    private $streetNumber;

    public function getFullAddress(): string
    {
        return $this->streetNumber." ".$this->street." ".$this->zipCode." ".$this->country;
    }

    /**
     * @return mixed
     */
    public function getZipCode()
    {
        return $this->zipCode;
    }

    /**
     * @param mixed $zipCode
     * @return AddressableTrait
     */
    public function setZipCode($zipCode)
    {
        $this->zipCode = $zipCode;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param mixed $city
     * @return AddressableTrait
     */
    public function setCity($city)
    {
        $this->city = $city;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param mixed $country
     */
    public function setCountry($country)
    {
        $this->country = $country;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getStreet()
    {
        return $this->street;
    }

    /**
     * @param mixed $street
     * @return AddressableTrait
     */
    public function setStreet($street)
    {
        $this->street = $street;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getStreetNumber()
    {
        return $this->streetNumber;
    }

    /**
     * @param mixed $streetNumber
     * @return AddressableTrait
     */
    public function setStreetNumber($streetNumber)
    {
        $this->streetNumber = $streetNumber;
        return $this;
    }

}
