 
<?php 
require __DIR__ . '/ticket/autoload.php'; //Nota: si renombraste la carpeta a algo diferente de "ticket" cambia el nombre en esta línea
use Mike42\Escpos\Printer;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
//use Mike42\Escpos\CapabilityProfiles\EposTepCapabilityProfile;
/* 	Este ejemplo imprime un Ticket de venta desde una impresora térmica */
/* Aquí, en lugar de "POS" (que es el nombre de mi impresora) 	escribe el nombre de la tuya. Recuerda que debes compartirla 	desde el panel de control */

$connector = new WindowsPrintConnector("POS-80C");
$printer = new Printer($connector);
// Most simple example
$pdf = '../pdf/archpdf.pdf';
try {
    $pages = ImagickEscposImage::loadPdf($pdf);
    foreach ($pages as $page) {
        $printer -> graphics($page);
    }
    $printer -> cut();
} catch (Exception $e) {
    /*
     * loadPdf() throws exceptions if files or not found, or you don't have the
     * imagick extension to read PDF's
     */
    echo $e -> getMessage() . "\n";
} finally {
    $printer -> close();
}
/*
 * 2: Speed up printing by roughly halving the resolution, and printing double-size.
 * This gives a 75% speed increase at the expense of some quality.
 * 
 * Reduce the page width further if necessary: if it extends past the printing area, your prints will be very slow.
 */
$connector = new FilePrintConnector("php://stdout");
$printer = new Printer($connector);
$pdf = 'resources/document.pdf';
$pages = ImagickEscposImage::loadPdf($pdf, 260);
foreach ($pages as $page) {
    $printer -> graphics($page, Printer::IMG_DOUBLE_HEIGHT | Printer::IMG_DOUBLE_WIDTH);
}
$printer -> cut();
$printer -> close();
/*
 * 3: PDF printing still too slow? If you regularly print the same files, serialize & compress your
 * EscposImage objects (after printing[1]), instead of throwing them away.
 * 
 * (You can also do this to print logos on computers which don't have an
 * image processing library, by preparing a serialized version of your logo on your PC)
 * 
 * [1]After printing, the pixels are loaded and formatted for the print command you used, so even a raspberry pi can print complex PDF's quickly.
 */
$connector = new FilePrintConnector("php://stdout");
$printer = new Printer($connector);
$pdf = 'resources/document.pdf';
$ser = 'resources/document.z';
if (!file_exists($ser)) {
    $pages = ImagickEscposImage::loadPdf($pdf);
} else {
    $pages = unserialize(gzuncompress(file_get_contents($ser)));
}
foreach ($pages as $page) {
    $printer -> graphics($page);
}
$printer -> cut();
$printer -> close();
if (!file_exists($ser)) {
    file_put_contents($ser, gzcompress(serialize($pages)));
}