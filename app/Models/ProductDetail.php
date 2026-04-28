<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductDetail extends Model
{
    use HasFactory;
    protected $primaryKey = 'product_id';
    protected $keyType = 'string';

    protected $fillable = [
        'product_id',
        'description',
        'lang1',
        'lang2',
        'lang3',
        'alerg_apio',
        'alerg_crustaceans',
        'alerg_dairy',
        'alerg_sulphites',
        'alerg_egg',
        'alerg_gluten',
        'alerg_lupins',
        'alerg_mollusks',
        'alerg_mustard',
        'alerg_peanuts',
        'alerg_peelfruits',
        'alerg_sesame',
        'alerg_soy',
        'alerg_fish',
    ];

    public const ALLERGEN_KEYS = [
        'alerg_apio',
        'alerg_crustaceans',
        'alerg_dairy',
        'alerg_sulphites',
        'alerg_egg',
        'alerg_gluten',
        'alerg_lupins',
        'alerg_mollusks',
        'alerg_mustard',
        'alerg_peanuts',
        'alerg_peelfruits',
        'alerg_sesame',
        'alerg_soy',
        'alerg_fish',
    ];


}
