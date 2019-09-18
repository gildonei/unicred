<?php

namespace Unicred\Exception;

use Unicred\Entity\UnicredError;

/**
 * Class UnicredException
 *
 * @package Unicred\Exception
 */
class UnicredRequestException extends \Exception
{
    /**
     * Entity of UnicredError
     * @var \UnicredError
     */
    private $unicredError;

    /**
     * UnicredException constructor.
     *
     * @param string $message
     * @param int    $code
     * @param null   $previous
     */
    public function __construct($message, $code, $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    /**
     * @return mixed
     */
    public function getUnicredError()
    {
        return $this->unicredError;
    }

    /**
     * @param UnicredError $unicredError
     *
     * @return $this
     */
    public function setUnicredError(UnicredError $unicredError)
    {
        $this->unicredError = $unicredError;

        return $this;
    }
}
