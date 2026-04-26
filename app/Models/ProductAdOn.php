<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductAdOn extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $primaryKey = 'product_id';
    protected $keyType = 'string';

    protected $fillable = [
        'product_id', 'adon_product_id','price'
    ];
}
