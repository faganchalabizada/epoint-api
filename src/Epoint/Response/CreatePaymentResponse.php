<?php
namespace FaganChalabizada\Epoint\Response;

class CreatePaymentResponse extends APIResponse
{

    // Get the transaction ID
    public function getTransactionId() : ?string {
        return $this->data['transaction'] ?? null;
    }

    // Get the payment URL
    public function getPaymentURL() : ?string {
        return $this->data['redirect_url'] ?? null;
    }
    
}