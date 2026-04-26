<?php

namespace App\Http\Controllers;

use App\Models\ProductAdOn;
use App\Models\UnicentaModels\Product;
use Illuminate\Http\Request;

class ProductAdOnController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ProductAdOn  $prodctAdOn
     * @return \Illuminate\Http\Response
     */
    public function show(ProductAdOn $prodctAdOn)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ProductAdOn  $prodctAdOn
     * @return \Illuminate\Http\Response
     */
    public function edit(ProductAdOn $prodctAdOn)
    {
        //
        $products = Product::query()->orderBy('name')->get();

        return view('admin.products.edit',compact('products'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ProductAdOn  $prodctAdOn
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ProductAdOn $prodctAdOn)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ProductAdOn  $prodctAdOn
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProductAdOn $prodctAdOn)
    {
        //
    }
}
