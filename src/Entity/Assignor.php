<?php

namespace Unicred\Entity;

use Unicred\Entity\AbstractEntity;

/**
 * Entity Assignor
 *
 * @author Gildonei Mendes Anacleto Junior <junior@sitecomarte.com.br>
 */
class Assignor extends AbstractEntity
{
    /**
     * Username (login)
     * @var string
     */
    private $username;

    /**
     * Password
     * @var string
     */
    private $password;

    /**
     * Api Key
     * @var string
     */
    private $apiKey;

    /**
     * Payee code
     * @var string
     */
    private $payeeCode;

    /**
     * @var int
     */
    private $portifolioNumber;

    /**
     * Number of agency bank
     * @var int
     */
    private $bankAgency;

    /**
     * @var AuthenticationRequest
     */
    private $authenticationRequest;

    /**
     * Define username
     * @param string $username
     * @throws \InvalidArgumentException
     * @return Assignor
     */
    public function setUser($username)
    {
        if (empty($username)) {
            throw new \InvalidArgumentException('Username is empty!');
        }

        $this->username = $username;

        return $this;
    }

    /**
     * Return username
     * @return string
     */
    public function getUser()
    {
        return $this->username;
    }

    /**
     * Define password
     * @param string $pswd
     * @throws \InvalidArgumentException
     * @return Assignor
     */
    public function setPassword($pswd)
    {
        if (empty($pswd)) {
            throw new \InvalidArgumentException('Password is empty!');
        }
        $this->password = $pswd;

        return $this;
    }

    /**
     * Return password
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Define Api Key
     * @param string $key
     * @throws \InvalidArgumentException
     * @return Assignor
     */
    public function setApiKey($key)
    {
        if (empty($key)) {
            throw new \InvalidArgumentException('API Key is empty!');
        }
        $this->apiKey = $key;

        return $this;
    }

    /**
     * Return Api Key
     * @return string
     */
    public function getApiKey()
    {
        return $this->apiKey;
    }

    /**
     * Define Bank Agency
     * @param string $agency
     * @throws \InvalidArgumentException
     * @return Assignor
     */
    public function setBankAgency($agency)
    {
        if (empty($agency)) {
            throw new \InvalidArgumentException('Bank Agency is empty!');
        }
        $this->bankAgency = $agency;

        return $this;
    }

    /**
     * Return Bank Agency
     * @return int
     */
    public function getBankAgency()
    {
        return $this->bankAgency;
    }

    /**
     * Define Api Key
     * @param string $key
     * @throws \InvalidArgumentException
     * @return Assignor
     */
    public function setPayeeCode($key)
    {
        if (empty($key)) {
            throw new \InvalidArgumentException('Payee Code is empty!');
        }
        $this->payeeCode = $key;

        return $this;
    }

    /**
     * Return Api Key
     * @return string
     */
    public function getPayeeCode()
    {
        return $this->payeeCode;
    }

    /**
     * @param AuthenticationRequest $authentication
     * @throws \InvalidArgumentException
     * @return Assignor
     */
    public function setAuthentication(AuthenticationRequest $authentication)
    {
        $this->authenticationRequest = $authentication;

        return $this;
    }

    /**
     * Return Authentication request
     * @return AuthenticationRequest
     */
    public function getAuthentication()
    {
        return $this->authenticationRequest;
    }


    /**
     * Define Portifolio Number
     * @param int $number
     * @throws \InvalidArgumentException
     * @return Assignor
     */
    public function setPortifolio($number)
    {
        if (empty($number)) {
            throw new \InvalidArgumentException('Portifolio Number is empty!');
        }
        if (!is_numeric($number)) {
            throw new \InvalidArgumentException('Portifolio must contain only numbers!');
        }
        $this->portifolioNumber = $number;

        return $this;
    }

    /**
     * Return Api Key
     * @return int
     */
    public function getPortifolio()
    {
        return $this->portifolioNumber;
    }
}
