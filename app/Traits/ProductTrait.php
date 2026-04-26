<?php
namespace App\Traits;

use App\Models\UnicentaModels\Product;
use Illuminate\Support\Facades\Log;

trait ProductTrait{



    public function getCategoryProducts($id)
    {


        $products = Product::where('category',$id)->orderBy('name')->paginate(200);

         foreach ($products as $product) {
            // Log::debug('productos en product controller getproductsformcategory'.$product);
            if (!empty($product->image)) {
                $product->image = base64_encode($product->image);
            }

        }
        return $products;
    }
}
