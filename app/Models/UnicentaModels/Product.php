<?php

namespace App\Models\UnicentaModels;


use App\Models\ProductAdOn;


use App\Models\ProductDetail;
use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use Uuids;

    /**
     * Default VAT factor applied when converting net <-> gross sell price
     * for the admin product edit form. The legacy code hard-coded 1.1
     * (10% IVA) throughout the controller; centralising it here means
     * future tax-category-aware logic only has one place to change.
     */
    public const DEFAULT_VAT_FACTOR = 1.1;

    protected $table = 'products';
    public $timestamps = false;
    protected $primaryKey = 'id';
    protected $keyType = 'string';

    public function setPriceSellAttribute($value)
    {
        $this->attributes['pricesell'] = str_replace(',', '.', $value);
    }

    public function setPriceBuyAttribute($value)
    {
        $this->attributes['pricebuy'] = str_replace(',', '.', $value);
    }

    /**
     * Gross (with-IVA) sell price. Read as $product->price_sell_gross,
     * write as $product->price_sell_gross = '2,50'.
     */
    public function getPriceSellGrossAttribute(): float
    {
        return (float) $this->pricesell * self::DEFAULT_VAT_FACTOR;
    }

    public function setPriceSellGrossAttribute($value): void
    {
        $normalised = str_replace(',', '.', (string) $value);
        $this->attributes['pricesell'] = ((float) $normalised) / self::DEFAULT_VAT_FACTOR;
    }

    protected $fillable = [
        'name', 'pricebuy','pricesell','code','reference','taxcat','category', 'detail','printto'
    ];

    public function category()
    {
        return $this->hasOne(Category::class, 'id', 'category');
    }

    public function product_cat(){
        return $this->hasOne(Products_Cat::class,'product','id');
    }

    public function product_addons(){
        return $this->hasMany(ProductAdOn::class,'product_id','id');
    }

    public function product_detail()
    {
        return $this->hasOne(ProductDetail::class, 'product_id', 'id');
    }

}
