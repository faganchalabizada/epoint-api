<?php

namespace FaganChalabizada\Epoint\Response;

use FaganChalabizada\Epoint\Enums\PaymentStatus;
use FaganChalabizada\Epoint\Enums\OperationCode;
use FaganChalabizada\Epoint\Enums\BankResponseCode;
use FaganChalabizada\Epoint\Exception\EpointException;

class GetWalletStatusResponse extends APIResponse
{

    // Get the list of available wallets (m10, BirBank etc)
    public function getWallets(): array
    {
        return $this->data;
    }


    /**
     * Check if the API request was successful.
     *
     * @return bool True if the status is "success", false otherwise.
     */
    public function isSuccess(): bool
    {
        return ($this->data['status'] ?? 'success') === 'success';
    }

}