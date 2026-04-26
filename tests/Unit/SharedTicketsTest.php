<?php
namespace Tests\Unit;
use Tests\TestCase;
use App\Http\Controllers\Unicenta\UnicentaSharedTicketController;
use App\Models\UnicentaModels\SharedTicket;


class SharedTicketsTest extends TestCase
{

    /**
     * A basic test example.
     *
     * @return void
     */

    public function testTableHasNoOpenTicket(){
        $sharedTicketController = new UnicentaSharedTicketController();
        self::assertFalse($sharedTicketController->hasTicket(self::TABLENUMBER));
    }

    public function testInsertSharedTicket(){
        $sharedTicket = new SharedTicket();
        $sharedTicketController = new UnicentaSharedTicketController();
        $sharedTicketController->saveEmptyTicket($sharedTicket,self::TABLENUMBER);
        self::assertNotEmpty($sharedTicketController->getTicket(self::TABLENUMBER));
    }

    public function testUpdateSharedTicket(){
        $this->assertTrue(true);

    }

    public function testMoveTable(){
        $new_table_nr=222;
        $sharedTicketController = new UnicentaSharedTicketController();

        $sharedTicketController->moveTable(self::TABLENUMBER,$new_table_nr);
        self::assertNotEmpty($sharedTicketController->getTicket($new_table_nr));
        self::assertFalse($sharedTicketController->hasTicket(self::TABLENUMBER));
        $sharedTicketController->clearOpenTableTicket($new_table_nr);
        self::assertFalse($sharedTicketController->hasTicket($new_table_nr));
    }
    public function testClearSharedTicket(){
        $sharedTicketController = new UnicentaSharedTicketController();
        $sharedTicketController->clearOpenTableTicket(self::TABLENUMBER);
        self::assertFalse($sharedTicketController->hasTicket(self::TABLENUMBER));
    }



}
