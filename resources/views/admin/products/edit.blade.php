@extends('layouts.admin')

@section('title', __('Editar producto') . ' — ' . config('app.name'))

@section('page', 'product-edit')

@section('page_header')
    <div class="flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">{{ __('Editar producto') }}</h1>
            <p class="mt-1 text-slate-600">{{ $product->name }}</p>
        </div>
        <a href="{{ url()->previous() }}" class="btn-secondary inline-flex w-fit no-underline">{{ __('Volver') }}</a>
    </div>
@endsection

@php
    $allergens = [
        'apio' => ['file' => 'Apio.png', 'label' => __('Apio')],
        'crustaceans' => ['file' => 'Crustaceans.png', 'label' => __('Marisco')],
        'dairy' => ['file' => 'DairyProducts.png', 'label' => __('Lácteo')],
        'sulphites' => ['file' => 'DioxideSulphites.png', 'label' => __('Sulfitos')],
        'gluten' => ['file' => 'Gluten.png', 'label' => __('Gluten')],
        'lupins' => ['file' => 'Lupins.png', 'label' => __('Altramuces')],
        'mollusks' => ['file' => 'Mollusks.png', 'label' => __('Moluscos')],
        'egg' => ['file' => 'Egg.png', 'label' => __('Huevo')],
        'mustard' => ['file' => 'Mustard.png', 'label' => __('Mostaza')],
        'peanuts' => ['file' => 'Peanuts.png', 'label' => __('Cacahuete')],
        'peelfruits' => ['file' => 'PeelFruits.png', 'label' => __('Frutos secos')],
        'sesame' => ['file' => 'SesameGrains.png', 'label' => __('Sésamo')],
        'soy' => ['file' => 'Soy.png', 'label' => __('Soja')],
        'fish' => ['file' => 'Fish.png', 'label' => __('Pescado')],
    ];
    $languages = array_keys(Config::get('languages'));
@endphp

@section('content')
    <form id="product-edit-form" data-product-id="{{ $product->id }}"
          action="{{ route('products.update', $product->id) }}" method="POST"
          class="mx-auto max-w-4xl space-y-6">
        @method('PATCH')
        @csrf
        <input type="hidden" name="redirects_to" value="{{ URL::previous() }}">

        {{-- Basic info --}}
        <section class="card-tw">
            <div class="card-tw-header">{{ __('Información') }}</div>
            <div class="card-tw-body space-y-4">
                <div>
                    <label for="name" class="label-tw">{{ __('Nombre') }}</label>
                    <input id="name" name="name" type="text" class="input-tw" value="{{ $product->name }}" required>
                </div>
                <div>
                    <label for="description" class="label-tw">{{ __('Descripción') }}</label>
                    <textarea id="description" name="description" rows="3" class="input-tw">{{ $product->product_detail->description ?? '' }}</textarea>
                </div>
            </div>
        </section>

        {{-- Image --}}
        <section class="card-tw">
            <div class="card-tw-header">{{ __('Imagen') }}</div>
            <div class="card-tw-body">
                <div class="flex flex-col items-start gap-4 sm:flex-row sm:items-center">
                    <img src="data:image/png;base64,{{ $product->image }}" alt="" class="h-32 w-32 rounded-lg border border-slate-200 object-contain bg-white">
                    <a href="/crop-image/{{ $product->id }}" class="btn-secondary no-underline">{{ __('Editar imagen') }}</a>
                </div>
            </div>
        </section>

        {{-- Categoria / Printer / Tax --}}
        <section class="card-tw">
            <div class="card-tw-header">{{ __('Categoria') }}</div>
            <div class="card-tw-body grid gap-4 sm:grid-cols-3">
                <div>
                    <label for="category" class="label-tw">{{ __('Categoria') }}</label>
                    <select id="category" name="category" class="input-tw">
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" @selected($category->id == $product->category)>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="printto" class="label-tw">{{ __('Printer Nr') }}</label>
                    <input id="printto" name="printto" type="text" inputmode="numeric" class="input-tw" value="{{ $product->printto ?? '1' }}">
                </div>
                <div>
                    <label for="taxcat" class="label-tw">{{ __('Tipo de IVA') }}</label>
                    <input id="taxcat" name="taxcat" type="text" class="input-tw" value="001" readonly>
                </div>
            </div>
        </section>

        {{-- System data --}}
        <section class="card-tw">
            <div class="card-tw-header">{{ __('Datos del sistema') }}</div>
            <div class="card-tw-body grid gap-4 sm:grid-cols-2">
                <div>
                    <label for="reference" class="label-tw">{{ __('Referencia') }}</label>
                    <input id="reference" name="reference" type="text" class="input-tw bg-slate-50" value="{{ $product->reference }}" readonly>
                </div>
                <div>
                    <label for="code" class="label-tw">{{ __('Código') }}</label>
                    <input id="code" name="code" type="text" class="input-tw bg-slate-50" value="{{ $product->code }}" readonly>
                </div>
            </div>
        </section>

        {{-- Pricing --}}
        <section class="card-tw">
            <div class="card-tw-header">{{ __('Compra y Venta') }}</div>
            <div class="card-tw-body grid gap-4 sm:grid-cols-3">
                <div>
                    <label for="stockunits" class="label-tw">{{ __('Unidades de stock') }}</label>
                    <input id="stockunits" name="stockunits" type="text" inputmode="decimal" class="input-tw" value="{{ $product->stockunits }}">
                </div>
                <div>
                    <label for="pricebuy" class="label-tw">{{ __('Compra (sin IVA)') }} €</label>
                    <input id="pricebuy" name="pricebuy" type="text" inputmode="decimal" class="input-tw" value="{{ $product->pricebuy }}">
                </div>
                <div>
                    <label for="pricesell" class="label-tw">{{ __('Venta (con IVA)') }} €</label>
                    <input id="pricesell" name="pricesell" type="text" inputmode="decimal" class="input-tw" value="{{ $product->pricesell * 1.1 }}">
                </div>
            </div>
        </section>

        {{-- Addons --}}
        <section class="card-tw">
            <div class="card-tw-header">{{ __('Extras a Añadir') }}</div>
            <div class="card-tw-body space-y-4">
                <div>
                    <label for="category_addon" class="label-tw">{{ __('Selección de añadidos') }}</label>
                    <select id="category_addon" name="category_addon" class="input-tw">
                        @foreach ($categories as $categorie)
                            <option value="{{ $categorie->id }}">{{ $categorie->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label for="products_list" class="label-tw">{{ __('Disponibles') }}</label>
                        <select id="products_list" name="products_list" class="input-tw"></select>
                    </div>
                    <div>
                        <label for="addon_products_list" class="label-tw">{{ __('Seleccionados') }}</label>
                        <select id="addon_products_list" name="addon_products_list" class="input-tw">
                            <option value=""></option>
                            @foreach ($all_adons as $all_adon)
                                <option value="{{ $all_adon->id }}">{{ $all_adon->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </section>

        {{-- Allergens --}}
        <section class="card-tw">
            <div class="card-tw-header">{{ __('Alergénicos') }}</div>
            <div class="card-tw-body">
                <div class="grid grid-cols-4 gap-3 sm:grid-cols-7 lg:grid-cols-14">
                    @foreach ($allergens as $key => $info)
                        @php
                            $field = 'alerg_' . $key;
                            $active = $product->product_detail && $product->product_detail->{$field};
                        @endphp
                        <button
                            type="button"
                            id="alerg_{{ $key }}"
                            class="toggleAlergen flex flex-col items-center gap-1 rounded-lg border border-slate-200 p-2 transition hover:bg-slate-50 {{ $active ? '' : 'opacity-30' }}"
                            aria-pressed="{{ $active ? 'true' : 'false' }}"
                            title="{{ $info['label'] }}"
                        >
                            <img src="/img/allergens/{{ $info['file'] }}" width="32" height="32" alt="{{ $info['label'] }}">
                            <span class="text-xs text-slate-600">{{ $info['label'] }}</span>
                        </button>
                    @endforeach
                </div>
            </div>
        </section>

        {{-- Translations --}}
        <section class="card-tw">
            <div class="card-tw-header">{{ __('Traducciones') }}</div>
            <div class="card-tw-body space-y-4">
                @foreach ([1, 2, 3] as $i)
                    @if (isset($languages[$i]))
                        <div>
                            <label for="lang{{ $i }}" class="label-tw inline-flex items-center gap-2">
                                <img src="/img/{{ $languages[$i] }}.svg" width="16" height="12" alt="" class="border border-slate-200">
                                <span>{{ __('Idioma') }} {{ $i }}</span>
                            </label>
                            <input id="lang{{ $i }}" type="text" name="lang{{ $i }}" class="input-tw" value="{{ $product->product_detail->{'lang' . $i} ?? '' }}">
                        </div>
                    @endif
                @endforeach
            </div>
        </section>

        {{-- Save --}}
        <div class="sticky bottom-0 z-10 -mx-4 border-t border-slate-200 bg-white p-4 lg:-mx-6 lg:px-6">
            <button type="submit" class="btn-primary w-full sm:w-auto">{{ __('Guardar') }}</button>
        </div>
        <div id="ProductPrice"></div>
    </form>
@endsection
