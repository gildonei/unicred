<?php

namespace Unicred\Request;

use Unicred\Entity\AbstractRequest;
use Unicred\Entity\Assignor;

/**
 * Class AuthenticationRequest
 *
 * @package Unicred\Request
 */
class AuthenticationRequest extends AbstractRequest
{
    /**
     * @var \Unicred\Environment
     */
    private $environment;

    /**
     * @var string
     */
    private $accessToken;

    /**
     * Constructor.
     *
     * @param Assignor $assignor
     * @param Environment $environment
     */
    public function __construct(Assignor $assignor, Environment $environment)
    {
        parent::__construct($assignor);

        $this->environment = $environment;
    }

    /**
     * @param array $param
     *      nomeUsuario => Assignor's username (login)
     *      senha => Assignor's password
     *
     * @return null
     * @throws \Unicred\Exception\UnicredRequestException
     * @throws \RuntimeException
     */
    public function execute($param = null)
    {
        $url = $this->environment->getApiUrl() . 'oauth2/v2/grant-token';

        if (empty($param)) {
            $param = [
                'nomeUsuario' => $this->getAssignor()->getUser(),
                'senha' => $this->getAssignor()->getPassword(),
            ];
        }

        return $this->sendRequest('POST', $url, $param);
    }

    /**
     * @param $json
     *
     * @return Authentication
     */
    protected function unserialize($json)
    {
        $response = json_decode($json);
        $this->setAcessToken($response->accessToken);

        return $this;
    }

    /**
     * Define Access Token
     * @param string $token
     * @throws \InvalidArgumentException
     * @return Assignor
     */
    public function setAcessToken($token)
    {
        if (empty($token)) {
            throw new \InvalidArgumentException('Acess Token is empty!');
        }
        $this->accessToken = $token;
        $this->getAssignor()->setAuthentication($this);

        return $this;
    }

    /**
     * Return Access Token
     * @return string
     */
    public function getAcessToken()
    {
        return $this->accessToken;
    }

    /**
     * Indicate if assignor is authenticated
     * @return bool
     */
    public function isAuthenticated()
    {
        return !empty($this->getAcessToken());
    }
}
