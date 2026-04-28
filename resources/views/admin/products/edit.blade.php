@extends('layouts.reg')

@php
    /** @var \App\Models\UnicentaModels\Product $product */
    $detail = $product->product_detail;
    $translationLanguages = $translationLanguages ?? [];
@endphp

@section('content')
    <div class="container py-3">

        @if (session('status'))
            <div class="alert alert-success" role="alert">{{ session('status') }}</div>
        @endif
        @if (session('success'))
            <div class="alert alert-success" role="alert">{{ session('success') }}</div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger" role="alert">
                <strong>Por favor revisa los siguientes campos:</strong>
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="d-flex flex-wrap align-items-center justify-content-between mb-3">
            <div>
                <a href="{{ url('/products/index') }}" class="btn btn-link p-0 text-decoration-none">
                    &larr; Volver a productos
                </a>
                <h2 class="h4 mb-0 mt-1" id="ProductName">{{ $product->name ?: 'Producto' }}</h2>
            </div>
            <nav aria-label="Secciones del producto" class="d-none d-md-block">
                <ul class="nav nav-pills small">
                    <li class="nav-item"><a class="nav-link py-1 px-2" href="#section-general">General</a></li>
                    <li class="nav-item"><a class="nav-link py-1 px-2" href="#section-image">Imagen</a></li>
                    <li class="nav-item"><a class="nav-link py-1 px-2" href="#section-pricing">Precio</a></li>
                    <li class="nav-item"><a class="nav-link py-1 px-2" href="#section-extras">Extras</a></li>
                    <li class="nav-item"><a class="nav-link py-1 px-2" href="#section-allergens">Alérgenos</a></li>
                    <li class="nav-item"><a class="nav-link py-1 px-2" href="#section-translations">Traducciones</a></li>
                </ul>
            </nav>
        </div>

        <form action="{{ route('products.update', $product->id) }}" method="POST" id="product-form" novalidate>
            <input type="hidden" name="redirects_to" value="{{ url()->previous() }}">
            @method('PATCH')
            @csrf

            {{-- ============================================================
                 General
             ============================================================ --}}
            <fieldset class="card mb-4" id="section-general">
                <legend class="card-header h6 mb-0">General</legend>
                <div class="card-body">
                    <div class="form-group row">
                        <label for="name" class="col-sm-3 col-form-label">Nombre</label>
                        <div class="col-sm-9">
                            <input type="text" name="name" id="name"
                                   class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name', $product->name) }}" required maxlength="255">
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="description" class="col-sm-3 col-form-label">Descripción</label>
                        <div class="col-sm-9">
                            <textarea name="description" id="description" rows="3"
                                      class="form-control @error('description') is-invalid @enderror">{{ old('description', $detail->description ?? '') }}</textarea>
                            @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="category" class="col-sm-3 col-form-label">Categoría</label>
                        <div class="col-sm-9">
                            <select name="category" id="category"
                                    class="custom-select @error('category') is-invalid @enderror" required>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}"
                                        {{ (string) old('category', $product->category) === (string) $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="printto" class="col-sm-3 col-form-label">Impresora</label>
                        <div class="col-sm-3">
                            <input type="number" name="printto" id="printto" min="0" step="1" inputmode="numeric"
                                   class="form-control @error('printto') is-invalid @enderror"
                                   value="{{ old('printto', trim((string) ($product->printto ?? '1'))) }}">
                            @error('printto')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <label for="taxcat" class="col-sm-3 col-form-label">Tipo de IVA</label>
                        <div class="col-sm-3">
                            <input type="text" name="taxcat" id="taxcat" maxlength="10"
                                   class="form-control @error('taxcat') is-invalid @enderror"
                                   value="{{ old('taxcat', $product->taxcat ?? '001') }}" required>
                            @error('taxcat')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>
            </fieldset>

            {{-- ============================================================
                 Imagen
             ============================================================ --}}
            <fieldset class="card mb-4" id="section-image">
                <legend class="card-header h6 mb-0">Imagen</legend>
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-sm-4 text-center mb-3 mb-sm-0">
                            @if (! empty($hasImage))
                                <img src="{{ url('/dbimage/' . $product->id . '.png') }}?v={{ time() }}"
                                     alt="{{ $product->name }}"
                                     class="img-thumbnail"
                                     style="max-height: 220px; max-width: 100%;">
                            @else
                                <img src="{{ asset('img/no-image.png') }}"
                                     alt="Sin imagen"
                                     class="img-thumbnail"
                                     style="max-height: 220px; max-width: 100%; opacity: .6;">
                            @endif
                        </div>
                        <div class="col-sm-8">
                            <p class="text-muted small mb-2">
                                Sube y recorta la imagen del producto. Cuadrada (1:1) recomendada.
                            </p>
                            <a href="{{ url('/crop-image/' . $product->id) }}?redirects_to={{ urlencode(route('products.edit', $product->id)) }}"
                               class="btn btn-outline-primary js-edit-image"
                               data-product-id="{{ $product->id }}">
                                <i class="fas fa-image"></i> Editar imagen
                            </a>
                        </div>
                    </div>
                </div>
            </fieldset>

            {{-- ============================================================
                 System data (read-only)
             ============================================================ --}}
            <fieldset class="card mb-4">
                <legend class="card-header h6 mb-0">Datos del sistema</legend>
                <div class="card-body">
                    <div class="form-group row mb-0">
                        <label for="reference" class="col-sm-2 col-form-label">Referencia</label>
                        <div class="col-sm-4">
                            <input type="text" name="reference" id="reference" class="form-control-plaintext"
                                   value="{{ $product->reference }}" readonly>
                        </div>
                        <label for="code" class="col-sm-2 col-form-label">Código</label>
                        <div class="col-sm-4">
                            <input type="text" name="code" id="code" class="form-control-plaintext"
                                   value="{{ $product->code }}" readonly>
                        </div>
                    </div>
                </div>
            </fieldset>

            {{-- ============================================================
                 Pricing & stock
             ============================================================ --}}
            <fieldset class="card mb-4" id="section-pricing">
                <legend class="card-header h6 mb-0">Precio y stock</legend>
                <div class="card-body">
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="stockunits">Unidades de stock</label>
                            <input type="number" step="0.001" inputmode="decimal" min="0"
                                   name="stockunits" id="stockunits"
                                   class="form-control @error('stockunits') is-invalid @enderror"
                                   value="{{ old('stockunits', $product->stockunits) }}">
                            @error('stockunits')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group col-md-4">
                            <label for="pricebuy">Compra (sin IVA)</label>
                            <div class="input-group">
                                <input type="number" step="0.01" inputmode="decimal" min="0"
                                       name="pricebuy" id="pricebuy"
                                       class="form-control @error('pricebuy') is-invalid @enderror"
                                       value="{{ old('pricebuy', $product->pricebuy) }}" required>
                                <div class="input-group-append">
                                    <span class="input-group-text">€</span>
                                </div>
                                @error('pricebuy')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="pricesell">Venta (con IVA)</label>
                            <div class="input-group">
                                <input type="number" step="0.01" inputmode="decimal" min="0"
                                       name="pricesell" id="pricesell"
                                       class="form-control @error('pricesell') is-invalid @enderror"
                                       value="{{ old('pricesell', number_format($product->price_sell_gross, 2, '.', '')) }}"
                                       required>
                                <div class="input-group-append">
                                    <span class="input-group-text">€</span>
                                </div>
                                @error('pricesell')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>
                </div>
            </fieldset>

            {{-- ============================================================
                 Extras / addons
             ============================================================ --}}
            <fieldset class="card mb-4" id="section-extras">
                <legend class="card-header h6 mb-0">Extras a añadir</legend>
                <div class="card-body">
                    <div class="form-row align-items-end">
                        <div class="form-group col-md-5">
                            <label for="category_addon">Categoría</label>
                            <select class="custom-select" name="category_addon" id="category_addon">
                                @foreach ($categories as $categorie)
                                    <option value="{{ $categorie->id }}">{{ $categorie->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-5">
                            <label for="products_list">Producto disponible</label>
                            <select name="products_list" class="custom-select" id="products_list">
                                <option value="">— Selecciona una categoría —</option>
                            </select>
                        </div>
                        <div class="form-group col-md-2">
                            <button type="button" id="add-addon-btn" class="btn btn-outline-primary btn-block">
                                + Añadir
                            </button>
                        </div>
                    </div>

                    <hr>

                    <p class="small text-muted mb-2">Seleccionados</p>
                    <ul class="list-unstyled d-flex flex-wrap" id="addon-chips">
                        @forelse ($all_adons as $addon)
                            <li class="mr-2 mb-2">
                                <span class="badge badge-pill badge-secondary p-2"
                                      data-addon-id="{{ $addon->id }}">
                                    {{ $addon->name }}
                                    <button type="button"
                                            class="btn btn-sm btn-link text-white p-0 ml-1 remove-addon-btn"
                                            aria-label="Eliminar {{ $addon->name }}"
                                            data-addon-id="{{ $addon->id }}">
                                        &times;
                                    </button>
                                </span>
                            </li>
                        @empty
                            <li id="addon-empty" class="text-muted small">Sin extras seleccionados.</li>
                        @endforelse
                    </ul>
                </div>
            </fieldset>

            {{-- ============================================================
                 Allergens
             ============================================================ --}}
            <fieldset class="card mb-4" id="section-allergens">
                <legend class="card-header h6 mb-0">Alérgenos</legend>
                <div class="card-body">
                    <p class="small text-muted">
                        Pulsa un alérgeno para activarlo/desactivarlo. Se guarda al instante.
                    </p>
                    <x-product.allergen-grid :product="$product" :detail="$detail" />
                </div>
            </fieldset>

            {{-- ============================================================
                 Translations
             ============================================================ --}}
            @if (! empty($translationLanguages))
                <fieldset class="card mb-4" id="section-translations">
                    <legend class="card-header h6 mb-0">Traducciones</legend>
                    <div class="card-body">
                        @foreach (array_values($translationLanguages) as $idx => $langName)
                            @php
                                $langKey = array_keys($translationLanguages)[$idx] ?? null;
                                $field = 'lang' . ($idx + 1);
                                $flag = $langKey ? "/img/{$langKey}.svg" : null;
                            @endphp
                            <div class="form-group row">
                                <label for="{{ $field }}" class="col-sm-3 col-form-label">
                                    @if ($flag)
                                        <img src="{{ $flag }}" alt="" width="16" class="mr-1">
                                    @endif
                                    {{ $langName }}
                                </label>
                                <div class="col-sm-9">
                                    <input type="text" name="{{ $field }}" id="{{ $field }}"
                                           class="form-control"
                                           value="{{ old($field, $detail->{$field} ?? '') }}">
                                </div>
                            </div>
                        @endforeach
                    </div>
                </fieldset>
            @endif

            {{-- ============================================================
                 Sticky save bar
             ============================================================ --}}
            <div class="d-flex justify-content-between align-items-center sticky-bottom bg-white border-top py-2 mt-3"
                 style="position: sticky; bottom: 0;">
                <a href="{{ url('/products/index') }}" class="btn btn-outline-secondary">
                    Cancelar
                </a>
                <div>
                    <span id="save-toast" class="text-success small mr-3" role="status" aria-live="polite"></span>
                    <button type="submit" class="btn btn-primary">
                        Guardar cambios
                    </button>
                </div>
            </div>
        </form>

    </div>
@endsection

@section('scripts')
    <script>
        (function ($) {
            'use strict';

            var productId = @json($product->id);
            var formSelector = 'form#product-form';

            function showToast(msg, type) {
                var $t = $('#save-toast');
                $t.text(msg).removeClass('text-success text-danger')
                  .addClass(type === 'error' ? 'text-danger' : 'text-success');
                if ($t.data('hideTimer')) clearTimeout($t.data('hideTimer'));
                $t.data('hideTimer', setTimeout(function () { $t.text(''); }, 2500));
            }

            $(function () {
                // ---- Dirty form guard for "Edit Image" ----
                var formDirty = false;
                var $form = $(formSelector);

                $form.on('change input', ':input', function () { formDirty = true; });
                $form.on('submit', function () { formDirty = false; });

                $('.js-edit-image').on('click', function (e) {
                    if (formDirty && !window.confirm(
                        'Hay cambios sin guardar que se perderán si continúas. ' +
                        '¿Quieres descartar los cambios?'
                    )) {
                        e.preventDefault();
                    }
                });

                // ---- Smooth scroll for section nav ----
                $('.nav-pills a[href^="#section-"]').on('click', function (e) {
                    var target = $(this.getAttribute('href'));
                    if (target.length) {
                        e.preventDefault();
                        $('html, body').animate({ scrollTop: target.offset().top - 70 }, 250);
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
                            .toggleClass('btn-success', active)
                            .toggleClass('btn-outline-secondary', !active);
                        $btn.find('img').css('filter', active ? 'none' : 'grayscale(100%)');
                        showToast(active ? 'Alérgeno activado' : 'Alérgeno desactivado');
                    }).fail(function () {
                        showToast('No se pudo guardar el alérgeno', 'error');
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
                        $sel.append($('<option>', { value: '', text: '— Selecciona un producto —' }));
                        $.each(data, function (_, item) {
                            $sel.append($('<option>', { value: item.id, text: item.name }));
                        });
                    });
                }

                $('#category_addon').on('change', function () {
                    loadAddonProducts($(this).val());
                });
                // Load on initial render so the user doesn't have to toggle the
                // category dropdown twice to see anything.
                loadAddonProducts($('#category_addon').val());

                // ---- Add a selected addon as a chip ----
                $('#add-addon-btn').on('click', function () {
                    var $sel = $('#products_list');
                    var addonId = $sel.val();
                    var addonName = $sel.find('option:selected').text();
                    if (!addonId) {
                        showToast('Selecciona un producto', 'error');
                        return;
                    }
                    if ($('#addon-chips [data-addon-id="' + addonId + '"]').length) {
                        showToast('Ese extra ya está añadido', 'error');
                        return;
                    }
                    var price = window.prompt('Precio del extra (con IVA, en €)', '0');
                    if (price === null) return;

                    $.ajax({
                        url: '/addOnProduct/add',
                        type: 'POST',
                        data: { product_id: productId, adon_product_id: addonId, price: price },
                        dataType: 'json'
                    }).done(function () {
                        $('#addon-empty').remove();
                        var $chip = $('<li class="mr-2 mb-2"></li>').append(
                            $('<span class="badge badge-pill badge-secondary p-2"></span>')
                                .attr('data-addon-id', addonId)
                                .text(addonName + ' ')
                                .append(
                                    $('<button type="button" class="btn btn-sm btn-link text-white p-0 ml-1 remove-addon-btn">&times;</button>')
                                        .attr('aria-label', 'Eliminar ' + addonName)
                                        .attr('data-addon-id', addonId)
                                )
                        );
                        $('#addon-chips').append($chip);
                        showToast('Extra añadido');
                    }).fail(function () {
                        showToast('No se pudo añadir el extra', 'error');
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
                                '<li id="addon-empty" class="text-muted small">Sin extras seleccionados.</li>'
                            );
                        }
                        showToast('Extra eliminado');
                    }).fail(function () {
                        showToast('No se pudo eliminar el extra', 'error');
                    });
                });

                // ---- Bootstrap popovers on the allergen icons ----
                $('[data-toggle="popover"]').popover();

                // ---- Browser-level unload guard for any uncaught navigation ----
                window.addEventListener('beforeunload', function (e) {
                    if (formDirty) {
                        e.preventDefault();
                        e.returnValue = '';
                    }
                });
            });
        })(jQuery);
    </script>
@stop
