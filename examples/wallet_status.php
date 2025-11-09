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

$wallets = $epoint->getWallets();

print_r($wallets->getWallets());
/**
Array
(
[8] => Portmanat
[9] => M10
[10] => BirBank
)
 */