 
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

try {
    $printer->text("Hello World!\n");
    $printer->setColor(Escpos::COLOR_2);
    $printer->text("Red?!\n");
    $printer->setColor(Escpos::COLOR_1);
    $printer->text("Default color again?!\n");
    $printer->cut();
} finally {
    /* Always close the printer! */
    $printer->close();
}