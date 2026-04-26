<?php
/**
 * Created by PhpStorm.
 * User: Gerrit
 * Date: 20/10/2020
 * Time: 13:10
 */

namespace App\Traits;


use App\Models\UnicentaModels\Product;
use App\Models\UnicentaModels\SharedTicket;
use App\Models\UnicentaModels\SharedTicketLines;
use App\Models\UnicentaModels\SharedTicketProduct;
use App\Models\UnicentaModels\SharedTicketUser;
use Carbon\Carbon;
use function count;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use function json_encode;
use function json_last_error;


trait SharedTicketTrait
{
    public function hasTicket($table_number)
    {
        $sharedTicket = DB::select('Select content from sharedtickets where id = ?', [$table_number]);
        if (count($sharedTicket) > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function getOpenOrders(){

        return DB::select('Select * from sharedtickets where length(id)<8 order by length(id)');

    }
    public function getTicket($table_number)
    {

        $existingticketlines = DB::select('Select content from sharedtickets where id = ?', [$table_number]);

        $ticketlinenumber = 0;
        $sharedTicket = $this->createEmptyTicket();
        if(count($existingticketlines)>0) {
            $productLists = json_decode($existingticketlines[0]->content)->m_aLines;

            foreach ($productLists as $productList) {
                $categoryid = $productList->attributes->{'product.categoryid'};
                $code = $productList->attributes->{'product.code'};
                $name = $productList->attributes->{'product.name'};
                $reference = $productList->attributes->{'product.reference'};
                $printto = $productList->attributes->{'product.printer'};
                $updated = $productList->attributes->{'ticket.updated'};
                $pricesell = $productList->price;
                $id = $productList->productid;
                $sharedTicketProduct = new SharedTicketProduct($reference, $name, $code, $categoryid, $printto, $pricesell, $id);
                $sharedTicketLine = new SharedTicketLines($sharedTicket->m_sId, $sharedTicketProduct, $ticketlinenumber,$updated);
                $sharedTicket->m_aLines[] = $sharedTicketLine;
                $ticketlinenumber = $ticketlinenumber + 1;

            }
        }
        return $sharedTicket;
    }

    /**
     * @return SharedTicket
     */
    public function createEmptyTicket()
    {
        $sharedTicket = new SharedTicket();
        $muser = new SharedTicketUser();
        $sharedTicket->m_User = $muser;
        $activeCash = DB::select('Select money FROM closedcash where dateend is null')[0];
        $sharedTicket->m_sActiveCash = $activeCash->money;
        return $sharedTicket;
    }

    public function saveEmptyTicket(SharedTicket $sharedTicket, $table_number)
    {
        $person = !empty(auth()->user())?$person = auth()->user()->name:'Guest';
        //INSERT empty sharedticket
        $jsonTicket = json_encode($sharedTicket);
        DB::insert(
            'INSERT into sharedtickets VALUES (?, ?, ?, 0, 0, null)',
            [$table_number, $person, $jsonTicket]
        );
    }


    public function getSumTicketLines($sharedTicketID){
        $sharedTicket = $this->getTicket($sharedTicketID);
        //dd($sharedTicket);
        $sum = 0;
        foreach ($sharedTicket->m_aLines as $line){
            $sum+=$line->price;
        }

        return $sum;
    }
    public function getSumTicketPartialLines($sharedTicketLines){

        //dd($sharedTicket);
        $sum = 0;
        foreach ($sharedTicketLines as $line){
            $sum+=$line->price;
        }

        return $sum;
    }

    public function getSumNewTicketLines($sharedTicketID){
        $sharedTicket = $this->getTicket($sharedTicketID);
        $sum = 0;
        foreach ($sharedTicket->m_aLines as $line){
            if($line->attributes->updated){$sum+=$line->price;}
        }

        return $sum;
    }



    public function addProductsToTicket($products, $table_number)
    {
        $sharedTicket = $this->getTicket($table_number);
        $muser = new SharedTicketUser();
        $sharedTicket->m_User = $muser;
        $numberLines = count($sharedTicket->m_aLines);
        foreach ($products as $product) {

            $numberLines += 1;
            $sharedTicket->m_aLines[] = new SharedTicketLines($sharedTicket->m_sId, $product, $numberLines);


        }
        $this->updateOpenTable($sharedTicket, $table_number);
    }

    public function removeTicketLine($table_number, $ticketLineNumber)
    {

        $sharedTicket = ($this->getTicket($table_number));
//dd($sharedTicket->m_aLines[$ticketLineNumber]->attributes->updated);
        if ($sharedTicket->m_aLines[$ticketLineNumber]->attributes->updated != "true") {

            $this->addLineRemoved($sharedTicket, $ticketLineNumber);
        }


            array_splice($sharedTicket->m_aLines, $ticketLineNumber, 1);
            $this->updateOpenTable($sharedTicket, $table_number);



    }

    public function removeTicketLines($table_number, $ticketLineNumbers)
    {
        $sharedTicket = ($this->getTicket($table_number));
//dd(count($ticketLineNumbers));
        for($i=0;$i<=count($ticketLineNumbers)-1;$i++){
            array_splice($sharedTicket->m_aLines, ($ticketLineNumbers[$i]->m_iLine)-$i, 1);
        }
        $this->updateOpenTable($sharedTicket, $table_number);
    }

    public function  getTicketLines($table_number)
    {

        $sharedTicket = $this->getTicket($table_number);
        $ticketLines = [];
        foreach ($sharedTicket->m_aLines as $ticketLine) {
            $ticketLines[] = $ticketLine;
        }
        return $ticketLines;

    }

    public function updateOpenTable(SharedTicket $sharedTicket, $table_number)
    {

        $json = json_encode($sharedTicket, JSON_UNESCAPED_UNICODE);
        DB::update('UPDATE sharedtickets SET content = ? WHERE id = ?', [$json, $table_number]);

        $occupied = Carbon::create($sharedTicket->m_dDate)->format('Y-m-d H:i:s');

        DB::update(
            'UPDATE places SET waiter = ?, ticketid = ?, occupied = ? WHERE (id = ?)',
            ['app', $sharedTicket->m_sId, $occupied, $table_number]
        );

    }
    public function moveTable($TABLENUMBER, $new_table_nr)
    {
        if($this->hasTicket($new_table_nr)){
            $this->mergeTicket($TABLENUMBER,$new_table_nr);
        }else {
            DB::update('update sharedtickets set id = ? where id = ?', [$new_table_nr, $TABLENUMBER]);
        }


    }

    public function mergeTicket($ticketID,$ticketIDtoMerge){
        $oldTicket=$this->getTicket($ticketID);
        $newTicket=$this->getTicket($ticketIDtoMerge);
        foreach ($oldTicket->m_aLines as $ticketLine) {
            $newTicket->m_aLines[] = $ticketLine;
        }
        $this->updateOpenTable($newTicket,$ticketIDtoMerge);
        $this->clearOpenTableTicket($ticketID);

    }
    public function  clearOpenTableTicket($table_number)
    {
        DB::delete('DELETE from sharedtickets WHERE id = ?', [$table_number]);
    }

    private function getUnprintedTicetLines(SharedTicket $ticket)
    {
        $lines_to_print = null;
        foreach ($ticket->m_aLines as $ticket_line) {
            if ($ticket_line->attributes->updated == 1) {
                $lines_to_print[] = $ticket_line;
            }
        }
        return $lines_to_print;
    }

    public function hasUnprintedTicketLines($tableNumber){
        $ticketLines= $this->getTicketLines($tableNumber);

        foreach ($ticketLines as $ticketLine) {
            //dd($ticketLines);
            if ($ticketLine->attributes->updated == 1) {
                Log::debug('Table:'.$tableNumber.' ticketline: '. $ticketLine->attributes->updated);
                return true;
            }
        }
        return false;
    }

    private function addLineRemoved(SharedTicket $ticket,int $ticketLineNumber){
        $now = Carbon::now();
        $user = (auth()->user())?auth()->user()->name:"Guest";
        $product= $ticket->m_aLines[$ticketLineNumber]->attributes->product;
        DB::insert(
            'INSERT INTO lineremoved VALUES (?, ?, ?, ?, ?, ?)',
            [
                $now->format('Y-m-d H:i:s'),
                $user,
                $ticket->m_sId,
                $product->id,
                $product->name,
                1,
            ]
        );

    }

}
