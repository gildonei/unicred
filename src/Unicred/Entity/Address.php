<?php

namespace Unicred\Entity;

use Unicred\Entity\AbstractEntity;

/**
 * Entity Address
 *
 * @author Gildonei Mendes Anacleto Junior <junior@sitecomarte.com.br>
 */
class Address extends AbstractEntity
{
    /**
     * Address name
     * @var string
     */
    private $address;

    /**
     * District of the city
     * @var string
     */
    private $district;

    /**
     * Nome da cidade
     * @var string
     */
    private $city;

    /**
     * Initial state name
     * @var string
     */
    private $state;

    /**
     * Array allowed initial state name
     * @var array
     */
    private $allowedStates = ['AC', 'AL', 'AM', 'AP', 'BA', 'CE', 'DF',
        'ES', 'GO', 'MA', 'MG', 'MT', 'MS', 'PA', 'PB', 'PE', 'PI',
        'PR', 'RJ', 'RN', 'RO', 'RR', 'RS', 'SC', 'SP', 'SE', 'TO'];

    /**
     * Número do cep com 8 dígitos
     * @var int
     */
    private $zip;

    /**
     * Define the address name
     * @param string $address
     * @throws \InvalidArgumentException
     * @return Address
     */
    public function setAddress($address)
    {
        if (empty($address)) {
            throw new \InvalidArgumentException('Address is empty!');
        }
        $this->address = $address;

        return $this;
    }

    /**
     * Return addresses name
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Define the district's name
     * @param string $district
     * @throws \InvalidArgumentException
     * @return Address
     */
    function setDistrict($district)
    {
        if (empty($district)) {
            throw new \InvalidArgumentException('District is empty!');
        }
        $this->district = $district;

        return $this;
    }

    /**
     * Return the district's name
     * @return string
     */
    function getDistrict()
    {
        return $this->district;
    }

    /**
     * Define city name
     * @param string $city
     * @throws \InvalidArgumentException
     * @return Address
     */
    function setCity($city)
    {
        if (empty($city)) {
            throw new \InvalidArgumentException('City não informada!');
        }
        $this->city = $city;

        return $this;
    }

    /**
     * Return the city name
     * @return string
     */
    function getCity()
    {
        return $this->city;
    }

    /**
     * Define the state's initial name with two letters
     * @param string $state Sigla
     * @throws \InvalidArgumentException
     * @return Address
     */
    public function setState($state)
    {
        if (!in_array(strtoupper($state), $this->allowedStates)) {
            throw new \InvalidArgumentException("Initial of State is invalid!");
        }

        $this->state = $state;

        return $this;
    }

    /**
     * Return the state initial name
     * @return string
     */
    function getState()
    {
        return $this->state;
    }

    /**
     * Define o número do cep
     * @param int $zip
     * @throws \InvalidArgumentException
     * @return Address
     */
    function setZip($zip)
    {
        $nrZip = preg_replace('/[^0-9]/', '', $zip);
        if (empty($nrZip)) {
            throw new \InvalidArgumentException('ZIP Code is empty!');
        }
        if (strlen($nrZip) <> 8) {
            throw new \InvalidArgumentException('CEP Code must contain 8 numbers!');
        }

        $this->zip = (int)$nrZip;

        return $this;
    }

    /**
     * Return the zip code number
     * @return int
     */
    function getZip()
    {
        return $this->zip;
    }
}
