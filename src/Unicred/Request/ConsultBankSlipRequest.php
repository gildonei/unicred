<?php

namespace Unicred\Request;

use Unicred\Environment;
use Unicred\Entity\Assignor;
use Unicred\Entity\BankSlip;
use Unicred\Request\AbstractRequest;

/**
 * Class InvoiceRequest
 *
 * @package Unicred\Request
 */
class ConsultBankSlipRequest extends AbstractRequest
{
    /**
     * @var \Unicred\Environment
     */
    private $environment;

    /**
     * Constructor
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
     * @param BankSlip $param
     * @return BankSlip
     * @throws \Unicred\Exception\UnicredRequestException
     * @throws \RuntimeException
     */
    public function execute($param = null)
    {
        if (!$param instanceof BankSlip) {
            throw new \InvalidArgumentException('Bank Slip not sent!');
        }

        $url = "{$this->environment->getApiUrl()}cobranca/v2/beneficiarios/{$this->getAssignor()->getPayeeCode()}/titulos/{$param->getBankSlipId()}/status";

        $response = $this->sendRequest('GET', $url, null, [
            "Authorization: bearer {$this->getAssignor()->getAuthentication()->getAcessToken()}",
            "Cooperativa: {$this->getAssignor()->getBankAgency()}",
        ]);

        // Sometimes response comes null with no reason
        if (empty($response)) {
            return null;
        }

        $param->setDueDate(new \DateTime($response->dataDeVencimento));
        $param->setValue($response->valor);
        $param->setStatus($response->status);
        $param->setReason($response->motivo);
        $param->setBarcode($response->codBarras);
        $param->setDigitableLine($response->linhaDigitavel);

        if ($response->nossoNumero) {
            $param->setBankSlipNumber(substr($response->nossoNumero, 0, -1));
        }

        return $param;
    }

    /**
     * @param $json
     * @return \stdClass Bank slip barcode, due data, digitable line and value
     */
    protected function unserialize($json)
    {
        return json_decode($json);
    }
}
