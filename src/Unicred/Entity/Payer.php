<?php

namespace Unicred\Entity;

use Unicred\Entity\AbstractEntity;
use Unicred\Entity\Address;

/**
 * Entity Payer
 *
 * @author Gildonei Mendes Anacleto Junior <junior@sitecomarte.com.br>
 */
class Payer extends AbstractEntity
{
    /**
     * Person payer type
     */
    const PERSON = 'F';

    /**
     * Company payer type
     */
    const COMPANY = 'J';

    /**
     * Payer name
     * @var string
     */
    private $name;

    /**
     * Payer's corporate name
     * @var string
     */
    private $corporateName;

    /**
     * Payer type
     * @var int
     */
    private $payerType;

    /**
     * Allowed payer types
     * @var int
     */
    private $allowedPayerType = [self::PERSON, self::COMPANY];

    /**
     * Payers document Id
     * @var int
     */
    private $document;

    /**
     * Payer's email
     * @var string
     */
    private $email;

    /**
     * Payer's address
     * @var Address
     */
    private $address;

    /**
     * Define payer type
     *      F => Person
     *      P => Company
     * @param string $type
     * @throws \InvalidArgumentException
     * @return Payer
     */
    public function setPayerType($type)
    {
        if (empty($type)) {
            throw new \InvalidArgumentException('Payer type is empty!');
        }

        $tipoPagador = strtoupper($type);
        if (!in_array($tipoPagador, $this->allowedPayerType)) {
            throw new \InvalidArgumentException('Payer type invalid!');
        }

        $this->payerType = $tipoPagador;

        return $this;
    }

    /**
     * Return payer type
     * @return string
     */
    public function getPayerType()
    {
        return $this->payerType;
    }

    /**
     * Define payer's name
     * @param string $name
     * @throws \InvalidArgumentException
     * @return Payer
     */
    public function setName($name)
    {
        if (empty($name)) {
            throw new \InvalidArgumentException('Name is empty!');
        }
        $this->name = $name;

        return $this;
    }

    /**
     * Return payer's name
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Define payer's corporate name
     * @param string $name
     * @throws \InvalidArgumentException
     * @return Payer
     */
    public function setCorporateName($name)
    {
        if (empty($name)) {
            throw new \InvalidArgumentException('Corporate name is empty!');
        }
        $this->corporateName = $name;

        return $this;
    }

    /**
     * Return payer's corporate name
     * @return string
     */
    public function getCorporateName()
    {
        return $this->corporateName;
    }

    /**
     * Define payer's document Id
     * @param string $document
     * @throws \InvalidArgumentException
     * @return Payer
     */
    function setDocument($document)
    {
        if (empty($document)) {
            throw new \InvalidArgumentException('Document is empty!');
        }

        $this->document = $this->validateDocument($document);

        return $this;
    }

    /**
     * Return payer's document Id
     * @return string
     */
    function getDocument()
    {
        return $this->document;
    }

    /**
     * Define payer's email address
     * @param string $email
     * @throws \InvalidArgumentException
     * @return Payer
     */
    function setEmail($email)
    {
        if (empty($email)) {
            throw new \InvalidArgumentException('Email is empty!');
        }
        $this->email = strtolower($email);

        return $this;
    }

    /**
     * Return payer's email address
     * @return string
     */
    function getEmail()
    {
        return $this->email;
    }

    /**
     * Define payer's address
     * @param string Address $address
     * @throws \InvalidArgumentException
     * @return Payer
     */
    public function setAddress(Address $address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Return payer address
     * @return type
     */
    function getAddress()
    {
        if (empty($this->address)) {
            $this->setAddress(new Address());
        }

        return $this->address;
    }

    /**
     * Valida o documento do pagador de acordo com o tipo de pagador e retorna o
     * número informado caso seja válido ou gera exceção em caso de erro
     * @param int $document
     * @return string
     * @throws \InvalidArgumentException
     */
    private function validateDocument($document)
    {
        switch ($this->getPayerType()) {
            case 'F':
                if (!$this->validatePersonId($document)) {
                    throw new \InvalidArgumentException('Invalid Person Document!');
                }
                break;
            case 'J':
                if (!$this->validateCompanyId($document)) {
                    throw new \InvalidArgumentException('Invalid Company Document!');
                }
                break;
            default:
                throw new \InvalidArgumentException('Document Type inválid for validation!');
        }

        return $document;
    }

    /**
     * Validate Person document Id (CPF)
     * @param mixed $document
     * @return bool
     */
    private function validatePersonId($document)
    {
        $document = (string)str_pad($this->onlyNumbers($document), 11, 0, STR_PAD_LEFT);
        // If CPF repeat or is a Fake
        if (preg_match('/(\d)\1{10}/', $document) ||
            in_array($document, ['01234567890', '12345678909'])) {
            return false;
        }

        for ($t = 9; $t < 11; $t++) {
            for ($d = 0, $c = 0; $c < $t; $c++) {
                $d += $document[$c] * (($t + 1) - $c);
            }
            $d = ((10 * $d) % 11) % 10;
            if ($document[$c] != $d) {
                return false;
            }
        }

        return true;
    }

    /**
     * Validate company document Id (CNPJ)
     * @param mixed $document
     * @return bool
     */
    private function validateCompanyId($document)
    {
        $document = (string)str_pad($this->onlyNumbers($document), 14, 0, STR_PAD_LEFT);
        if (preg_match('/(\d)\1{13}/', $document)){
            return false;
        }

        if (preg_match('/(\d)\1{13}/', $document)){
            return false;
        }

        for ($i = 0, $j = 5, $soma = 0; $i < 12; $i++) {
            $soma += $document[$i] * $j;
            $j = ($j == 2) ? 9 : $j - 1;
        }

        $restoDv1 = $soma % 11;
        if ($document[12] != ($restoDv1 < 2 ? 0 : 11 - $restoDv1))
            return false;

        for ($i = 0, $j = 6, $soma = 0; $i < 13; $i++) {
            $soma += $document[$i] * $j;
            $j = ($j == 2) ? 9 : $j - 1;
        }

        $restoDv2 = $soma % 11;

        return $document[13] == ($restoDv2 < 2 ? 0 : 11 - $restoDv2);
    }

    /**
     * Remove all non numbers characters
     * @param mixed $numero
     * @return double
     */
    private function onlyNumbers($numero)
    {
        return (double)preg_replace('/[^0-9]/', '', $numero);
    }
}
