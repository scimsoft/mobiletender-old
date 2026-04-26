<?php
/**
 * Created by PhpStorm.
 * User: Gerrit
 * Date: 16/10/2020
 * Time: 13:24
 */

namespace Tests\Unit;
use Tests\TestCase;

use function anInstanceOf;
use App\Http\Controllers\Unicenta\UnicentaSharedTicketController;
use App\Traits\SharedTicketTrait;
use App\Models\UnicentaModels\Product;
use App\Models\UnicentaModels\SharedTicket;
use App\Models\UnicentaModels\SharedTicketLines;
use App\Models\UnicentaModels\TicketLines;
use Illuminate\Support\Facades\DB;
use function json_encode;


class SharedTicketLineTest extends TestCase
{
    use SharedTicketTrait;

    protected $sharedTicket;

    public function testCreateSharedTicketWith2Products()
    {

        $this->sharedTicket = $this->createEmptyTicket();
        $this->saveEmptyTicket($this->sharedTicket,self::TABLENUMBER);

        $products[] = Product::all()->first();
        $products[] = Product::all()->get(2);
        $this->addProductsToTicket($products,self::TABLENUMBER);
       self::assertNotEmpty($this->sharedTicket->m_sId);


    }

    public function testGetProductList()
    {
        $sharedTicketController = new UnicentaSharedTicketController();
        $productlist = $sharedTicketController->getTicketLines(self::TABLENUMBER);
        //dd('ProductList'.$productlist);
        self::assertEquals(2, count($productlist));
    }

    public function testGetTicketLinesSum(){
        $sharedTicketController = new UnicentaSharedTicketController();
        $sumTicketLines = $sharedTicketController->getSumTicketLines(self::TABLENUMBER);
        self::assertEquals(12, round($sumTicketLines*1.1,2));
    }

    public function testAddOneTicketLine()
    {
        $product[] = Product::all()->first();
        $sharedTicketController = new UnicentaSharedTicketController();
        $sharedTicketController->addProductsToTicket($product, self::TABLENUMBER);
        $productlist = $sharedTicketController->getTicketLines(self::TABLENUMBER);
        self::assertEquals(3, count($productlist));
    }

    public function testAddMultipleTicketLines()
    {
        $allproducts = Product::all();
        //dd($allproducts);
        for ($i = 0; $i < 3; $i++) {
            $product[] = $allproducts[$i];

        }
        $sharedTicketController = new UnicentaSharedTicketController();
        $sharedTicketController->addProductsToTicket($product, self::TABLENUMBER);
        $productlist = $sharedTicketController->getTicketLines(self::TABLENUMBER);
        self::assertEquals(6, count($productlist));

    }

    public function testRemoveProduct()
    {
        $ticketLineNumber = 1;
        $sharedTicketController = new UnicentaSharedTicketController();
        $sharedTicketController->removeTicketLine(self::TABLENUMBER, $ticketLineNumber);
        $productlist = $sharedTicketController->getTicketLines(self::TABLENUMBER);
        self:
        self::assertEquals(5, count($productlist));

    }

    public function testSetTicketLinePrinted(){
        $ticketLineNumber = 0;
        $sharedTicketController = new UnicentaSharedTicketController();
        $sharedTicketController->setTicketLinePrinted(self::TABLENUMBER, $ticketLineNumber);
        $sharedTicket = $sharedTicketController->getTicket(self::TABLENUMBER);
        self::assertEquals('false',$sharedTicket->m_aLines[$ticketLineNumber]->updated);
    }

    public function NOtestGetSharedTicket2(){
        $sharedTicketController = new UnicentaSharedTicketController();
        $sharedTicket = $sharedTicketController->getTicket2(self::TABLENUMBER);

        self::assertTrue($sharedTicket instanceof SharedTicket);
    }

    public function testClearSharedTicket()
    {
        $sharedTicketController = new UnicentaSharedTicketController();
        $sharedTicketController->clearOpenTableTicket(self::TABLENUMBER);
        self::assertFalse($sharedTicketController->hasTicket(self::TABLENUMBER));

    }

}
