<?php

namespace App\Http\Controllers;

use App\Models\ProductDetail;
use App\Models\UnicentaModels\Category;
use App\Models\ProductAdOn;
use App\Traits\ProductTrait;
use App\Models\UnicentaModels\Product;
use App\Models\UnicentaModels\Products_Cat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use function str_replace;
use function view;

class ProductController extends Controller
{
    use ProductTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($category=null)
    {
        //
        if(empty($category)) {
            $products = Product::paginate(50);
        }else{
            $products= $this->getCategoryProducts($category);
    }
        $categories = Category::orderByRaw('CONVERT(catorder, SIGNED)')->get();

        return view('admin.products.index',compact('categories','products'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $categories = Category::all();
        return view('admin.products.create',compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'code'=> 'required',
            'reference'=> 'required',
            'category'=> 'required',
            'pricebuy'=> 'required',
            'pricesell'=> 'required',
            'name' => 'required',

        ]);


        Product::create($request->all());


        $code = request()->get('code');

        $createdProduct = Product::where('code', $code)->first();

        $pricesell = str_replace(',','.',$request->pricesell);
        $createdProduct->pricesell = $pricesell /1.1;
        $createdProduct->save();
        $product_id = $createdProduct->id;
        $this->addOrDeleteFromCatalog($product_id);

        return redirect()->route('products.edit',$product_id )
            ->with('success','Product created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($product)
    {
        //
        return view('products.show',compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $product=Product::findOrFail($id);

        if (!empty($product->image)) {
            $product->image = base64_encode($product->image);
        }

        $product_addons = ProductAdOn::where('product_id','=',$product->id)->get();
       // dd($product_addons);
        $all_adons=[];
        foreach ($product_addons as $product_addon) {
            $all_adons[]= Product::find($product_addon->adon_product_id);
        }

        $categories = Category::all();


        return view('admin.products.edit',compact('product','all_adons','categories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //

        $request->validate([
            'code'=> 'required',
            'taxcat'=> 'required',
            'reference'=> 'required',
            'category'=> 'required',
            'pricebuy'=> 'required',
            'pricesell'=> 'required',
            'name' => 'required',

        ]);
        Log::debug('product update id:'.$id);


        $product=Product::find($id);
        $request->validate([
            'name' => 'required',
            'pricebuy' => 'required',
        ]);


        $product->update($request->all());
        $pricesell = str_replace(',','.',$request->pricesell);
        $stockunits = str_replace(',','.',$request->stockunits);
        $product->pricesell = ($pricesell/1.1);
        $product->stockunits = $stockunits;
        $product->save();
        $this->updateProductDetail($request,$id);

        $url = $request->only('redirects_to');

        return redirect()->to($url['redirects_to']);
        //return redirect()->route('products.index')->with('success','Product updated successfully');
    }

    private function updateProductDetail(Request $request,$id){

        $productDetail = ProductDetail::where('product_id',$id)->first();
        if(empty($productDetail)){
            $productDetail = new ProductDetail();
            $productDetail->product_id = $id;
        }
        $productDetail->description = $request->description;
        $productDetail->lang1=$request->lang1;
        $productDetail->lang2=$request->lang2;
        $productDetail->lang3=$request->lang3;
        $productDetail->save();



    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $product=Product::findOrFail($id);
        $product->product_cat()->delete();
        $product->delete();

        return redirect()->route('products.index')
            ->with('success','Product deleted successfully');
    }

    public function editImage($id)
    {

        $product = Product::find($id);
        if (!empty($product->image)) {
            $product->image = base64_encode($product->image);
        }

        return view('admin.products.crop_image', compact('product'));
    }

    public function imageCrop(Request $request)
    {
        $image_file = $request->image;
        $product = Product::find($request->productID);
        list($type, $image_file) = explode(';', $image_file);
        list(, $image_file) = explode(',', $image_file);
        $image_file = base64_decode($image_file);
        $product->image = $image_file;
        $product->save();
        return response()->json(['status' => true]);
    }
    public function toggleCatalog(Request $request){
        $product_id = $request->product_id;
        Log::debug('product_id:'.$product_id);

        $this->addOrDeleteFromCatalog($product_id);

        return "SUCCES";

    }

    public function addOnProductAdd(){
        ProductAdOn::create(request()->all());
        return true;
    }
    public function removeAddOnProductAdd(){
        $productID = request()->get('product_id');
        $addonProductID = request()->get('adon_product_id');
        $product = ProductAdOn::where('product_id',$productID)->where('adon_product_id',$addonProductID)->first();
        $product->delete();
        return true;
    }

    /**
     * @param $product_id
     */
    private function addOrDeleteFromCatalog($product_id): void
    {
        $productcat = Products_Cat::find($product_id);
        if (empty($productcat)) {
            $cat = new Products_Cat();

            $cat->product = $product_id;
            $cat->save();
        } else {
            $productcat->delete();
        }
    }

    public function getProductList($id){
        return Product::select('id','name')->where('category',$id)->get();
    }

    public function toggleAlergen(Request $request){
        $productID = request()->get('product_id');
        $productDetail = ProductDetail::where('product_id',$productID)->first();
        if(empty($productDetail)){
            $productDetail = new ProductDetail();
            $productDetail->product_id = $productID;
        }
        if($productDetail->{$request->alergen_id}){
            $productDetail->{$request->alergen_id} = false;
        }else{
            $productDetail->{$request->alergen_id} = true;
        }
        $productDetail->save();
        return $productDetail->{$request->alergen_id};

    }


}
