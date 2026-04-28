@extends('layouts.reg')

@section('content')
    <div class="container">
        <div class="row justify-content-center">

            <div class="card">
                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    @if (session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif
                    @if ($errors->any())
                        <div class="alert alert-danger" role="alert">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
            </div>


            <div class="card text-center">
                <div id="ProductName" class="card-header">Product</div>

                <div class="card-body text-center">
                    @if (session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <form action="{{ route('products.update',$product->id) }}" method="POST"
                          class="form-inline justify-content-center">

                        <input type="hidden" name="redirects_to" value="{{URL::previous()}}">
                        @method('PATCH')
                        @csrf


                        <table class="table-borderless">
                            <tr>
                                <td colspan="3">
                                    <label for="name" class="form-label"><b>Nombre</b></label>
                                    <input name="name" class="form-control" type="text" value="{{$product->name}}" style="min-width: 100%">

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
                                    <textarea name="description" class="form-control"
                                              rows="3" size="12" style="min-width: 100%">{{$product->product_detail->description ?? ''}}</textarea>

                                </td>
                            </tr>
                            <tr><td><h6 class="inline-flex bg-dark text-white mt-4">Categoria</h6></td><td><hr></td><td><hr></td></tr>


                            <tr>

                                <td colspan="1 ">
                                    <label for="category" class="label label-default"><b>Categoria</b> </label>
                                    <select name="category" class="form-control">
                                        @foreach($categories as $category)
                                            <option value="{{$category->id}}" {{ ( $category->id == $product->category) ? 'selected' : '' }}>
                                                {{$category->name}}
                                            </option>

                                        @endforeach
                                    </select>
                                </td>
                                <td colspan="1">
                                    <label for="printto" class="form-label"><b>Printer Nr</b></label>
                                    <input name="printto" id="printto" class="form-control" type="text" size="2"
                                           value="{{ trim((string) ($product->printto ?? '1')) }}">

                                </td>
                                <td>
                                    <label for="taxcat" class="form-label"><b>Tipo de IVA</b></label>
                                    <input name="taxcat" id="taxcat" class="form-control" type="text"
                                           value="{{ $product->taxcat ?? '001' }}" size="3">

                                </td>
                            </tr>
                            <tr>

                            </tr>
                            <tr><td><h6 class="inline-flex bg-dark text-white mt-4">System data</h6></td><td><hr></td><td><hr></td></tr>
                        <tr>

                                <td colspan="2">
                                    <label for="reference" class="label label-default"><b>Referencia</b> </label>
                                    <input name="reference" class="form-control" type="text"
                                           value="{{$product->reference}}" readonly>
                                </td>
                                <td>
                                    <label for="code" class="form-label"><b>Codigo</b></label>
                                    <input name="code" class="form-control" type="text"
                                           value="{{$product->code}}" readonly>

                                </td>
                            </tr>


                            <tr><td><h6 class="inline-flex bg-dark text-white mt-4">Compra y Venta</h6></td><td><hr></td><td><hr></td></tr>

                            <tr>

                                <td colspan="1">
                                    <label for="stockunits" class="label label-default"><b>Unidades de stock</b>
                                    </label>
                                    <input name="stockunits" class="form-control" type="text" size="3"
                                           value="{{$product->stockunits}}">
                                </td>


                                <td>
                                    <label for="pricebuy" class="form-label"><b>Compra (sin IVA)</b> </label>
                                    <input name="pricebuy" class="form-control" type="text" size="3"
                                           value="{{$product->pricebuy}}">€

                                </td>
                                <td>
                                    <label for="pricesell" class="form-label"><b>Venta (con IVA)</b></label>
                                    <input name="pricesell" class="form-control" type="text" size="3"
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
                                    <select class="custom-select" name="category_addon" id="category_addon">
                                        @foreach($categories as $categorie)
                                            <option value="{{$categorie->id}}">{{$categorie->name}}</option>

                                        @endforeach
                                    </select></td>
                            </tr>

                            <tr>

                                <td colspan="2">
                                    <label for="products_list" class="form-label">Disponibles</label>

                                    <select name="products_list" class="custom-select"
                                            id="products_list">


                                    </select>

                                </td>
                                <td colspan="1">
                                    <label for="addon_products_list" class="form-label">Selecionados</label>
                                    <select name="addon_products_list" class="custom-select"
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
                                    <input type="text" name="lang1" class="form-control" style="min-width: 100%"  value="{{$product->product_detail->lang1 ?? ''}}">
                                </td>
                            </tr><tr>
                                <td colspan="3">

                                    <label for="lang2" class="form-label"><img src="/img/{{array_keys(Config::get('languages'))[2]}}.svg" width="16"><b>Language 2</b></label>
                                    <input type="text" name="lang2" class="form-control" style="min-width: 100%" value="{{$product->product_detail->lang2 ?? ''}}">
                                </td>
                            </tr><tr>
                                <td colspan="3">

                                    <label for="lang3" class="form-label" ><img src="/img/{{array_keys(Config::get('languages'))[3]}}.svg" width="16"><b>Language 3</b></label>
                                    <input type="text" name="lang3" class="form-control" style="min-width: 100%"  value="{{$product->product_detail->lang3 ?? ''}}">
                                </td>
                            </tr>
                            <tr><td><h6 class="inline-flex bg-dark text-white mt-4">Save</h6></td><td><hr></td><td><hr></td></tr>

                            <tr>
                                <td colspan="3">
                                    <button type="submit" class="btn btn-tab btn-block">SAVE</button>
                                </td>
                            </tr>

                        </table>


                        <div>
                            <div class="" id="ProductPrice" >
                            </div>
                        </div>

                    </form>

                </div>
            </div>

        </div>


@endsection
@section('scripts')

    <script>
        jQuery(document).ready(function () {
            $('#products_list').on('change', function () {
                var selected_product = $(this).val();
                var price = prompt("Price ?", "0");
                var productID = $(this).find(":selected").val();
                var addOnProductID = $(this).find(":selected").text();
                $("#addon_products_list").append($('<option>', {value: productID, text: addOnProductID}));
                addOnProduct(productID, price)
            })
            $('#addon_products_list').on('change', function () {
                var selected_product = $(this).val();
                var productID = $(this).find(":selected").val();
                var addOnProductID = $(this).find(":selected").text();
                $(this).find(":selected").remove();
                removeaddOnProduct(productID)
            })

            $('#category_addon').on('change', function () {
                var categoryid = $(this).find(":selected").val();
                jQuery.ajax({
                    url: '/products/list/' + categoryid,
                    type: "GET",

                    dataType: "json",
                    success: function (data) {

                        var $el = $("#products_list");
                        $el.empty(); // remove old options
                        $.each(data, function (id, name) {

                            $el.append($("<option></option>")
                                .attr("value", name.id).text(name.name));

                        })


                    }
                });

            })
            $('.toggleAlergen').click(function(e){
                var alergid =  this.id;
                var opacity = this.style;
                jQuery.ajax({
                    url: '/product/alergen',
                    type: "POST",
                    data: {product_id: '{{$product->id}}', alergen_id: alergid},
                    dataType: "json",
                    success: function (data) {



                    }
                });
                if(opacity.opacity == "1"){
                    opacity.opacity = "0.3"
                }else{
                    opacity.opacity = "1"
                }


            })

        })
        $(function () {
            $('[data-toggle="popover"]').popover()
        })

        function addOnProduct(addOnProductID, price) {
            jQuery.ajax({
                url: '/addOnProduct/add',
                type: "POST",
                data: {product_id: '{{$product->id}}', adon_product_id: addOnProductID, price: price},
                dataType: "json",
                success: function (data) {


                }
            });
        }

        function removeaddOnProduct(addOnProductID) {
            jQuery.ajax({
                url: '/addOnProduct/remove',
                type: "POST",
                data: {product_id: '{{$product->id}}', adon_product_id: addOnProductID},
                dataType: "json",
                success: function (data) {


                }
            });
        }

    </script>



@stop