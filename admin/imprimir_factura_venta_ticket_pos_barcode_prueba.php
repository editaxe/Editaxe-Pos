 
<?php 
require __DIR__ . '/ticket/autoload.php'; //Nota: si renombraste la carpeta a algo diferente de "ticket" cambia el nombre en esta línea
use Mike42\Escpos\Printer;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
//use Mike42\Escpos\CapabilityProfiles\EposTepCapabilityProfile;
/*  Este ejemplo imprime un Ticket de venta desde una impresora térmica */
/* Aquí, en lugar de "POS" (que es el nombre de mi impresora)   escribe el nombre de la tuya. Recuerda que debes compartirla    desde el panel de control */

$connector = new WindowsPrintConnector("POS-80C");
$printer = new Printer($connector);
// Most simple example
$pdf = '../pdf/archpdf.pdf';

$justification = array(Printer::JUSTIFY_LEFT, Printer::JUSTIFY_CENTER, Printer::JUSTIFY_RIGHT);
for ($i = 0; $i < count($justification); $i++) {
    $printer->setJustification($justification[$i]);
    $printer->text("A man a plan a canal panama\n");
}
$printer->setJustification();
// Reset
$printer->cut();
/* Barcodes - see barcode.php for more detail */
$printer->setBarcodeHeight(80);
$printer->setBarcodeTextPosition(Printer::BARCODE_TEXT_BELOW);
$printer->barcode("9876");
$printer->feed();
$printer->cut();
/* Graphics - this demo will not work on some non-Epson printers */
try {
    $logo = EscposImage::load("../imagenes/logo_empresa_factura_pos.png", false);
    $imgModes = array(Printer::IMG_DEFAULT, Printer::IMG_DOUBLE_WIDTH, Printer::IMG_DOUBLE_HEIGHT, Printer::IMG_DOUBLE_WIDTH | Printer::IMG_DOUBLE_HEIGHT);
    foreach ($imgModes as $mode) {
        $printer->graphics($logo, $mode);
    }
} catch (Exception $e) {
    /* Images not supported on your PHP, or image file not found */
    $printer->text($e->getMessage() . "\n");
}
$printer->cut();