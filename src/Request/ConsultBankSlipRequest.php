<?php

namespace Unicred\Request;

use Unicred\Entity\AbstractRequest;

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
            throw new InvalidArgumentException('Bank Slip not send!');
        }

        $url = "{$this->environment->getApiUrl()}cobranca/v2/beneficiarios/{$this->getAssignor()->getPayeeCode()}/titulos/{$param->getBankSlipId()}/status";

        $response = $this->sendRequest('GET', $url, null, [
            "Authorization: bearer {$this->getAssignor()->getAuthentication()->getAcessToken()}",
            "Cooperativa: {$this->getAssignor()->getBankAgency()}",
        ]);

        $param->setBarcode($response->codBarras);
        $param->setDigitableLine($response->linhaDigitavel);

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
