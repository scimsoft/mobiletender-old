<?php
/**
 * Created by PhpStorm.
 * User: Gerrit
 * Date: 24/10/2020
 * Time: 18:00
 */

namespace Tests\Unit;
use Tests\TestCase;


use App\Http\Controllers\BasketController;
use App\Http\Controllers\Unicenta\UnicentaSharedTicketController;
use App\Traits\SharedTicketTrait;
use App\Models\UnicentaModels\Product;
use function assert;
use Illuminate\Support\Facades\Log;
use function json_encode;

class CheckoutControllerTest extends TestCase
{
    use SharedTicketTrait;

    public function setUp(): void
    {
        parent::setUp();


    }

    public function testPrintOrderChangeUpdateStatus(){
        $this->saveEmptyTicket($this->createEmptyTicket(),self::TABLENUMBER);
        $products[] = Product::first();
        $this->addProductsToTicket($products,self::TABLENUMBER);
        $ticket = $this->getTicket(self::TABLENUMBER);
       foreach ($ticket->m_aLines as $ticketLine) {
           self::assertEquals(true, $ticketLine->attributes->updated);
       }
       $checkoutController= new BasketController();
       $checkoutController->printOrder(self::TABLENUMBER);
        $ticket = $this->getTicket(self::TABLENUMBER);
        foreach ($ticket->m_aLines as $ticketLine) {
            self::assertEquals(false, $ticketLine->attributes->updated);
        }
    }

    public function testMergeTicketIDS(){

        self::assertEquals(1,count($this->getTicketLines(self::TABLENUMBER)));
        $this->saveEmptyTicket($this->createEmptyTicket(),self::NEWRABLENUMBER);
        $products[] = Product::first();
        $this->addProductsToTicket($products,self::NEWRABLENUMBER);
        self::assertEquals(1,count($this->getTicketLines(self::NEWRABLENUMBER)));

        $this->mergeTicket(self::NEWRABLENUMBER,self::TABLENUMBER);

        self::assertEquals(2,count($this->getTicketLines(self::TABLENUMBER)));
        self::assertFalse($this->hasTicket(self::NEWRABLENUMBER));


    }
    public function testCleanTicketID()
    {
        $ticketController = new UnicentaSharedTicketController();
        $ticketController->clearOpenTableTicket(self::TABLENUMBER);
        self::assertEmpty($ticketController->getTicketLines(self::TABLENUMBER));

    }


}