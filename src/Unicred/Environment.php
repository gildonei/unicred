<?php

namespace Unicred;

/**
 * Class Environment
 *
 * @package Unicred
 */
class Environment 
{
    /**
     * API address
     * @var string
     */
    private $api;

    /**
     * Environment constructor.
     *
     * @param $api
     * @param $apiQuery
     */
    private function __construct($api)
    {
        $this->api = $api;
    }

    /**
     * @return Environment
     */
    public static function sandbox()
    {
        $api = 'https://api.e-unicred.com.br/homolog/';

        return new Environment($api);
    }

    /**
     * @return Environment
     */
    public static function production()
    {
        $api = 'https://api.e-unicred.com.br/';

        return new Environment($api);
    }

    /**
     * Gets the environment's Api URL
     *
     * @return string the Api URL
     */
    public function getApiUrl()
    {
        return $this->api;
    }
}
