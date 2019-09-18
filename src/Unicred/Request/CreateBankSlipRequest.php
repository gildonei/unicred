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
class CreateBankSlipRequest extends AbstractRequest
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
     * @param \Unicred\Entity\BankSlip $param
     * @return null
     * @throws \Unicred\Exception\UnicredRequestException
     * @throws \RuntimeException
     */
    public function execute($param = null)
    {
        if (!$param instanceof BankSlip) {
            throw new \InvalidArgumentException('Bank Slip not sent!!');
        }

        $url = "{$this->environment->getApiUrl()}cobranca/v2/beneficiarios/{$this->getAssignor()->getPayeeCode()}/titulos";

        $data = [
            'beneficiarioVariacaoCarteira' => $this->getAssignor()->getPortifolio(),
            'seuNumero' => $param->getYourNumber(),
            'valor' => $param->getValue(),
            'vencimento' => $param->getDueDate()->format('Y-m-d'),
            'nossoNumero' => str_pad($param->getBankSlipNumber() . $param->getBankSlipNumberDv(), 11, 0, STR_PAD_LEFT),
            'pagador' => [
                'nomeRazaoSocial' => $param->getPayer()->getCorporateName(),
                'tipoPessoa' => $param->getPayer()->getPayerType(),
                'numeroDocumento' => $param->getPayer()->getDocument(),
                'nomeFantasia' => $param->getPayer()->getName(),
                'email' => $param->getPayer()->getEmail(),
                'endereco' => [
                    'logradouro' => $param->getPayer()->getAddress()->getAddress(),
                    'bairro' => $param->getPayer()->getAddress()->getDistrict(),
                    'cidade' => $param->getPayer()->getAddress()->getCity(),
                    'uf' => $param->getPayer()->getAddress()->getState(),
                    'cep' => $param->getPayer()->getAddress()->getZip()
                ]
            ]
        ];

        return $this->sendRequest('POST', $url, $data, [
            "Authorization: bearer {$this->getAssignor()->getAuthentication()->getAcessToken()}",
            "Cooperativa: {$this->getAssignor()->getBankAgency()}",
        ]);
    }

    /**
     * @param $json
     * @return string Bank slip Id
     */
    protected function unserialize($json)
    {
        return $json;
    }
}
