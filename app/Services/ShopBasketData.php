<?php

namespace App\Services;

use App\Traits\SharedTicketTrait;

/**
 * Resolves live basket total and line count for shop topbar and View composers.
 */
class ShopBasketData
{
    use SharedTicketTrait;

    public function totalBasketPriceExTax(): float
    {
        $tid = \Session::get('ticketID');
        if (! $tid) {
            return 0.0;
        }

        return (float) $this->getSumTicketLines($tid);
    }

    public function lineCountWithProducts(): int
    {
        $tid = \Session::get('ticketID');
        if (! $tid) {
            return 0;
        }

        $ticket = $this->getTicket($tid);
        $c = 0;
        foreach ($ticket->m_aLines as $line) {
            if (! empty($line->productid)) {
                $c++;
            }
        }

        return $c;
    }
}
