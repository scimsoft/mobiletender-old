<?php
/**
 * Created by PhpStorm.
 * User: Gerrit
 * Date: 27/10/2020
 * Time: 10:43
 */

namespace Tests\Unit;
use Tests\TestCase;


use App\Traits\PrinterTrait;
use Illuminate\Support\Facades\Log;
use function mb_convert_encoding;
use Mike42\Escpos\CapabilityProfile;
use Mike42\Escpos\PrintConnectors\FilePrintConnector;
use Mike42\Escpos\PrintConnectors\NetworkPrintConnector;
use Mike42\Escpos\Printer;


class PrinterTraitTest extends TestCase
{
    use PrinterTrait;
    const ESC = "\x1b";


    public function testPrinterConnection()
    {
        $printer = $this->connectToPrinter();
        self::assertNotEmpty($printer);
        $printer->close();
    }

    public function NotestPrintLine()
    {

        $connector = new NetworkPrintConnector(config('app.printer-ip'), config('app.printer-port'), 3);

        $profile = CapabilityProfile::load('xp-n160ii');

        $printer = new Printer($connector,$profile);

        $codePages = $profile -> getCodePages();

        $printer->selectCharacterTable(1145);

        $printer -> getPrintConnector()-> write(Printer::FS . ".");
        $printer -> getPrintConnector() -> write("Caña");

        $printer->text("Test");
        $printer->text("\n \n");



        $printer->close();
    }



    public function NotestPrintChars(){
        $connector = new NetworkPrintConnector(config('app.printer-ip'), config('app.printer-port'), 3);

        $profile = CapabilityProfile::load('xp-n160ii');

        $printer = new Printer($connector,$profile);
        //$printer->selectCharacterTable(53);

        $connector->write(Printer::FS . '.');
        for ($char = 1; $char <= 500; $char++) {

            $printer->text(chr($char));
        }
        $printer->text("\n");
        $printer->cut();
        $printer->close();
    }

    public function NOtestCodePageForSpanish(){
        $connector = new NetworkPrintConnector(config('app.printer-ip'), config('app.printer-port'), 3);

        $profile = CapabilityProfile::load('CT-S651');
        $printer = new Printer($connector,$profile);
        $codepages=$profile->getCodePages();




            $printer->setTextSize(2, 2);
            $printer->getPrintConnector()->write(PRINTER::ESC . "t" . chr(2) );

            $printer->text("\n");





        $printer->close();

}
    public function testPrinterCodePages()
    {
        $printer = $this->connectToPrinter();
        $printer->getPrintConnector()->write(PRINTER::ESC ."t"."2");
        $printer->textRaw("test");
        $printer->textRaw("ñ");
        $printer->textRaw(iconv( 'utf8','cp850',"ñ"));
        $printer->text("ñ");
        $printer->text("\n");
        $printer->close();
    }
}
