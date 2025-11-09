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

/* Wallet IDs
 [8] => Portmanat
[9] => M10
[10] => BirBank
 */
$create_payment = $epoint->createWalletPayment(9, "2", "AZN", "test11", "Wallet Payment test",  "en");
//
$paymentUrl = $create_payment->getPaymentURL();
$transactionId = $create_payment->getTransactionId();

echo "Payment URL: <a href='" . $paymentUrl . "'>$paymentUrl</a><br/>";
echo "Transaction ID:" . $transactionId . "<br/>";