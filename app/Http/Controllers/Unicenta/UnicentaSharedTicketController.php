<?php

namespace App\Http\Controllers\Unicenta;

use App\Http\Controllers\Controller;
use App\Models\UnicentaModels\SharedTicket;
use App\Models\UnicentaModels\SharedTicketLines;
use App\Models\UnicentaModels\SharedTicketProduct;
use App\Models\UnicentaModels\SharedTicketUser;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Traits\SharedTicketTrait;
use function json_decode;
use function PHPUnit\Framework\isEmpty;

class UnicentaSharedTicketController extends Controller
{
    //
    use SharedTicketTrait;









    public function getTicket2($table_number){
        $existingticketlines = DB::select('Select content from sharedtickets where id = ?', [$table_number]);
        if (count($existingticketlines) === 0) {
            abort(404);
        }
        $sharedTicket = json_decode($existingticketlines[0]->content);
        return $sharedTicket;
    }
    //TODO move object creacion from json to constructor












    public function setTicketLinePrinted($TABLENUMBER, $ticketLineNumber)
    {
        $sharedTicket = ($this->getTicket($TABLENUMBER));
        $ticketLine = $sharedTicket->m_aLines[$ticketLineNumber];
        $ticketLine->updated = false;
        $this->updateOpenTable($sharedTicket, $TABLENUMBER);

    }



}
