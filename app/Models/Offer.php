<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
    protected $table = 'offers';
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id', 'name', 'final_price', 'active', 'sort_order',
    ];

    protected $casts = [
        'active' => 'boolean',
        'final_price' => 'float',
        'sort_order' => 'integer',
    ];

    public function setFinalPriceAttribute($value)
    {
        $this->attributes['final_price'] = is_string($value)
            ? str_replace(',', '.', $value)
            : $value;
    }

    public function offerProducts()
    {
        return $this->hasMany(OfferProduct::class, 'offer_id', 'id');
    }
}
