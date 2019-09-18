<?php

namespace Unicred\Request;

use Unicred\Entity\UnicredError;
use Unicred\Exception\UnicredRequestException;
use Unicred\Entity\Assignor;

/**
 * Class AbstractRequest
 *
 * @package Unicred\Requisicoes
 */
abstract class AbstractRequest
{
    /**
     * Assignor of the bank sli´p
     * @var Assignor
     */
    private $assignor;

    /**
     * Constructor
     *
     * @param \Unicred\Entity\Assignor $assignor
     */
    public function __construct(Assignor $assignor)
    {
        $this->assignor = $assignor;
    }

    /**
     * @param mixed $param
     *
     * @return mixed
     */
    public abstract function execute($param);

    /**
     * @param $method
     * @param $url
     * @param \JsonSerializable|null $content
     *
     * @return mixed
     *
     * @throws \Unicred\API30\Ecommerce\Request\UnicredRequestException
     * @throws \RuntimeException
     */
    protected function sendRequest($method, $url, $content = null, array $extraHeaders = [])
    {
        $headers = array_merge($extraHeaders, [
            'Accept: application/json',
            'Accept-Encoding: gzip',
            'User-Agent: Unicred/1.0 PHP API',
            'apiKey: ' . $this->assignor->getApiKey(),
            'RequestId: ' . uniqid()
        ]);

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1_2);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
        switch ($method) {
            case 'GET':
                break;
            case 'POST':
                curl_setopt($curl, CURLOPT_POST, true);
                break;
            default:
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
        }
        if ($content !== null) {
            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($content));
            $headers[] = 'Content-Type: application/json';
        } else {
            $headers[] = 'Content-Length: 0';
        }

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        $response   = curl_exec($curl);
        $statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        if (curl_errno($curl)) {
            throw new \RuntimeException('Curl error: ' . curl_error($curl));
        }
        curl_close($curl);

        return $this->readResponse($statusCode, $response);
    }

    /**
     * @param $statusCode
     * @param $responseBody
     *
     * @return mixed
     *
     * @throws UnicredRequestException
     */
    protected function readResponse($statusCode, $responseBody)
    {
        $unserialized = null;
        switch ($statusCode) {
            case 200:
            case 201:
                $unserialized = $this->unserialize($responseBody);
                break;

            case 400:
            case 422:
                $exception = null;
                $response  = json_decode($responseBody);

                if (isset($response->body)) {
                    foreach ($response->body as $error) {
                        $unicredError = new UnicredError($error->message, $statusCode);
                        $exception = new UnicredRequestException('Request Error', $statusCode, $exception);
                        $exception->setUnicredError($unicredError);
                    }
                } else {
                    $unicredError = new UnicredError($response->message, $statusCode);
                    $exception = new UnicredRequestException('Request Error', $statusCode, null);
                    $exception->setUnicredError($unicredError);
                }
                throw $exception;

            case 401:
            case 500:
                $response = json_decode($responseBody);
                $unicredError = new UnicredError($response->message, $statusCode);
                $exception = new UnicredRequestException('Request Error', $statusCode, null);
                $exception->setUnicredError($unicredError);
                throw $exception;

            case 404:
                throw new UnicredRequestException('Resource not found', 404, null);

            default:
                throw new UnicredRequestException('Unknown status', $statusCode);
        }

        return $unserialized;
    }

    /**
     * @param $json
     *
     * @return mixed
     */
    protected abstract function unserialize($json);

    /**
     * @return Assignor
     */
    protected function getAssignor()
    {
        return $this->assignor;
    }
}
