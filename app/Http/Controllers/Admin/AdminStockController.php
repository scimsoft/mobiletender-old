<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UnicentaModels\Category;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use function is_null;

class AdminStockController extends Controller
{
    //

    public function currentStockIndex($cat = null){
        $categories = Category::orderByRaw('CONVERT(catorder, SIGNED)')->get();
        if(!is_null($cat)){
            $stocks = DB::select(
                'SELECT id ,name, units FROM  products left join stockcurrent  on products.id = stockcurrent.product where products.category=? order by name',
                [$cat]
            );
        }else{
            $stocks = DB::select('SELECT id ,name, units FROM  products left join stockcurrent  on products.id = stockcurrent.product order by name');
        }
        return view('admin.stock.index',compact('stocks','categories'));


    }


    public function addStock(Request $request){

        $product_id = $request->product_id;
        $units = $request->units;
        $stockDiaryID=Str::uuid();
        $datenew = Carbon::now()->format('Y-m-d H:i:s');
        DB::insert(
            'INSERT INTO stockdiary (ID, DATENEW, REASON, LOCATION, PRODUCT, ATTRIBUTESETINSTANCE_ID, UNITS, PRICE, AppUser) VALUES (?, ?, 1, \'0\', ?, null, ?, 0, ?)',
            [$stockDiaryID, $datenew, $product_id, $units, 'stockApp']
        );

        //Log::debug('Insert Stock'.$insertStockDairy);
        $control = DB::update(
            'UPDATE stockcurrent SET UNITS = (UNITS + ?) WHERE LOCATION = \'0\' AND PRODUCT = ? AND ATTRIBUTESETINSTANCE_ID IS NULL',
            [$units, $product_id]
        );

        if ($control==0) {
            DB::insert(
                'INSERT INTO stockcurrent (LOCATION, PRODUCT, ATTRIBUTESETINSTANCE_ID, UNITS) VALUES (\'0\', ?, null, ?)',
                [$product_id, $units]
            );
        }
        return 'OK';


    }

}
