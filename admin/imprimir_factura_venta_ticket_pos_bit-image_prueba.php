 
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
    $tux = EscposImage::load("../imagenes/logo_empresa_factura_pos.png", false);
    $printer -> text("These example images are printed with the older\nbit image print command. You should only use\n\$p -> bitImage() if \$p -> graphics() does not\nwork on your printer.\n\n");
    
    $printer -> bitImage($tux);
    $printer -> text("Regular Tux (bit image).\n");
    $printer -> feed();
    
    $printer -> bitImage($tux, Printer::IMG_DOUBLE_WIDTH);
    $printer -> text("Wide Tux (bit image).\n");
    $printer -> feed();
    
    $printer -> bitImage($tux, Printer::IMG_DOUBLE_HEIGHT);
    $printer -> text("Tall Tux (bit image).\n");
    $printer -> feed();
    
    $printer -> bitImage($tux, Printer::IMG_DOUBLE_WIDTH | Printer::IMG_DOUBLE_HEIGHT);
    $printer -> text("Large Tux in correct proportion (bit image).\n");
} catch (Exception $e) {
    /* Images not supported on your PHP, or image file not found */
    $printer -> text($e -> getMessage() . "\n");
}
$printer -> cut();
$printer -> close();