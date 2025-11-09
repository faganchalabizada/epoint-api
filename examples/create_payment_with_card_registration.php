<?php
require __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;
use FaganChalabizada\Epoint\EpointAPI;

// Load .env from project root
$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

// Read API keys from environment
$publicKey = $_ENV['PUBLIC_KEY'] ?? '';
$privateKey = $_ENV['PRIVATE_KEY'] ?? '';

$epoint = new EpointAPI($publicKey, $privateKey);

$create_payment = $epoint->createPayment("0.12", "AZN", 4, "dd", "aa.com", "aa.com", "en");

$paymentUrl = $create_payment->getPaymentURL();
$transactionId = $create_payment->getTransactionId();

echo "Payment URL: <a href='" . $paymentUrl . "'>$paymentUrl</a><br/>";
echo "Transaction ID:" . $transactionId . "<br/>";