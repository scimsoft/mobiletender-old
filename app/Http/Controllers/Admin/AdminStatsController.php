<?php
/**
 * Created by PhpStorm.
 * User: Gerrit
 * Date: 23/07/2021
 * Time: 17:44
 */

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Traits\UnicentaPayedTrait;
use function compact;
use Illuminate\Support\Facades\DB;

class AdminStatsController extends Controller
{
    use UnicentaPayedTrait;

    public function index()
    {
        $ventaLinesHoy = DB::select("SELECT payments.PAYMENT, SUM(ticketlines.UNITS*ticketlines.PRICE*1.1) AS TOTAL
FROM tickets, ticketlines, receipts, closedcash,payments
WHERE ticketlines.TICKET = receipts.ID
AND closedcash.MONEY = receipts.MONEY
AND ticketlines.TICKET = tickets.Id
and tickets.Id = payments.RECEIPT
AND date(receipts.DATENEW)>= (CURDATE())
AND time(receipts.DATENEW) >= '10:00'
GROUP BY payments.PAYMENT");

        $ventaLinesHoyNight = DB::select("SELECT payments.PAYMENT, SUM(ticketlines.UNITS*ticketlines.PRICE*1.1) AS TOTAL
FROM tickets, ticketlines, receipts, closedcash,payments
WHERE ticketlines.TICKET = receipts.ID
AND closedcash.MONEY = receipts.MONEY
AND ticketlines.TICKET = tickets.Id
and tickets.Id = payments.RECEIPT
AND date(receipts.DATENEW)>= (CURDATE())
AND time(receipts.DATENEW) <= '10:00'
GROUP BY payments.PAYMENT");
        $cajaActual = $this->getClosedCash();

        $categoriesHoy = DB::select('SELECT  categories.name as NAME, COUNT(categories.name) AS CHECKS, SUM(ticketlines.UNITS*ticketlines.PRICE*1.1) AS TOTAL
FROM tickets, ticketlines, receipts,
 closedcash,products,categories
WHERE ticketlines.TICKET = receipts.ID
AND closedcash.MONEY = receipts.MONEY
AND ticketlines.TICKET = tickets.Id
AND ticketlines.PRODUCT = products.ID
AND products.CATEGORY = categories.ID
AND date(receipts.DATENEW) >= (curdate())
GROUP BY NAME');

        $ventaPorDias = DB::select('SELECT date(receipts.Datenew) as daynumber, COUNT(DISTINCT(receipts.ID)) AS CHECKS, SUM(ticketlines.UNITS*ticketlines.PRiCE*1.1) AS TOTAL
 FROM tickets, ticketlines, receipts, closedcash WHERE ticketlines.TICKET = receipts.ID
AND closedcash.MONEY = receipts.MONEY
AND ticketlines.TICKET = tickets.Id
AND receipts.DATENEW >= CURDATE()-13
GROUP BY daynumber');


        $totalDay = 0;
        foreach ($ventaLinesHoy as $ventaLineHoy) {
            $totalDay += $ventaLineHoy->TOTAL;


        }
        $totalNight = 0;
        foreach ($ventaLinesHoyNight as $ventaLineaHoyNight) {
            $totalNight += $ventaLineaHoyNight->TOTAL;


        }

        return view('admin.stats.index', compact(['ventaLinesHoy', 'ventaLinesHoyNight', 'cajaActual','categoriesHoy', 'ventaPorDias', 'totalDay','totalNight']));
    }


}
