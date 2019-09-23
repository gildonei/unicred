<?php

namespace Unicred\Entity;

use Unicred\Entity\AbstractEntity;
use Unicred\Entity\Payer;

/**
 * Entity BankSlip
 *
 * @author Gildonei Mendes Anacleto Junior <junior@sitecomarte.com.br>
 */
class BankSlip extends AbstractEntity
{
    /**
     * Bank Slip's Payer
     * @var Payer
     */
    private $payer;

    /**
     * Yous system Id number
     * @var string
     */
    private $yourNumber;

    /**
     * Bill value
     * @var decimal
     */
    private $value;

    /**
     * Due date
     * @var \DateTime
     */
    private $dueDate;

    /**
     * Id generated on each bank slip request
     * @var string
     */
    private $bankSlipId;

    /**
     * Bank slip number
     * @var int
     */
    private $bankSlipNumber;

    /**
     * Bank slip number digit verification
     * @var int
     */
    private $bankSlipNumberDv;

    /**
     * Bank slip barcode
     * @var string
     */
    private $barcode;

    /**
     * Bank slip digitable line
     * @var string
     */
    private $digitLine;

    /**
     * Define bank slip payer
     * @param \Unicred\Entity\Payer $payer
     * @return Payer
     */
    public function setPayer(Payer $payer)
    {
        $this->payer = $payer;

        return $this;
    }

    /**
     * Return bank slip payer
     * @return Payer
     */
    public function getPayer()
    {
        if (empty($this->payer)) {
            $this->setPayer(new Payer());
        }

        return $this->payer;
    }

    /**
     * Define the invoice Id from your origin system
     * @param mixed $number
     * @throws \InvalidArgumentException
     * @return BankSlip
     */
    public function setYourNumber($number)
    {
        if (empty($number)) {
            throw new \InvalidArgumentException('Your Number is empty!');
        }

        $this->yourNumber = $number;

        return $this;
    }

    /**
     * Return the invoice Id from your origin system
     * @return string
     */
    public function getYourNumber()
    {
        return $this->yourNumber;
    }

    /**
     * Define invoice value
     * @param decimal $value
     * @throws \InvalidArgumentException
     * @return Payer
     */
    public function setValue($value)
    {
        if (empty($value)) {
            throw new \InvalidArgumentException('Bank Slip value is empty!');
        }
        if (!preg_match('/^[1-9]\d*(\.\d+)?$/', $value)) {
            throw new \InvalidArgumentException('Bank Slip value is not a decimal value!');
        }
        $this->value = $value;

        return $this;
    }

    /**
     * Retorn payer's corporate name
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Define bank slip due date
     * @param \DateTime $date
     * @throws \InvalidArgumentException
     * @return Payer
     */
    function setDueDate(\DateTime $date)
    {
        $this->dueDate = $date;

        return $this;
    }

    /**
     * Return bank slip due date
     * @return string
     */
    function getDueDate()
    {
        return $this->dueDate;
    }

    /**
     * Define bank slip number
     * @param int $number
     * @throws \InvalidArgumentException
     * @return Payer
     */
    function setBankSlipNumber($number)
    {
        if (empty($number)) {
            throw new \InvalidArgumentException('Bank Slip Number is empty!');
        }
        $this->bankSlipNumber = $number;
        $this->setBankSlipNumberDv($number);

        return $this;
    }

    /**
     * Return bank slip number
     * @return int
     */
    function getBankSlipNumber()
    {
        return $this->bankSlipNumber;
    }

    /**
     * Return bank slip number digit validation (modulo_11)
     * @return int
     */
    public function getBankSlipNumberDv()
    {
        return $this->bankSlipNumberDv;
    }

    /**
     * Define bank slip id
     * @param int $id
     * @throws \InvalidArgumentException
     * @return BankSlip
     */
    function setBankSlipId($id)
    {
        if (empty($id)) {
            throw new \InvalidArgumentException('Bank Slip Id is empty!');
        }
        $this->bankSlipId = $id;

        return $this;
    }

    /**
     * Return bank slip id
     * @return string
     */
    function getBankSlipId()
    {
        return $this->bankSlipId;
    }

    /**
     * Define bank slip barcode
     * @param int $number
     * @throws \InvalidArgumentException
     * @return BankSlip
     */
    function setBarcode($number)
    {
        if (empty($number)) {
            throw new \InvalidArgumentException('Barcode is empty!');
        }
        $this->barcode = $number;

        return $this;
    }

    /**
     * Return bank slip barcode
     * @return string
     */
    function getBarcode()
    {
        return $this->barcode;
    }

    /**
     * Define bank slip digitable line
     * @param int $number
     * @throws \InvalidArgumentException
     * @return BankSlip
     */
    function setDigitableLine($number)
    {
        if (empty($number)) {
            throw new \InvalidArgumentException('Barcode is empty!');
        }
        $this->digitLine = $number;

        return $this;
    }

    /**
     * Return bank slip digitable line
     * @return string
     */
    function getDigitableLine()
    {
        return $this->digitLine;
    }

    /**
     * Define bank slip number digit validation (modulo_11)
     * @param int $number
     * @param int $base
     * @return int $dv
     */
    private function setBankSlipNumberDv($number, $base=9)
    {
        $sum = 0;
        $factor = 2;
        $length = strlen($number);

        // Split numbers
        for ($i = $length; $i > 0; $i--) {
            $numeros[$i] = substr($number, $i - 1, 1);
            $parcial[$i] = $numeros[$i] * $factor;
            $sum += $parcial[$i];
            if ($factor == $base) {
                $factor = 1;
            }
            $factor++;
        }

        // Calculate the modulo11
        $sum *= 10;
        $digit = $sum % 11;
        if ($digit == 10) {
            $digit = 0;
        }

        $this->bankSlipNumberDv = $digit;
    }
}
