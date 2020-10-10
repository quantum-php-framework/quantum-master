<?php
namespace PaymentGateway\Entities;

/**
 * Class Address
 * @package PaymentGateway\Entities
 */
class Address
{
    /**
     * @var
     */
    public $name;
    /**
     * @var
     */
    public $street_address;
    /**
     * @var
     */
    public $street_address2;
    /**
     * @var
     */
    public $city;
    /**
     * @var
     */
    public $state;
    /**
     * @var
     */
    public $country;
    /**
     * @var
     */
    public $zip;


    /**
     * Address constructor.
     */
    public function __construct() {}

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getStreetAddress()
    {
        return $this->street_address;
    }

    /**
     * @param mixed $street_address
     */
    public function setStreetAddress($street_address)
    {
        $this->street_address = $street_address;
    }

    /**
     * @return mixed
     */
    public function getStreetAddress2()
    {
        return $this->street_address2;
    }

    /**
     * @param mixed $street_address2
     */
    public function setStreetAddress2($street_address2 = "")
    {
        $this->street_address2 = $street_address2;
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
     */
    public function setCity($city)
    {
        $this->city = $city;
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
    public function setState($state)
    {
        $this->state = $state;
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
    }

    /**
     * @return mixed
     */
    public function getZip()
    {
        return $this->zip;
    }

    /**
     * @param mixed $zip
     */
    public function setZip($zip)
    {
        $this->zip = $zip;
    }


}