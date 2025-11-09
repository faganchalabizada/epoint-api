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

$epoint = new EpointAPI($publicKey, $privateKey);

$data = $_POST['data'];
$signature = $_POST['signature'];

try {
    $result = $epoint->processCallback($data, $signature);

    print_r($result);

} catch (EpointException $e) {
    echo $e->getMessage();
}
