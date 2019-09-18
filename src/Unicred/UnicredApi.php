<?php

namespace Unicred;

use Unicred\Request\CreateBankSlipRequest;
use Unicred\Request\ConsultBankSlipRequest;

/**
 * The Unicred API for creation and consult of bank slip.
 *
 * @package \Unicred
 */
class UnicredApi
{
    /**
     * @var \Unicred\Entity\Assignor
     */
    private $assignor;

    /**
     * @var \Unicred\Environment
     */
    private $environment;

    /**
     * Create an instance of UnicredApi choosing the environment where the
     * requests will be send
     *      ::production
     *      ::sandbox
     *
     * @param Assignor $assignor The merchant credentials
     * @param Environment environment
     */
    public function __construct(Assignor $assignor, Environment $environment = null)
    {
        if ($environment == null) {
            $environment = Environment::production();
        }

        $this->assignor = $assignor;
        $this->environment = $environment;
    }

    /**
     * Send the bank slip to Unicred and return the Id of generated bankSlip
     *
     * @param BankSlip $bankSlip The preconfigured Sale
     * @return string Invoice Id.
     * @throws \Unicred\Exception\UnicredRequestException if anything gets wrong.
     */
    public function createBankSlip(BankSlip $bankSlip)
    {
        $createBankSlipRequest = new CreateBankSlipRequest($this->assignor, $this->environment);

        return $createBankSlipRequest->execute($bankSlip);
    }

    /**
     * Consult the bank slip and return an array with barcode, digit line,
     * due date and value
     *
     * @param string $bankSlipId The id that is retrievid on a new bank slip
     * @return array
     * @throws \Unicred\Exception\UnicredRequestException if anything gets wrong.
     */
    public function consultBankSlip($bankSlipId)
    {
        $consultBankSlipRequest = new ConsultBankSlipRequest($this->assignor, $this->environment);

        return $consultBankSlipRequest->execute($bankSlipId);
    }
}
