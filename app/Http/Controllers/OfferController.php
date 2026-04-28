<?php

namespace App\Http\Controllers;

use App\Models\Offer;
use App\Models\OfferProduct;
use App\Models\UnicentaModels\Category;
use App\Models\UnicentaModels\Product;
use App\Traits\OfferTrait;
use App\Traits\SharedTicketTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class OfferController extends Controller
{
    use OfferTrait;

    public function index()
    {
        $offers = Offer::with('offerProducts')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return view('admin.offers.index', compact('offers'));
    }

    public function create()
    {
        $categories = Category::orderByRaw('CONVERT(catorder, SIGNED)')->get();
        $products = Product::orderBy('name')->get();
        return view('admin.offers.create', compact('categories', 'products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'final_price' => 'required',
        ]);

        $offer = new Offer([
            'id' => Str::uuid()->toString(),
            'name' => $request->input('name'),
            'final_price' => $request->input('final_price'),
            'active' => $request->has('active'),
            'sort_order' => (int) $request->input('sort_order', 0),
        ]);
        $offer->save();

        $this->syncOfferProducts($offer, $request);

        return redirect()->route('offers.edit', $offer->id)
            ->with('success', 'Oferta creada correctamente.');
    }

    public function edit($id)
    {
        $offer = Offer::with('offerProducts')->findOrFail($id);
        $categories = Category::orderByRaw('CONVERT(catorder, SIGNED)')->get();
        $products = Product::orderBy('name')->get();

        $productsSubtotal = $this->getOfferProductsSubtotalSell($offer) * 1.1;

        return view('admin.offers.edit', compact('offer', 'categories', 'products', 'productsSubtotal'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'final_price' => 'required',
        ]);

        $offer = Offer::findOrFail($id);
        $offer->name = $request->input('name');
        $offer->final_price = $request->input('final_price');
        $offer->active = $request->has('active');
        $offer->sort_order = (int) $request->input('sort_order', 0);
        $offer->save();

        $this->syncOfferProducts($offer, $request);

        return redirect()->route('offers.edit', $offer->id)
            ->with('success', 'Oferta actualizada correctamente.');
    }

    public function destroy($id)
    {
        $offer = Offer::findOrFail($id);
        OfferProduct::where('offer_id', $offer->id)->delete();
        $offer->delete();

        return redirect()->route('offers.index')
            ->with('success', 'Oferta borrada.');
    }

    public function showOffersForOrder()
    {
        $this->checkForSessionTicketId();

        $offers = Offer::with('offerProducts')
            ->where('active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        $totalBasketPrice = $this->getSumTicketLines(Session::get('ticketID'));

        return view('order.offers', compact('offers', 'totalBasketPrice'));
    }

    private function checkForSessionTicketId()
    {
        $ticketID = Session::get('ticketID');
        if (is_null($ticketID) || $this->hasTicket($ticketID) < 1) {
            $ticket = $this->createEmptyTicket();
            $newTicketID = Str::uuid()->toString();
            $this->saveEmptyTicket($ticket, $newTicketID);
            Session::put('ticketID', $newTicketID);
            Session::forget('tableNumber');
        }
    }

    public function addOffer($id)
    {
        $offer = Offer::with('offerProducts')->findOrFail($id);
        $tableNumber = Session::get('ticketID');

        if (empty($tableNumber)) {
            return redirect()->route('order')
                ->with('error', 'No hay mesa o ticket activo.');
        }

        $this->addOfferToTicket($offer, $tableNumber);

        return redirect()->route('basket')
            ->with('status', 'Oferta "' . $offer->name . '" añadida al ticket.');
    }

    private function syncOfferProducts(Offer $offer, Request $request)
    {
        OfferProduct::where('offer_id', $offer->id)->delete();

        $productIds = (array) $request->input('product_id', []);
        $quantities = (array) $request->input('quantity', []);

        foreach ($productIds as $i => $productId) {
            if (empty($productId)) {
                continue;
            }
            $qty = isset($quantities[$i]) ? max(1, (int) $quantities[$i]) : 1;
            OfferProduct::create([
                'offer_id' => $offer->id,
                'product_id' => $productId,
                'quantity' => $qty,
            ]);
        }
    }
}
