<?php
namespace App\Http\Controllers;

use App\Models\UnicentaModels\Product;
use Illuminate\Support\Facades\Response;

/**
 * Created by PhpStorm.
 * User: Gerrit
 * Date: 20/10/2020
 * Time: 14:57
 */

class ProductImageController extends Controller
{

    public function getImage($prodcutImageID){
        $productID=explode(".",$prodcutImageID);
        $product = Product::find($productID[0]);
        if (!$product || $product->image === null) {
            abort(404);
        }
        $rendered_buffer = $product->image;

        $response = Response::make($rendered_buffer);
        $response->header('Content-Type', 'image/png');
        $response->header('Cache-Control','max-age=2592000');
        return $response;
    }

}