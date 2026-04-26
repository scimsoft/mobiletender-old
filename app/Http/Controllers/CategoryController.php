<?php

namespace App\Http\Controllers;

use App\Models\UnicentaModels\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use function redirect;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $categories = Category::orderByRaw('CONVERT(catorder, SIGNED)')->get();

        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('admin.categories.create');
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


        $category = new Category([
            'id' =>  Str::uuid()->toString(),
            'name' => $request->get('name'),
            'parentid' => $request->get('parentid'),
            'catshowname' => $request->get('catshowname')=='on',
            'catorder' => $request->get('catorder')
        ]);
        $category->save();
        return redirect('/categories')->with('success', 'Categoria guradado!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
            'id'=>'required',
            'name'=>'required',

        ]);
        $category = Category::findorfail($request->id);
        $category->parentid = $request->parentid;
        $category->name = $request->name;
        $category->catorder = $request->catorder;
        $category->save();
        return redirect('/categories')->with('success', 'Categoria guardado!');

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
        $category=Category::findOrFail($id);

        foreach ($category->products()->get() as $producttodelete) {

            $producttodelete->product_cat()->delete();
        }
        $category->products()->delete();

        $childcategories = Category::where('parentid', $id)->get();

        foreach ($childcategories as $category_child) {
            foreach ($category_child->products()->get() as $producttodelete) {
                $producttodelete->product_cat()->delete();
            }
            $category_child->products()->delete();
            $category_child->delete();
        }


        $category->delete();

        return redirect()->route('categories.index')
            ->with('success','Product deleted successfully');
    }

    public function toggleActive(Request $request){
        $category_id = $request->category_id;
        Log::debug('$category_id:'.$category_id);
        $category= Category::findorfail($category_id);
        if($category->catshowname){
            $category->catshowname=false;

        }else{
            $category->catshowname=true;
        }
        $category->save();

        return "SUCCES";

    }



}
