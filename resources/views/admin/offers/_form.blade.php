<div class="grid gap-4 sm:grid-cols-12">
    <div class="sm:col-span-6">
        <label for="offer-name" class="label-tw">{{ __('Nombre') }}</label>
        <input id="offer-name" name="name" type="text" class="input-tw"
               value="{{ old('name', $offer->name ?? '') }}" required>
    </div>
    <div class="sm:col-span-3">
        <label for="offer-final-price" class="label-tw">{{ __('Precio final (con IVA)') }}</label>
        <input id="offer-final-price" name="final_price" type="text" inputmode="decimal" class="input-tw"
               value="{{ old('final_price', isset($offer) ? number_format($offer->final_price, 2, '.', '') : '') }}"
               required>
    </div>
    <div class="sm:col-span-2">
        <label for="offer-sort-order" class="label-tw">{{ __('Orden') }}</label>
        <input id="offer-sort-order" name="sort_order" type="number" inputmode="numeric" class="input-tw"
               value="{{ old('sort_order', $offer->sort_order ?? 0) }}">
    </div>
    <div class="flex items-end sm:col-span-1">
        <label class="inline-flex items-center gap-2 text-sm font-medium text-slate-700">
            <input class="h-4 w-4 rounded border-slate-300 text-brand focus:ring-brand"
                   type="checkbox" name="active" id="active"
                   {{ (isset($offer) ? $offer->active : true) ? 'checked' : '' }}>
            <span>{{ __('Activa') }}</span>
        </label>
    </div>
</div>

<hr class="my-6 border-slate-200">

<h2 class="text-lg font-semibold text-slate-900">{{ __('Productos en la oferta') }}</h2>
<p class="mt-1 text-sm text-slate-500">
    {{ __('Esto es la "mesa virtual" de la oferta. Añade los productos y cantidades. Cuando un cliente pida la oferta, estos productos se moverán a su mesa con un ajuste para que el total sume el precio final indicado.') }}
</p>

<div class="mt-4 overflow-hidden rounded-xl border border-slate-200">
    <div class="overflow-x-auto">
        <table class="table-tw" id="offer-products-table">
            <thead>
                <tr>
                    <th class="w-3/5">{{ __('Producto') }}</th>
                    <th class="w-1/5">{{ __('Cantidad') }}</th>
                    <th class="w-1/5 text-right">{{ __('Quitar') }}</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $existingLines = isset($offer)
                        ? $offer->offerProducts->map(fn ($op) => ['product_id' => $op->product_id, 'quantity' => $op->quantity])->toArray()
                        : [];
                @endphp
                @forelse ($existingLines as $line)
                    <tr class="offer-product-row">
                        <td>
                            <select name="product_id[]" class="input-tw">
                                <option value="">{{ __('-- selecciona producto --') }}</option>
                                @foreach ($products as $product)
                                    <option value="{{ $product->id }}" {{ $line['product_id'] == $product->id ? 'selected' : '' }}>
                                        {{ $product->name }} ({{ number_format($product->pricesell * 1.1, 2) }}€)
                                    </option>
                                @endforeach
                            </select>
                        </td>
                        <td>
                            <input type="number" name="quantity[]" min="1" inputmode="numeric"
                                   value="{{ $line['quantity'] }}" class="input-tw">
                        </td>
                        <td class="text-right">
                            <button type="button" class="btn-secondary remove-offer-row text-xs">{{ __('Quitar') }}</button>
                        </td>
                    </tr>
                @empty
                    <tr class="offer-product-row">
                        <td>
                            <select name="product_id[]" class="input-tw">
                                <option value="">{{ __('-- selecciona producto --') }}</option>
                                @foreach ($products as $product)
                                    <option value="{{ $product->id }}">
                                        {{ $product->name }} ({{ number_format($product->pricesell * 1.1, 2) }}€)
                                    </option>
                                @endforeach
                            </select>
                        </td>
                        <td>
                            <input type="number" name="quantity[]" min="1" value="1" inputmode="numeric" class="input-tw">
                        </td>
                        <td class="text-right">
                            <button type="button" class="btn-secondary remove-offer-row text-xs">{{ __('Quitar') }}</button>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<button type="button" id="add-offer-row" class="btn-secondary mt-3">+ {{ __('Añadir producto') }}</button>

@isset($productsSubtotal)
    <dl class="mt-4 grid gap-1 text-sm sm:grid-cols-3">
        <div>
            <dt class="text-slate-500">{{ __('Suma de productos (PVP)') }}</dt>
            <dd class="font-semibold text-slate-900">{{ number_format($productsSubtotal, 2) }}€</dd>
        </div>
        <div>
            <dt class="text-slate-500">{{ __('Precio final oferta') }}</dt>
            <dd class="font-semibold text-slate-900">{{ number_format((float) $offer->final_price, 2) }}€</dd>
        </div>
        <div>
            <dt class="text-slate-500">{{ __('Descuento aplicado') }}</dt>
            <dd class="font-semibold text-slate-900">{{ number_format($productsSubtotal - (float) $offer->final_price, 2) }}€</dd>
        </div>
    </dl>
@endisset

<div class="mt-6 flex flex-col-reverse gap-2 sm:flex-row sm:justify-end">
    <a href="{{ route('offers.index') }}" class="btn-secondary no-underline">{{ __('Cancelar') }}</a>
    <button type="submit" class="btn-primary">{{ __('Guardar') }}</button>
</div>

{{-- Add/remove row behavior is wired up in resources/js/admin/offer-edit.js --}}
