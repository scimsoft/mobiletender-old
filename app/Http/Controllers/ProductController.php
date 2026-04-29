<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddOnProductRequest;
use App\Http\Requests\RemoveAddOnProductRequest;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\ToggleAllergenRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\ProductAdOn;
use App\Models\ProductDetail;
use App\Models\UnicentaModels\Category;
use App\Models\UnicentaModels\Product;
use App\Models\UnicentaModels\Products_Cat;
use App\Traits\ProductTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    use ProductTrait;

    public function index($category = null)
    {
        if (empty($category)) {
            $products = Product::paginate(50);
        } else {
            $products = $this->getCategoryProducts($category);
        }

        $categories = Category::orderByRaw('CONVERT(catorder, SIGNED)')->get();

        return view('admin.products.index', compact('categories', 'products'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
    }

    public function create()
    {
        $this->authorize('create', Product::class);

        $categories = Category::all();
        return view('admin.products.create', compact('categories'));
    }

    public function store(StoreProductRequest $request)
    {
        $attrs = $request->productAttributes();

        $product = Product::create($attrs);

        // The form posts a gross (with-IVA) sell price; convert it back to net
        // so it matches what the rest of the application stores.
        $product->price_sell_gross = $request->input('pricesell');
        $product->save();

        $this->addOrDeleteFromCatalog($product->id);

        return redirect()->route('products.edit', $product->id)
            ->with('success', 'Product created successfully.');
    }

    public function show($product)
    {
        return view('products.show', compact('product'));
    }

    public function edit($id)
    {
        $product = Product::with('product_detail')->findOrFail($id);
        $this->authorize('update', $product);

        $hasImage = ! empty($product->image);

        $addonIds = ProductAdOn::where('product_id', $product->id)->pluck('adon_product_id');
        $all_adons = $addonIds->isEmpty()
            ? collect()
            : Product::whereIn('id', $addonIds)->get();

        $categories = Category::orderBy('name')->get();

        $languages = config('languages', []);
        // Strip the default (first) language and keep the next three —
        // these correspond to product_details.lang1, lang2, lang3.
        $translationLanguages = array_slice($languages, 1, 3, true);

        return view('admin.products.edit', compact(
            'product',
            'all_adons',
            'categories',
            'hasImage',
            'translationLanguages'
        ));
    }

    public function update(UpdateProductRequest $request, $id)
    {
        Log::debug('product update id:' . $id);

        $product = Product::findOrFail($id);
        $this->authorize('update', $product);

        $product->fill($request->productAttributes());
        $product->printto = trim((string) $request->input('printto', $product->printto));
        $product->price_sell_gross = $request->input('pricesell');

        $stockunits = $request->input('stockunits');
        if ($stockunits !== null && $stockunits !== '') {
            $product->stockunits = str_replace(',', '.', $stockunits);
        }

        $product->save();
        $this->updateProductDetail($request->productDetailAttributes(), $product->id);

        return $this->safeRedirect(
            $request->input('redirects_to'),
            route('products.edit', $product->id)
        )->with('success', 'Product updated successfully');
    }

    /**
     * Validate the supplied URL is a same-host path before redirecting.
     * Falls back to $fallback otherwise. Prevents open-redirects.
     */
    private function safeRedirect(?string $candidate, string $fallback)
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

    private function updateProductDetail(array $attrs, $productId): void
    {
        $detail = ProductDetail::firstOrNew(['product_id' => $productId]);
        $detail->fill($attrs);
        $detail->save();
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $this->authorize('delete', $product);

        $product->product_cat()->delete();
        $product->delete();

        return redirect()->route('products.index')
            ->with('success', 'Product deleted successfully');
    }

    public function editImage($id)
    {
        $product = Product::find($id);
        if (! empty($product->image)) {
            $product->image = base64_encode($product->image);
        }

        return view('admin.products.crop_image', compact('product'));
    }

    public function imageCrop(Request $request)
    {
        $image_file = $request->image;
        $product = Product::find($request->productID);
        list(, $image_file) = explode(';', $image_file);
        list(, $image_file) = explode(',', $image_file);
        $image_file = base64_decode($image_file);
        $product->image = $image_file;
        $product->save();
        return response()->json(['status' => true]);
    }

    public function toggleCatalog(Request $request)
    {
        $product_id = $request->product_id;
        Log::debug('product_id:' . $product_id);

        $this->addOrDeleteFromCatalog($product_id);

        return 'SUCCES';
    }

    public function addOnProductAdd(AddOnProductRequest $request)
    {
        ProductAdOn::create([
            'product_id'      => $request->input('product_id'),
            'adon_product_id' => $request->input('adon_product_id'),
            'price'           => $request->priceAsFloat(),
        ]);

        return response()->json(['status' => true]);
    }

    public function removeAddOnProductAdd(RemoveAddOnProductRequest $request)
    {
        $row = ProductAdOn::where('product_id', $request->input('product_id'))
            ->where('adon_product_id', $request->input('adon_product_id'))
            ->first();

        if ($row !== null) {
            $row->delete();
        }

        return response()->json(['status' => true]);
    }

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

    public function getProductList($id)
    {
        return Product::select('id', 'name')->where('category', $id)->get();
    }

    public function toggleAlergen(ToggleAllergenRequest $request)
    {
        $productDetail = ProductDetail::firstOrNew(['product_id' => $request->input('product_id')]);
        $key = $request->input('alergen_id');
        $productDetail->{$key} = ! (bool) $productDetail->{$key};
        $productDetail->save();

        return response()->json([
            'status' => true,
            'active' => (bool) $productDetail->{$key},
        ]);
    }
}
