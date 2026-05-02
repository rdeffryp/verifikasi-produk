<?php
require_once __DIR__ . '/../vendor/autoload.php';

use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;

$qrCode = QrCode::create('TEST-QR-123')
    ->setSize(300)
    ->setMargin(10);

$writer = new PngWriter();
$result = $writer->write($qrCode);

header('Content-Type: image/png');
echo $result->getString();