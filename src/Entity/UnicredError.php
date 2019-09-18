<?php

namespace Unicred\Entity;

/**
 * Class UnicredError
 *
 * @package Unicred\Request
 */
class UnicredError
{
    /**
     * Error code from Unicred API
     * @var int
     */
    private $code;

    /**
     * Error message from Unicred API
     * @var string
     */
    private $message;

    /**
     * Constructor
     *
     * @param $message
     * @param $code
     */
    public function __construct($message, $code)
    {
        $this->setCode($code);
        $this->setMessage($message);
    }

    /**
     * Return the error code from Unicred API
     * @return mixed
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Define the error code from Unicred API
     * @param int $code
     * @return UnicredError
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Return the error message from Unicred API
     * @return mixed
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Define the error message from Unicred API
     * @param string $message
     * @return UnicredError
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }
}
