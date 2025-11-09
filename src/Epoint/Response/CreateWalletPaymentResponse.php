<?php
namespace FaganChalabizada\Epoint\Response;

class CreateWalletPaymentResponse extends APIResponse
{

    // Get the transaction ID
    public function getTransactionId() : ?string {
        return $this->data['transaction'] ?? null;
    }

    // Get the payment URL
    public function getPaymentURL() : ?string {
        return $this->data['redirect_url'] ?? null;
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