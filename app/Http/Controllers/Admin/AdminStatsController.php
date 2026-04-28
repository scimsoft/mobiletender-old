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
use Carbon\Carbon;
use function compact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminStatsController extends Controller
{
    use UnicentaPayedTrait;

    public function index(Request $request)
    {
        $selectedDate = $request->query('date');
        $parsedDate = Carbon::createFromFormat('Y-m-d', (string) $selectedDate);
        if ($parsedDate === false) {
            $parsedDate = Carbon::today();
        }

        $selectedDate = $parsedDate->toDateString();
        $selectedCategoryId = (int) $request->query('category');
        $startOfDay = $parsedDate->copy()->startOfDay()->toDateTimeString();
        $endOfDay = $parsedDate->copy()->endOfDay()->toDateTimeString();
        $historyStartDate = $parsedDate->copy()->subDays(13)->startOfDay()->toDateTimeString();

        $ventaLinesHoy = DB::select(
            "SELECT payments.PAYMENT, SUM(ticketlines.UNITS*ticketlines.PRICE*1.1) AS TOTAL
FROM tickets, ticketlines, receipts, closedcash,payments
WHERE ticketlines.TICKET = receipts.ID
AND closedcash.MONEY = receipts.MONEY
AND ticketlines.TICKET = tickets.Id
and tickets.Id = payments.RECEIPT
AND receipts.DATENEW BETWEEN ? AND ?
AND time(receipts.DATENEW) >= '10:00:00'
GROUP BY payments.PAYMENT",
            [$startOfDay, $endOfDay]
        );

        $ventaLinesHoyNight = DB::select(
            "SELECT payments.PAYMENT, SUM(ticketlines.UNITS*ticketlines.PRICE*1.1) AS TOTAL
FROM tickets, ticketlines, receipts, closedcash,payments
WHERE ticketlines.TICKET = receipts.ID
AND closedcash.MONEY = receipts.MONEY
AND ticketlines.TICKET = tickets.Id
and tickets.Id = payments.RECEIPT
AND receipts.DATENEW BETWEEN ? AND ?
AND time(receipts.DATENEW) < '10:00:00'
GROUP BY payments.PAYMENT",
            [$startOfDay, $endOfDay]
        );
        $cajaActual = $this->getClosedCash();

        $categoriesHoy = DB::select('SELECT categories.ID AS ID, categories.name as NAME, COUNT(categories.name) AS CHECKS, SUM(ticketlines.UNITS*ticketlines.PRICE*1.1) AS TOTAL
FROM tickets, ticketlines, receipts,
 closedcash,products,categories
WHERE ticketlines.TICKET = receipts.ID
AND closedcash.MONEY = receipts.MONEY
AND ticketlines.TICKET = tickets.Id
AND ticketlines.PRODUCT = products.ID
AND products.CATEGORY = categories.ID
AND receipts.DATENEW BETWEEN ? AND ?
GROUP BY categories.ID, NAME', [$startOfDay, $endOfDay]);

        $ventaPorDias = DB::select('SELECT date(receipts.Datenew) as daynumber, COUNT(DISTINCT(receipts.ID)) AS CHECKS, SUM(ticketlines.UNITS*ticketlines.PRiCE*1.1) AS TOTAL
 FROM tickets, ticketlines, receipts, closedcash WHERE ticketlines.TICKET = receipts.ID
AND closedcash.MONEY = receipts.MONEY
AND ticketlines.TICKET = tickets.Id
AND receipts.DATENEW BETWEEN ? AND ?
GROUP BY daynumber', [$historyStartDate, $endOfDay]);

        $selectedCategory = null;
        foreach ($categoriesHoy as $category) {
            if ((int) $category->ID === $selectedCategoryId) {
                $selectedCategory = $category;
                break;
            }
        }

        $categoryProductDetails = [];
        if ($selectedCategory !== null) {
            $categoryProductDetails = DB::select('SELECT products.NAME as NAME, SUM(ticketlines.UNITS) AS UNITS, SUM(ticketlines.UNITS*ticketlines.PRICE*1.1) AS TOTAL
FROM tickets, ticketlines, receipts, closedcash, products, categories
WHERE ticketlines.TICKET = receipts.ID
AND closedcash.MONEY = receipts.MONEY
AND ticketlines.TICKET = tickets.Id
AND ticketlines.PRODUCT = products.ID
AND products.CATEGORY = categories.ID
AND categories.ID = ?
AND receipts.DATENEW BETWEEN ? AND ?
GROUP BY products.ID, products.NAME
ORDER BY TOTAL DESC', [$selectedCategoryId, $startOfDay, $endOfDay]);
        }


        $totalDay = 0;
        foreach ($ventaLinesHoy as $ventaLineHoy) {
            $totalDay += $ventaLineHoy->TOTAL;


        }
        $totalNight = 0;
        foreach ($ventaLinesHoyNight as $ventaLineaHoyNight) {
            $totalNight += $ventaLineaHoyNight->TOTAL;


        }

        return view('admin.stats.index', compact([
            'ventaLinesHoy',
            'ventaLinesHoyNight',
            'cajaActual',
            'categoriesHoy',
            'ventaPorDias',
            'totalDay',
            'totalNight',
            'selectedDate',
            'selectedCategoryId',
            'selectedCategory',
            'categoryProductDetails',
        ]));
    }


}
