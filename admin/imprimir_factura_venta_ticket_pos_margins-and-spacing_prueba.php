 
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

$printer -> setEmphasis(true);
$printer -> text("Left margin\n");
$printer -> setEmphasis(false);
$printer -> text("Default left\n");
foreach(array(1, 2, 4, 8, 16, 32, 64, 128, 256, 512) as $margin) {
    $printer -> setPrintLeftMargin($margin);
    $printer -> text("left margin $margin\n");
}
/* Reset left */
$printer -> setPrintLeftMargin(0);
/* Stuff around with page width */
$printer -> setEmphasis(true);
$printer -> text("Page width\n");
$printer -> setEmphasis(false);
$printer -> setJustification(Printer::JUSTIFY_RIGHT);
$printer -> text("Default width\n");
foreach(array(512, 256, 128, 64) as $width) {
    $printer -> setPrintWidth($width);
    $printer -> text("page width $width\n");
}
/* Printer shutdown */
$printer -> cut();
$printer -> close();