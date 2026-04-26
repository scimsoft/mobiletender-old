<?php

namespace App\Models\UnicentaModels;


use App\Models\ProductAdOn;


use App\Models\ProductDetail;
use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    //
    use Uuids;

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
