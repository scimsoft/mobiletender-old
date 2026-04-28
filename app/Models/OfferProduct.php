<?php

namespace App\Models;

use App\Models\UnicentaModels\Product;
use Illuminate\Database\Eloquent\Model;

class OfferProduct extends Model
{
    protected $table = 'offer_products';
    public $timestamps = false;

    protected $fillable = [
        'offer_id', 'product_id', 'quantity',
    ];

    protected $casts = [
        'quantity' => 'integer',
    ];

    public function offer()
    {
        return $this->belongsTo(Offer::class, 'offer_id', 'id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
}
