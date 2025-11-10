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

$epoint = new EpointAPI($publicKey, $privateKey, 1);

$create_widget = $epoint->createWidgetUri("0.12", "test16", "Apple&Google Pay widget");

$widgetUrl = $create_widget->getWidgetURL();

echo "Widget URL: <a href='" . $widgetUrl . "'>$widgetUrl</a><br/>";
