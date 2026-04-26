<?php

namespace App\Models\UnicentaModels;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    //

    protected $table = 'categories';
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'id','name', 'parentid','catshowname','catorder'
    ];


    public function products()
    {
        return $this->hasMany(Product::class,'category','id');
    }

    public function category_childs(){
        return $this->hasMany(Category::class,'parentid','id');
    }

    /**
     * Order categories by numeric catorder (stored as string in uniCenta).
     */
    public function scopeOrdered($query)
    {
        return $query->orderByRaw('CONVERT(catorder, SIGNED)');
    }

}
