<?php

namespace FaganChalabizada\Epoint\Response;

use FaganChalabizada\Epoint\Enums\PaymentStatus;
use FaganChalabizada\Epoint\Enums\OperationCode;
use FaganChalabizada\Epoint\Enums\BankResponseCode;

class GetPaymentStatusResponse extends APIResponse
{

    /**
     * Check if the API request was successful.
     *
     * @return bool True if the status is "success", false otherwise.
     */
    public function isSuccess(): bool
    {
        return ($this->data['status'] ?? 'error') !== 'error';
    }

    // Get the payment status as an enum
    public function getStatus(): PaymentStatus
    {
        return isset($this->data['status']) ? PaymentStatus::from($this->data['status']) : PaymentStatus::UNDEFINED;
    }

    // Get the bank response code as an enum
    public function getBankResponseCode(): BankResponseCode
    {
        return isset($this->data['code']) ? BankResponseCode::from($this->data['code']) : BankResponseCode::UNDEFINED;
    }

    // Get the payment status message
    public function getMessage(): ?string
    {
        return $this->data['message'] ?? null;
    }

    // Get the Epoint transaction ID
    public function getTransactionId(): ?string
    {
        return $this->data['transaction'] ?? null;
    }

    // Get the bank transaction ID
    public function getBankTransactionId(): ?string
    {
        return $this->data['bank_transaction'] ?? null;
    }

    // Get the bank's response details
    public function getBankResponse(): ?string
    {
        return $this->data['bank_response'] ?? null;
    }

    // Get the operation code as an enum
    public function getOperationCode(): OperationCode
    {
        return isset($this->data['operation_code']) ? OperationCode::from($this->data['operation_code']) : OperationCode::UNDEFINED;
    }

    // Get the Retrieval Reference Number (RRN)
    public function getRrn(): ?string
    {
        return $this->data['rrn'] ?? null;
    }

    // Get the cardholder's name
    public function getCardName(): ?string
    {
        return $this->data['card_name'] ?? null;
    }

    // Get the masked card number (e.g., 123456******1234)
    public function getCardMask(): ?string
    {
        return $this->data['card_mask'] ?? null;
    }

    // Get the payment amount
    public function getAmount(): ?float
    {
        return isset($this->data['amount']) ? (float)$this->data['amount'] : null;
    }

    // Get additional attributes (if any)
    public function getOtherAttributes(): ?array
    {
        return $this->data['other_attr'] ?? null;
    }
}