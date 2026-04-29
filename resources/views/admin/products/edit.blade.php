@extends('layouts.admin')

@section('title', __('Editar producto') . ' — ' . config('app.name'))

@section('page', 'product-edit')

@section('page_header')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div class="min-w-0">
            <a href="{{ url('/products/index') }}"
               class="text-sm text-slate-500 hover:text-slate-700">
                &larr; {{ __('Volver a productos') }}
            </a>
            <h1 id="ProductName" class="mt-1 truncate text-2xl font-bold text-slate-900">
                {{ $product->name ?: __('Producto') }}
            </h1>
        </div>
        <nav aria-label="{{ __('Secciones del producto') }}" class="hidden md:block">
            <ul class="flex flex-wrap gap-1 text-xs">
                @foreach ([
                    'section-general'      => __('General'),
                    'section-image'        => __('Imagen'),
                    'section-pricing'      => __('Precio'),
                    'section-extras'       => __('Extras'),
                    'section-allergens'    => __('Alérgenos'),
                    'section-translations' => __('Traducciones'),
                ] as $anchor => $label)
                    <li>
                        <a href="#{{ $anchor }}"
                           class="rounded-md border border-slate-200 bg-white px-2 py-1 text-slate-600 hover:bg-slate-50 hover:text-slate-900">
                            {{ $label }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </nav>
    </div>
@endsection

@php
    /** @var \App\Models\UnicentaModels\Product $product */
    $detail = $product->product_detail;
    $translationLanguages = $translationLanguages ?? [];
@endphp

@section('content')
    <form id="product-edit-form"
          data-product-id="{{ $product->id }}"
          action="{{ route('products.update', $product->id) }}"
          method="POST"
          class="mx-auto max-w-5xl space-y-6"
          novalidate>
        <input type="hidden" name="redirects_to" value="{{ url()->previous() }}">
        @method('PATCH')
        @csrf

        {{-- ============================================================
             General
         ============================================================ --}}
        <fieldset id="section-general" class="card-tw">
            <legend class="card-tw-header w-full">{{ __('General') }}</legend>
            <div class="card-tw-body space-y-4">

                <div>
                    <label for="name" class="label-tw">{{ __('Nombre') }}</label>
                    <input type="text" id="name" name="name" maxlength="255" required
                           class="input-tw @error('name') border-red-400 @enderror"
                           value="{{ old('name', $product->name) }}">
                    @error('name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="description" class="label-tw">{{ __('Descripción') }}</label>
                    <textarea id="description" name="description" rows="3"
                              class="input-tw @error('description') border-red-400 @enderror">{{ old('description', $detail->description ?? '') }}</textarea>
                    @error('description')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="category" class="label-tw">{{ __('Categoría') }}</label>
                    <select id="category" name="category" required
                            class="input-tw @error('category') border-red-400 @enderror">
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}"
                                {{ (string) old('category', $product->category) === (string) $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label for="printto" class="label-tw">{{ __('Impresora') }}</label>
                        <input type="number" id="printto" name="printto" min="0" step="1" inputmode="numeric"
                               class="input-tw @error('printto') border-red-400 @enderror"
                               value="{{ old('printto', trim((string) ($product->printto ?? '1'))) }}">
                        @error('printto')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="taxcat" class="label-tw">{{ __('Tipo de IVA') }}</label>
                        <input type="text" id="taxcat" name="taxcat" maxlength="10" required
                               class="input-tw @error('taxcat') border-red-400 @enderror"
                               value="{{ old('taxcat', $product->taxcat ?? '001') }}">
                        @error('taxcat')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>
        </fieldset>

        {{-- ============================================================
             Imagen
         ============================================================ --}}
        <fieldset id="section-image" class="card-tw">
            <legend class="card-tw-header w-full">{{ __('Imagen') }}</legend>
            <div class="card-tw-body">
                <div class="grid items-center gap-6 sm:grid-cols-3">
                    <div class="text-center sm:col-span-1">
                        @if (! empty($hasImage))
                            <img src="{{ url('/dbimage/' . $product->id . '.png') }}?v={{ time() }}"
                                 alt="{{ $product->name }}"
                                 class="mx-auto h-48 w-48 rounded-lg border border-slate-200 object-cover">
                        @else
                            <img src="{{ asset('img/no-image.png') }}"
                                 alt="{{ __('Sin imagen') }}"
                                 class="mx-auto h-48 w-48 rounded-lg border border-slate-200 object-cover opacity-60">
                        @endif
                    </div>
                    <div class="sm:col-span-2">
                        <p class="mb-3 text-sm text-slate-500">
                            {{ __('Sube y recorta la imagen del producto. Cuadrada (1:1) recomendada.') }}
                        </p>
                        <a href="{{ url('/crop-image/' . $product->id) }}?redirects_to={{ urlencode(route('products.edit', $product->id)) }}"
                           class="btn-secondary js-edit-image"
                           data-product-id="{{ $product->id }}">
                            <i class="fa fa-image mr-2"></i>{{ __('Editar imagen') }}
                        </a>
                    </div>
                </div>
            </div>
        </fieldset>

        {{-- ============================================================
             Datos del sistema (read-only)
         ============================================================ --}}
        <fieldset class="card-tw">
            <legend class="card-tw-header w-full">{{ __('Datos del sistema') }}</legend>
            <div class="card-tw-body grid gap-4 sm:grid-cols-2">
                <div>
                    <label for="reference" class="label-tw">{{ __('Referencia') }}</label>
                    <input type="text" id="reference" name="reference" readonly
                           class="input-tw bg-slate-100"
                           value="{{ $product->reference }}">
                </div>
                <div>
                    <label for="code" class="label-tw">{{ __('Código') }}</label>
                    <input type="text" id="code" name="code" readonly
                           class="input-tw bg-slate-100"
                           value="{{ $product->code }}">
                </div>
            </div>
        </fieldset>

        {{-- ============================================================
             Precio y stock
         ============================================================ --}}
        <fieldset id="section-pricing" class="card-tw">
            <legend class="card-tw-header w-full">{{ __('Precio y stock') }}</legend>
            <div class="card-tw-body grid gap-4 sm:grid-cols-3">
                <div>
                    <label for="stockunits" class="label-tw">{{ __('Unidades de stock') }}</label>
                    <input type="number" id="stockunits" name="stockunits" step="0.001" inputmode="decimal" min="0"
                           class="input-tw @error('stockunits') border-red-400 @enderror"
                           value="{{ old('stockunits', $product->stockunits) }}">
                    @error('stockunits')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="pricebuy" class="label-tw">{{ __('Compra (sin IVA)') }}</label>
                    <div class="relative">
                        <input type="number" id="pricebuy" name="pricebuy" step="0.01" inputmode="decimal" min="0" required
                               class="input-tw pr-8 @error('pricebuy') border-red-400 @enderror"
                               value="{{ old('pricebuy', $product->pricebuy) }}">
                        <span class="pointer-events-none absolute inset-y-0 right-2 flex items-center text-sm text-slate-400">€</span>
                    </div>
                    @error('pricebuy')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="pricesell" class="label-tw">{{ __('Venta (con IVA)') }}</label>
                    <div class="relative">
                        <input type="number" id="pricesell" name="pricesell" step="0.01" inputmode="decimal" min="0" required
                               class="input-tw pr-8 @error('pricesell') border-red-400 @enderror"
                               value="{{ old('pricesell', number_format($product->price_sell_gross, 2, '.', '')) }}">
                        <span class="pointer-events-none absolute inset-y-0 right-2 flex items-center text-sm text-slate-400">€</span>
                    </div>
                    @error('pricesell')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
            </div>
        </fieldset>

        {{-- ============================================================
             Extras / addons
         ============================================================ --}}
        <fieldset id="section-extras" class="card-tw">
            <legend class="card-tw-header w-full">{{ __('Extras a añadir') }}</legend>
            <div class="card-tw-body space-y-4">
                <div class="grid items-end gap-4 sm:grid-cols-12">
                    <div class="sm:col-span-5">
                        <label for="category_addon" class="label-tw">{{ __('Categoría') }}</label>
                        <select id="category_addon" name="category_addon" class="input-tw">
                            @foreach ($categories as $categorie)
                                <option value="{{ $categorie->id }}">{{ $categorie->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="sm:col-span-5">
                        <label for="products_list" class="label-tw">{{ __('Producto disponible') }}</label>
                        <select id="products_list" name="products_list" class="input-tw">
                            <option value="">— {{ __('Selecciona una categoría') }} —</option>
                        </select>
                    </div>
                    <div class="sm:col-span-2">
                        <button type="button" id="add-addon-btn" class="btn-secondary w-full">
                            + {{ __('Añadir') }}
                        </button>
                    </div>
                </div>

                <hr class="border-slate-200">

                <p class="text-xs text-slate-500">{{ __('Seleccionados') }}</p>
                <ul class="flex flex-wrap gap-2" id="addon-chips">
                    @forelse ($all_adons as $addon)
                        <li>
                            <span class="inline-flex items-center gap-2 rounded-full bg-slate-100 px-3 py-1 text-sm text-slate-700"
                                  data-addon-id="{{ $addon->id }}">
                                {{ $addon->name }}
                                <button type="button"
                                        class="remove-addon-btn text-slate-500 hover:text-red-600"
                                        aria-label="{{ __('Eliminar') }} {{ $addon->name }}"
                                        data-addon-id="{{ $addon->id }}">
                                    &times;
                                </button>
                            </span>
                        </li>
                    @empty
                        <li id="addon-empty" class="text-sm text-slate-500">
                            {{ __('Sin extras seleccionados.') }}
                        </li>
                    @endforelse
                </ul>
            </div>
        </fieldset>

        {{-- ============================================================
             Allergens
         ============================================================ --}}
        <fieldset id="section-allergens" class="card-tw">
            <legend class="card-tw-header w-full">{{ __('Alérgenos') }}</legend>
            <div class="card-tw-body space-y-3">
                <p class="text-sm text-slate-500">
                    {{ __('Pulsa un alérgeno para activarlo/desactivarlo. Se guarda al instante.') }}
                </p>
                <x-product.allergen-grid :product="$product" :detail="$detail" />
            </div>
        </fieldset>

        {{-- ============================================================
             Translations
         ============================================================ --}}
        @if (! empty($translationLanguages))
            <fieldset id="section-translations" class="card-tw">
                <legend class="card-tw-header w-full">{{ __('Traducciones') }}</legend>
                <div class="card-tw-body space-y-4">
                    @foreach (array_values($translationLanguages) as $idx => $langName)
                        @php
                            $langKey = array_keys($translationLanguages)[$idx] ?? null;
                            $field = 'lang' . ($idx + 1);
                            $flag = $langKey ? "/img/{$langKey}.svg" : null;
                        @endphp
                        <div>
                            <label for="{{ $field }}" class="label-tw flex items-center gap-2">
                                @if ($flag)
                                    <img src="{{ $flag }}" alt="" width="16" class="inline-block">
                                @endif
                                <span>{{ $langName }}</span>
                            </label>
                            <input type="text" id="{{ $field }}" name="{{ $field }}"
                                   class="input-tw"
                                   value="{{ old($field, $detail->{$field} ?? '') }}">
                        </div>
                    @endforeach
                </div>
            </fieldset>
        @endif

        {{-- ============================================================
             Sticky save bar
         ============================================================ --}}
        <div class="sticky bottom-0 -mx-4 flex items-center justify-between border-t border-slate-200 bg-white/95 px-4 py-3 backdrop-blur lg:-mx-6 lg:px-6">
            <a href="{{ url('/products/index') }}" class="btn-secondary">
                {{ __('Cancelar') }}
            </a>
            <div class="flex items-center gap-3">
                <span id="save-toast" class="text-sm text-emerald-600" role="status" aria-live="polite"></span>
                <button type="submit" class="btn-primary">{{ __('Guardar cambios') }}</button>
            </div>
        </div>
    </form>
@endsection

@push('scripts')
    <script>
        (function ($) {
            'use strict';

            var productId = @json($product->id);

            function showToast(msg, type) {
                var $t = $('#save-toast');
                $t.text(msg)
                  .removeClass('text-emerald-600 text-red-600')
                  .addClass(type === 'error' ? 'text-red-600' : 'text-emerald-600');
                if ($t.data('hideTimer')) clearTimeout($t.data('hideTimer'));
                $t.data('hideTimer', setTimeout(function () { $t.text(''); }, 2500));
            }

            $(function () {
                // ---- Dirty form guard ----
                var formDirty = false;
                var $form = $('#product-edit-form');

                $form.on('change input', ':input', function () { formDirty = true; });
                $form.on('submit', function () { formDirty = false; });

                $('.js-edit-image').on('click', function (e) {
                    if (formDirty && !window.confirm(
                        '{{ __('Hay cambios sin guardar que se perderán si continúas. ¿Quieres descartar los cambios?') }}'
                    )) {
                        e.preventDefault();
                    }
                });

                // ---- Smooth scroll for section nav ----
                $('a[href^="#section-"]').on('click', function (e) {
                    var target = $(this.getAttribute('href'));
                    if (target.length) {
                        e.preventDefault();
                        $('html, body').animate({ scrollTop: target.offset().top - 80 }, 250);
                    }
                });

                // ---- Allergen toggles ----
                $('#allergen-grid').on('click', '.toggleAlergen', function () {
                    var $btn = $(this);
                    var allergen = $btn.data('allergen');
                    $btn.prop('disabled', true);
                    $.ajax({
                        url: '/product/alergen',
                        type: 'POST',
                        data: { product_id: productId, alergen_id: allergen },
                        dataType: 'json'
                    }).done(function (data) {
                        var active = !!(data && data.active);
                        $btn.attr('aria-pressed', active ? 'true' : 'false')
                            .data('active', active ? 1 : 0)
                            .toggleClass('border-emerald-500 bg-emerald-50 text-emerald-800', active)
                            .toggleClass('border-slate-200 bg-white text-slate-600', !active);
                        $btn.find('img').css('filter', active ? 'none' : 'grayscale(100%)');
                        showToast(active
                            ? '{{ __('Alérgeno activado') }}'
                            : '{{ __('Alérgeno desactivado') }}');
                    }).fail(function () {
                        showToast('{{ __('No se pudo guardar el alérgeno') }}', 'error');
                    }).always(function () {
                        $btn.prop('disabled', false);
                    });
                });

                // ---- Addon category change loads available products ----
                function loadAddonProducts(catId) {
                    if (!catId) return;
                    $.ajax({
                        url: '/products/list/' + catId,
                        type: 'GET',
                        dataType: 'json'
                    }).done(function (data) {
                        var $sel = $('#products_list').empty();
                        $sel.append($('<option>', { value: '', text: '— {{ __('Selecciona un producto') }} —' }));
                        $.each(data, function (_, item) {
                            $sel.append($('<option>', { value: item.id, text: item.name }));
                        });
                    });
                }

                $('#category_addon').on('change', function () {
                    loadAddonProducts($(this).val());
                });
                loadAddonProducts($('#category_addon').val());

                // ---- Add a selected addon as a chip ----
                $('#add-addon-btn').on('click', function () {
                    var $sel = $('#products_list');
                    var addonId = $sel.val();
                    var addonName = $sel.find('option:selected').text();
                    if (!addonId) {
                        showToast('{{ __('Selecciona un producto') }}', 'error');
                        return;
                    }
                    if ($('#addon-chips [data-addon-id="' + addonId + '"]').length) {
                        showToast('{{ __('Ese extra ya está añadido') }}', 'error');
                        return;
                    }
                    var price = window.prompt('{{ __('Precio del extra (con IVA, en €)') }}', '0');
                    if (price === null) return;

                    $.ajax({
                        url: '/addOnProduct/add',
                        type: 'POST',
                        data: { product_id: productId, adon_product_id: addonId, price: price },
                        dataType: 'json'
                    }).done(function () {
                        $('#addon-empty').remove();
                        var $chip = $('<li></li>').append(
                            $('<span class="inline-flex items-center gap-2 rounded-full bg-slate-100 px-3 py-1 text-sm text-slate-700"></span>')
                                .attr('data-addon-id', addonId)
                                .text(addonName + ' ')
                                .append(
                                    $('<button type="button" class="remove-addon-btn text-slate-500 hover:text-red-600">&times;</button>')
                                        .attr('aria-label', '{{ __('Eliminar') }} ' + addonName)
                                        .attr('data-addon-id', addonId)
                                )
                        );
                        $('#addon-chips').append($chip);
                        showToast('{{ __('Extra añadido') }}');
                    }).fail(function () {
                        showToast('{{ __('No se pudo añadir el extra') }}', 'error');
                    });
                });

                // ---- Remove addon ----
                $('#addon-chips').on('click', '.remove-addon-btn', function () {
                    var addonId = $(this).data('addon-id');
                    var $li = $(this).closest('li');
                    $.ajax({
                        url: '/addOnProduct/remove',
                        type: 'POST',
                        data: { product_id: productId, adon_product_id: addonId },
                        dataType: 'json'
                    }).done(function () {
                        $li.remove();
                        if ($('#addon-chips li').length === 0) {
                            $('#addon-chips').append(
                                '<li id="addon-empty" class="text-sm text-slate-500">{{ __('Sin extras seleccionados.') }}</li>'
                            );
                        }
                        showToast('{{ __('Extra eliminado') }}');
                    }).fail(function () {
                        showToast('{{ __('No se pudo eliminar el extra') }}', 'error');
                    });
                });

                // ---- Browser-level unload guard ----
                window.addEventListener('beforeunload', function (e) {
                    if (formDirty) {
                        e.preventDefault();
                        e.returnValue = '';
                    }
                });
            });
        })(jQuery);
    </script>
@endpush
