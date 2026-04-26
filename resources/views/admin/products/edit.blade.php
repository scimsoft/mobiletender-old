@extends('layouts.admin')

@section('title', __('Product') . ' — ' . config('app.name'))

@section('page_header')
    <h1 class="text-2xl font-bold text-slate-900">{{ __('Editar producto') }}</h1>
@endsection

@section('page', 'product-edit')

@section('content')
    <div class="card-tw max-w-5xl">
        <div id="ProductName" class="card-tw-header text-center">{{ __('Product') }}</div>
        <div class="card-tw-body text-left">

                    <form id="product-edit-form" data-product-id="{{ $product->id }}" action="{{ route('products.update',$product->id) }}" method="POST"
                          class="space-y-8">

                        <input type="hidden" name="redirects_to" value="{{ URL::previous() }}">
                        @method('PATCH')
                        @csrf


                        <div class="space-y-6">
                        <table class="w-full border-collapse text-sm">
                            <tr>
                                <td colspan="3">
                                    <label for="name" class="form-label"><b>Nombre</b></label>
                                    <input name="name" class="input-tw w-full" type="text" value="{{$product->name}}" style="min-width: 100%">

                                </td>
                            </tr>
                            <tr><td><h6 class="inline-flex bg-dark text-white mt-4">Image</h6></td><td><hr></td><td><hr></td></tr>

                            <tr>
                                <td colspan="2">
                                    <img src="data:image/png;base64,{{$product->image}}">
                                </td>
                                <td>
                                    <a href="/crop-image/{{$product->id}}" class="btn btn-tab">
                                        Edit Image
                                    </a>
                                </td>
                            </tr>
                            <tr><td><h6 class="inline-flex bg-dark text-white mt-4">Descripcion</h6></td><td><hr></td><td><hr></td></tr>
                            <tr>
                                <td colspan="3">
                                    <label for="description" class="form-label"><b>Dicripcion</b></label>
                                    <textarea name="description" class="input-tw w-full"
                                              rows="3" size="12" style="min-width: 100%">{{$product->product_detail->description ?? ''}}</textarea>

                                </td>
                            </tr>
                            <tr><td><h6 class="inline-flex bg-dark text-white mt-4">Categoria</h6></td><td><hr></td><td><hr></td></tr>


                            <tr>

                                <td colspan="1 ">
                                    <label for="category" class="label label-default"><b>Categoria</b> </label>
                                    <select name="category" class="input-tw w-full">
                                        @foreach($categories as $category)
                                            <option value="{{$category->id}}" {{ ( $category->id == $product->category) ? 'selected' : '' }}>
                                                {{$category->name}}
                                            </option>

                                        @endforeach
                                    </select>
                                </td>
                                <td colspan="1">
                                    <label for="printto" class="form-label"><b>Printer Nr</b></label>
                                    <input name="printto" class="input-tw w-full" type="text" size="2"
                                           value="{{$product->printto ?? '1'}} ">

                                </td>
                                <td>
                                    <label for="taxcat" class="form-label"><b>Tipo de IVA</b></label>
                                    <input name="taxcat" class="input-tw w-full" type="text" value="001" size="2" readonly>

                                </td>
                            </tr>
                            <tr>

                            </tr>
                            <tr><td><h6 class="inline-flex bg-dark text-white mt-4">System data</h6></td><td><hr></td><td><hr></td></tr>
                        <tr>

                                <td colspan="2">
                                    <label for="reference" class="label label-default"><b>Referencia</b> </label>
                                    <input name="reference" class="input-tw w-full" type="text"
                                           value="{{$product->reference}}" readonly>
                                </td>
                                <td>
                                    <label for="code" class="form-label"><b>Codigo</b></label>
                                    <input name="code" class="input-tw w-full" type="text"
                                           value="{{$product->code}}" readonly>

                                </td>
                            </tr>


                            <tr><td><h6 class="inline-flex bg-dark text-white mt-4">Compra y Venta</h6></td><td><hr></td><td><hr></td></tr>

                            <tr>

                                <td colspan="1">
                                    <label for="stockunits" class="label label-default"><b>Unidades de stock</b>
                                    </label>
                                    <input name="stockunits" class="input-tw w-full" type="text" size="3"
                                           value="{{$product->stockunits}}">
                                </td>


                                <td>
                                    <label for="pricebuy" class="form-label"><b>Compra (sin IVA)</b> </label>
                                    <input name="pricebuy" class="input-tw w-full" type="text" size="3"
                                           value="{{$product->pricebuy}}">€

                                </td>
                                <td>
                                    <label for="pricesell" class="form-label"><b>Venta (con IVA)</b></label>
                                    <input name="pricesell" class="input-tw w-full" type="text" size="3"
                                           value="{{($product->pricesell *1.1)}}">€

                                </td>
                            </tr>
                            <tr>
                                <td><h6 class="inline-flex bg-dark text-white mt-4">Extras a Añadir</h6></td>
                                <td>
                                    <hr>
                                </td>
                                <td>
                                    <hr>
                                </td>
                            </tr>


                            <tr>
                                <td colspan="3" class="text-left">
                                    <label for="category_addon" class="form-label"><b>Seleccion de añadidos</b></label>
                                    <select class="input-tw w-full" name="category_addon" id="category_addon">
                                        @foreach($categories as $categorie)
                                            <option value="{{$categorie->id}}">{{$categorie->name}}</option>

                                        @endforeach
                                    </select></td>
                            </tr>

                            <tr>

                                <td colspan="2">
                                    <label for="products_list" class="form-label">Disponibles</label>

                                    <select name="products_list" class="input-tw w-full"
                                            id="products_list">


                                    </select>

                                </td>
                                <td colspan="1">
                                    <label for="addon_products_list" class="form-label">Selecionados</label>
                                    <select name="addon_products_list" class="input-tw w-full"
                                            id="addon_products_list">
                                        <option value=""></option>
                                        @foreach($all_adons as $all_adon)

                                            <option value="{{$all_adon->id}}">{{$all_adon->name}}</option>
                                        @endforeach

                                    </select>

                                </td>
                            </tr>
                            <tr><td><h6 class="inline-flex bg-dark text-white mt-4">Alergenicos</h6></td><td><hr></td><td><hr></td></tr>
                            <tr>
                                <td colspan="3">
                                    <img
                                            src="/img/allergens/Apio.png" class="img-fluid toggleAlergen"
                                            width="32" data-container="body" data-toggle="popover" data-placement="top" data-content="Apio" id='alerg_apio' style=" opacity:@if($product->product_detail AND $product->product_detail->alerg_apio) 1 @else 0.3 @endif;">
                                    <img
                                            src="/img/allergens/Crustaceans.png" class="img-fluid toggleAlergen"
                                            width="32" data-container="body" data-toggle="popover" data-placement="top" data-content="Marisco" id='alerg_crustaceans' style=" opacity:@if($product->product_detail AND $product->product_detail->alerg_crustaceans) 1 @else 0.3 @endif;">
                                    <img
                                            src="/img/allergens/DairyProducts.png" class="img-fluid toggleAlergen"
                                            width="32" data-container="body" data-toggle="popover" data-placement="top" data-content="Lacteo" id='alerg_dairy' style=" opacity:@if($product->product_detail AND $product->product_detail->alerg_dairy) 1 @else 0.3 @endif;">
                                    <img
                                            src="/img/allergens/DioxideSulphites.png" class="img-fluid toggleAlergen"
                                            width="32" data-container="body" data-toggle="popover" data-placement="top" data-content="Sulphite" id='alerg_sulphites' style=" opacity:@if($product->product_detail AND $product->product_detail->alerg_sulphites) 1 @else 0.3 @endif;">
                                    <img
                                            src="/img/allergens/Gluten.png" class="img-fluid toggleAlergen"
                                            width="32" data-container="body" data-toggle="popover" data-placement="top" data-content="Gluten" id='alerg_gluten' style=" opacity:@if($product->product_detail AND $product->product_detail->alerg_gluten) 1 @else 0.3 @endif;">
                                    <img
                                            src="/img/allergens/Lupins.png" class="img-fluid toggleAlergen"
                                            width="32" data-container="body" data-toggle="popover" data-placement="top" data-content="Altramuces" id='alerg_lupins' style=" opacity:@if($product->product_detail AND $product->product_detail->alerg_lupins) 1 @else 0.3 @endif;">
                                    <img
                                            src="/img/allergens/Mollusks.png" class="img-fluid toggleAlergen"
                                            width="32" data-container="body" data-toggle="popover" data-placement="top" data-content="Moluscos" id='alerg_mollusks' style=" opacity:@if($product->product_detail AND $product->product_detail->alerg_mollusks) 1 @else 0.3 @endif;">
                                    <img
                                            src="/img/allergens/Egg.png" class="img-fluid toggleAlergen"
                                            width="32" data-container="body" data-toggle="popover" data-placement="top" data-content="Huevo" id='alerg_egg' style=" opacity:@if($product->product_detail AND $product->product_detail->alerg_egg) 1 @else 0.3 @endif;">
                                    <img
                                            src="/img/allergens/Mustard.png" class="img-fluid toggleAlergen"
                                            width="32" data-container="body" data-toggle="popover" data-placement="top" data-content="Mostaza" id='alerg_mustard' style=" opacity:@if($product->product_detail AND $product->product_detail->alerg_mustard) 1 @else 0.3 @endif;">
                                    <img
                                            src="/img/allergens/Peanuts.png" class="img-fluid toggleAlergen"
                                            width="32" data-container="body" data-toggle="popover" data-placement="top" data-content="Cacahuete" id='alerg_peanuts' style=" opacity:@if($product->product_detail AND $product->product_detail->alerg_peanuts) 1 @else 0.3 @endif;">
                                    <img
                                            src="/img/allergens/PeelFruits.png" class="img-fluid toggleAlergen"
                                            width="32" data-container="body" data-toggle="popover" data-placement="top" data-content="Frutos Secos" id='alerg_peelfruits' style=" opacity:@if($product->product_detail AND $product->product_detail->alerg_peelfruits) 1 @else 0.3 @endif;">
                                    <img
                                            src="/img/allergens/SesameGrains.png" class="img-fluid toggleAlergen"
                                            width="32" data-container="body" data-toggle="popover" data-placement="top" data-content="Sesamo"id='alerg_sesame'  style=" opacity:@if($product->product_detail AND $product->product_detail->alerg_sesame) 1 @else 0.3 @endif;">
                                    <img
                                            src="/img/allergens/Soy.png" class="img-fluid toggleAlergen"
                                            width="32" data-container="body" data-toggle="popover" data-placement="top" data-content="Soy"id='alerg_soy'  style=" opacity:@if($product->product_detail AND $product->product_detail->alerg_soy) 1 @else 0.3 @endif;">
                                    <img
                                            src="/img/allergens/Fish.png" class="img-fluid toggleAlergen"
                                            width="32" data-container="body" data-toggle="popover" data-placement="top" data-content="Fish"id='alerg_fish'  style=" opacity:@if($product->product_detail AND $product->product_detail->alerg_fish) 1 @else 0.3 @endif;">

                                </td>

                            </tr>

                            <tr><td><h6 class="inline-flex bg-dark text-white mt-4">Traduciones</h6></td><td><hr></td><td><hr></td></tr>
                            <tr>
                                <td colspan="3">

                                    <label for="lang1" class="form-label"><img src="/img/{{array_keys(Config::get('languages'))[1]}}.svg" width="16"><b>Language 1</b></label>
                                    <input type="text" name="lang1" class="input-tw w-full" style="min-width: 100%"  value="{{$product->product_detail->lang1 ?? ''}}">
                                </td>
                            </tr><tr>
                                <td colspan="3">

                                    <label for="lang2" class="form-label"><img src="/img/{{array_keys(Config::get('languages'))[2]}}.svg" width="16"><b>Language 2</b></label>
                                    <input type="text" name="lang2" class="input-tw w-full" style="min-width: 100%" value="{{$product->product_detail->lang2 ?? ''}}">
                                </td>
                            </tr><tr>
                                <td colspan="3">

                                    <label for="lang3" class="form-label" ><img src="/img/{{array_keys(Config::get('languages'))[3]}}.svg" width="16"><b>Language 3</b></label>
                                    <input type="text" name="lang3" class="input-tw w-full" style="min-width: 100%"  value="{{$product->product_detail->lang3 ?? ''}}">
                                </td>
                            </tr>
                            <tr><td><h6 class="inline-flex bg-dark text-white mt-4">Save</h6></td><td><hr></td><td><hr></td></tr>

                            <tr>
                                <td colspan="3">
                                    <button type="submit" class="btn-primary w-full max-w-md">SAVE</button>
                                </td>
                            </tr>

                        </table>

                        <div id="ProductPrice"></div>

                    </form>

        </div>
    </div>
@endsection