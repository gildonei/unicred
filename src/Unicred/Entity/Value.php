<?php

namespace Unicred\Entity;

use Unicred\Entity\AbstractEntity;

/**
 * Entity Value
 *
 * @author Gildonei Mendes Anacleto Junior <junior@sitecomarte.com.br>
 */
class Value extends AbstractEntity
{
    /**
     * Bank Slip's Value Indicator
     * @var int
     */
    private $indicator;

    /**
     * Indicates the date limit to apply discount, fine or interest
     * @var \DateTime
     */
    private $dateLimit;

    /**
     * Discount, Fine or Interest value
     * @var decimal
     */
    private $value;

    /**
     * Define the indicator of Discount, Fine or Interest
     * @param int $indicator
     * @return Value
     */
    public function setIndicator($indicator)
    {
        $this->indicator = $indicator;

        return $this;
    }

    /**
     * Return bank slip indicator
     * @return int
     */
    public function getIndicator()
    {
        return $this->indicator;
    }

    /**
     * Define the Discount, Fine or Interest value
     * @param decimal $value
     * @throws \InvalidArgumentException
     * @return Value
     */
    public function setValue($value)
    {
        setlocale(LC_NUMERIC, 'usa');
        if (!empty($this->getIndicator()) && empty($value)) {
            throw new \InvalidArgumentException('Value is empty!');
        }

        if (!preg_match('/^[1-9]\d*(\.\d+)?$/', $value)) {
            throw new \InvalidArgumentException('Value is not a decimal!');
        }
        $this->value = $value;

        return $this;
    }

    /**
     * Return discount, fine or interest value
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Define the date limit to apply discount, fine or interest
     * @param \DateTime $date
     * @throws \InvalidArgumentException
     * @return Value
     */
    function setDateLimit(\DateTime $date)
    {
        $this->dateLimit = $date;

        return $this;
    }

    /**
     * Return the date limit to apply discount, fine or interest
     * @return \DateTime
     */
    function getDateLimit()
    {
        return $this->dateLimit;
    }
}
