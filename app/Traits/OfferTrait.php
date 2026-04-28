<?php

namespace App\Traits;

use App\Models\Offer;
use App\Models\UnicentaModels\Product;
use App\Models\UnicentaModels\SharedTicketLines;
use App\Models\UnicentaModels\SharedTicketProduct;
use App\Models\UnicentaModels\SharedTicketUser;
use Illuminate\Support\Facades\DB;

trait OfferTrait
{
    use SharedTicketTrait;

    public function buildVirtualOfferTicket(Offer $offer)
    {
        $sharedTicket = $this->createEmptyTicket();
        $sharedTicket->m_User = new SharedTicketUser();

        $lineNumber = 0;
        foreach ($offer->offerProducts as $offerProduct) {
            $product = Product::find($offerProduct->product_id);
            if (!$product) {
                continue;
            }
            $qty = max(1, (int) $offerProduct->quantity);
            for ($i = 0; $i < $qty; $i++) {
                $sharedTicketProduct = new SharedTicketProduct(
                    $product->reference,
                    $product->name,
                    $product->code,
                    $product->category,
                    $product->printto,
                    $product->pricesell,
                    $product->id
                );
                $sharedTicket->m_aLines[] = new SharedTicketLines(
                    $sharedTicket->m_sId,
                    $sharedTicketProduct,
                    $lineNumber++
                );
            }
        }

        return $sharedTicket;
    }

    public function getOfferProductsSubtotalSell(Offer $offer)
    {
        $sum = 0.0;
        foreach ($offer->offerProducts as $offerProduct) {
            $product = Product::find($offerProduct->product_id);
            if (!$product) {
                continue;
            }
            $qty = max(1, (int) $offerProduct->quantity);
            $sum += ((float) $product->pricesell) * $qty;
        }
        return $sum;
    }

    public function addOfferToTicket(Offer $offer, $tableNumber)
    {
        if (!$this->hasTicket($tableNumber)) {
            $this->saveEmptyTicket($this->createEmptyTicket(), $tableNumber);
        }

        $virtualTicket = $this->buildVirtualOfferTicket($offer);
        $virtualLines = $virtualTicket->m_aLines;

        if (empty($virtualLines)) {
            return;
        }

        $productsSell = 0.0;
        foreach ($virtualLines as $line) {
            $productsSell += (float) $line->price;
        }

        $finalPriceWithTax = (float) $offer->final_price;
        $targetSell = $finalPriceWithTax / 1.1;
        $adjustmentSell = $targetSell - $productsSell;

        $sharedTicket = $this->getTicket($tableNumber);
        $sharedTicket->m_User = new SharedTicketUser();
        $existingCount = count($sharedTicket->m_aLines);

        foreach ($virtualLines as $vline) {
            $vline->m_sTicket = $sharedTicket->m_sId;
            $vline->setLineNumber($existingCount++);
            $sharedTicket->m_aLines[] = $vline;
        }

        if (abs($adjustmentSell) > 0.0001) {
            $adjustmentProduct = new SharedTicketProduct(
                'OFFER-' . $offer->id,
                'Ajuste oferta: ' . $offer->name,
                'OFFER-' . $offer->id,
                null,
                null,
                $adjustmentSell,
                'offer-' . $offer->id
            );
            $sharedTicket->m_aLines[] = new SharedTicketLines(
                $sharedTicket->m_sId,
                $adjustmentProduct,
                $existingCount++
            );
        }

        $this->updateOpenTable($sharedTicket, $tableNumber);
    }
}
