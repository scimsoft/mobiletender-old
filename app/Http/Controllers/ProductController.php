<?php

namespace App\Http\Controllers;

use App\Models\ProductDetail;
use App\Models\UnicentaModels\Category;
use App\Models\ProductAdOn;
use App\Traits\ProductTrait;
use App\Models\UnicentaModels\Product;
use App\Models\UnicentaModels\Products_Cat;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
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
        $validated = $request->validate([
            'code'      => 'required|string|max:255',
            'reference' => 'required|string|max:255',
            'category'  => 'required|string|max:255',
            'pricebuy'  => 'required',
            'pricesell' => 'required',
            'name'      => 'required|string|max:255',
            'taxcat'    => 'nullable|string|max:255',
            'printto'   => 'nullable|string|max:255',
        ]);

        Product::create(Arr::only($validated, [
            'name', 'pricebuy', 'pricesell', 'code', 'reference', 'taxcat', 'category', 'printto',
        ]));

        $createdProduct = Product::where('code', $validated['code'])->first();

        $pricesell = str_replace(',', '.', $validated['pricesell']);
        $createdProduct->pricesell = $pricesell / 1.1;
        $createdProduct->save();
        $product_id = $createdProduct->id;
        $this->addOrDeleteFromCatalog($product_id);

        return redirect()->route('products.edit', $product_id)
            ->with('success', 'Product created successfully.');
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
        $product = Product::with('product_detail')->findOrFail($id);
        $hasImage = ! empty($product->image);

        $addonIds = ProductAdOn::where('product_id', $product->id)->pluck('adon_product_id');
        $all_adons = $addonIds->isEmpty()
            ? collect()
            : Product::whereIn('id', $addonIds)->get();

        $categories = Category::orderBy('name')->get();

        return view('admin.products.edit', compact('product', 'all_adons', 'categories', 'hasImage'));
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
        $validated = $request->validate([
            'code'       => 'required|string|max:255',
            'taxcat'     => 'required|string|max:255',
            'reference'  => 'required|string|max:255',
            'category'   => 'required|string|max:255',
            'pricebuy'   => 'required',
            'pricesell'  => 'required',
            'name'       => 'required|string|max:255',
            'printto'    => 'nullable|string|max:255',
            'stockunits' => 'nullable|string|max:255',
        ]);

        Log::debug('product update id:' . $id);

        $product = Product::findOrFail($id);

        $product->fill(Arr::only($validated, [
            'name', 'code', 'reference', 'category', 'taxcat', 'pricebuy',
        ]));

        $product->printto = isset($validated['printto']) ? trim($validated['printto']) : $product->printto;

        $pricesell = str_replace(',', '.', $validated['pricesell']);
        $product->pricesell = $pricesell / 1.1;

        if (array_key_exists('stockunits', $validated) && $validated['stockunits'] !== null) {
            $product->stockunits = str_replace(',', '.', $validated['stockunits']);
        }

        $product->save();
        $this->updateProductDetail($request, $id);

        return $this->safeRedirectBack(
            $request->input('redirects_to'),
            route('products.edit', $product->id)
        )->with('success', 'Product updated successfully');
    }

    /**
     * Validate the supplied "redirects_to" URL is a same-host path before
     * redirecting. Falls back to $fallback otherwise. Prevents open-redirects.
     */
    private function safeRedirectBack(?string $candidate, string $fallback)
    {
        if (empty($candidate)) {
            return redirect()->to($fallback);
        }

        $appHost = parse_url(config('app.url'), PHP_URL_HOST);
        $targetHost = parse_url($candidate, PHP_URL_HOST);

        if ($targetHost === null || $targetHost === $appHost) {
            return redirect()->to($candidate);
        }

        return redirect()->to($fallback);
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

    public function addOnProductAdd(Request $request){
        $validated = $request->validate([
            'product_id'      => 'required|string|exists:products,id',
            'adon_product_id' => 'required|string|exists:products,id',
            'price'           => 'nullable|numeric',
        ]);

        ProductAdOn::create($validated);

        return response()->json(['status' => true]);
    }
    public function removeAddOnProductAdd(Request $request){
        $validated = $request->validate([
            'product_id'      => 'required|string|exists:products,id',
            'adon_product_id' => 'required|string|exists:products,id',
        ]);

        $row = ProductAdOn::where('product_id', $validated['product_id'])
            ->where('adon_product_id', $validated['adon_product_id'])
            ->first();

        if ($row !== null) {
            $row->delete();
        }

        return response()->json(['status' => true]);
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
        $validated = $request->validate([
            'product_id' => 'required|string|exists:products,id',
            'alergen_id' => ['required', 'string', \Illuminate\Validation\Rule::in(ProductDetail::ALLERGEN_KEYS)],
        ]);

        $productDetail = ProductDetail::firstOrNew(['product_id' => $validated['product_id']]);
        $productDetail->{$validated['alergen_id']} = ! (bool) $productDetail->{$validated['alergen_id']};
        $productDetail->save();

        return response()->json([
            'status' => true,
            'active' => (bool) $productDetail->{$validated['alergen_id']},
        ]);
    }


}
