<?php
require __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;
use FaganChalabizada\Epoint\EpointAPI;
use FaganChalabizada\Epoint\Exception\EpointException;

// Load .env from project root
$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

// Read API keys from environment
$publicKey = $_ENV['PUBLIC_KEY'] ?? '';
$privateKey = $_ENV['PRIVATE_KEY'] ?? '';

$epoint = new EpointAPI($publicKey, $privateKey, 1);

try {
    $check_payment = $epoint->checkPaymentStatus("te010437599");

    echo "\nGet status: " . $check_payment->getStatus()->value . "\n";
    echo "Get getMessage: " . $check_payment->getMessage() . "\n";
    echo "Get getBankResponse: " . $check_payment->getBankResponse() . "\n";;
    echo "Get getBankResponseCode: " . $check_payment->getBankResponseCode()->value . "\n";
    echo "Get getBankTransactionId: " . $check_payment->getBankTransactionId() . "\n";
    echo "Get getCardMask: " . $check_payment->getCardMask() . "\n";
    echo "Get getCardName: " . $check_payment->getCardName() . "\n";
    echo "Get getRrn: " . $check_payment->getRrn() . "\n";
    echo "Get getTransactionId: " . $check_payment->getTransactionId() . "\n";
    echo "Get getAmount: " . $check_payment->getAmount() . "\n";
    echo "Get getOperationCode: " . $check_payment->getOperationCode()->value . "\n";

} catch (EpointException $e) {
    echo $e->getMessage();
}
