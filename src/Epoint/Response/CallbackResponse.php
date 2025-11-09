<?php

namespace FaganChalabizada\Epoint\Response;

use FaganChalabizada\Epoint\Enums\OperationCode;
use FaganChalabizada\Epoint\Enums\PaymentStatus;

class CallbackResponse
{
    public function __construct(
        public readonly ?string $orderId,
        public readonly ?PaymentStatus $status,
        public readonly ?string $code,
        public readonly ?string $message,
        public readonly ?string $transaction,
        public readonly ?string $bankTransaction,
        public readonly ?string $bankResponse,
        public readonly ?OperationCode $operationCode,
        public readonly ?string $rrn,
        public readonly ?string $cardName,
        public readonly ?string $cardMask,
        public readonly ?float $amount,
        public readonly ?array $otherAttributes
    ) {}
}