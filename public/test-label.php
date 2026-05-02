<?php
require_once __DIR__ . '/../vendor/autoload.php';

use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;

// 1. Generate QR Code sebagai PNG string
$qrCode = QrCode::create('TEST-RZ20260128-A3B9')
    ->setSize(300)
    ->setMargin(5);

$writer = new PngWriter();
$result = $writer->write($qrCode);

// 2. Load template label
$template = imagecreatefrompng(__DIR__ . '/label-template.png');
$templateWidth = imagesx($template);
$templateHeight = imagesy($template);

// 3. Load QR dari string
$qrImage = imagecreatefromstring($result->getString());

// 4. Hitung ukuran label dalam pixel (asumsi 96 DPI)
// Label: 10cm x 7cm → 10cm/2.54*96 = 378px, 7cm/2.54*96 = 264px
// QR area: 2.5cm x 2.5cm → 95px x 95px
// Posisi X: 6.07cm → 229px, Posisi Y: 4.14cm → 156px

$qrSizePx = 295;
$posX = 717;
$posY = 489;

// 5. Resize QR ke ukuran yang sesuai
$qrResized = imagecreatetruecolor($qrSizePx, $qrSizePx);
imagecopyresampled($qrResized, $qrImage, 0, 0, 0, 0, $qrSizePx, $qrSizePx, imagesx($qrImage), imagesy($qrImage));

// 6. Tempel QR ke template
imagecopy($template, $qrResized, $posX, $posY, 0, 0, $qrSizePx, $qrSizePx);

// 7. Output hasil
header('Content-Type: image/png');
imagepng($template);

imagedestroy($template);
imagedestroy($qrImage);
imagedestroy($qrResized);