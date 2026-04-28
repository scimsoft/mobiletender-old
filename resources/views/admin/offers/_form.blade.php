<div class="row">
    <div class="col-md-6">
        <label class="form-label"><b>Nombre</b></label>
        <input name="name" class="form-control" type="text"
               value="{{ old('name', $offer->name ?? '') }}" required>
    </div>
    <div class="col-md-3">
        <label class="form-label"><b>Precio final (con IVA)</b></label>
        <input name="final_price" class="form-control" type="text"
               value="{{ old('final_price', isset($offer) ? number_format($offer->final_price, 2, '.', '') : '') }}"
               required>
    </div>
    <div class="col-md-2">
        <label class="form-label"><b>Orden</b></label>
        <input name="sort_order" class="form-control" type="number"
               value="{{ old('sort_order', $offer->sort_order ?? 0) }}">
    </div>
    <div class="col-md-1 d-flex align-items-end">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="active"
                   id="active"
                   {{ (isset($offer) ? $offer->active : true) ? 'checked' : '' }}>
            <label class="form-check-label" for="active"><b>Activa</b></label>
        </div>
    </div>
</div>

<hr>

<h4>Productos en la oferta</h4>
<p class="text-muted">
    Esto es la "mesa virtual" de la oferta. Añade los productos y cantidades.
    Cuando un cliente pida la oferta, estos productos se moverán a su mesa
    con un ajuste para que el total sume el precio final indicado.
</p>

<table class="table table-bordered" id="offer-products-table">
    <thead>
        <tr>
            <th width="60%">Producto</th>
            <th width="20%">Cantidad</th>
            <th width="20%">Quitar</th>
        </tr>
    </thead>
    <tbody>
    @php
        $existingLines = isset($offer)
            ? $offer->offerProducts->map(fn ($op) => ['product_id' => $op->product_id, 'quantity' => $op->quantity])->toArray()
            : [];
    @endphp
    @forelse($existingLines as $line)
        <tr class="offer-product-row">
            <td>
                <select name="product_id[]" class="form-control">
                    <option value="">-- selecciona producto --</option>
                    @foreach($products as $product)
                        <option value="{{ $product->id }}"
                            {{ $line['product_id'] == $product->id ? 'selected' : '' }}>
                            {{ $product->name }} ({{ number_format($product->pricesell * 1.1, 2) }}€)
                        </option>
                    @endforeach
                </select>
            </td>
            <td>
                <input type="number" name="quantity[]" min="1"
                       value="{{ $line['quantity'] }}" class="form-control">
            </td>
            <td>
                <button type="button" class="btn btn-tab remove-offer-row">Quitar</button>
            </td>
        </tr>
    @empty
        <tr class="offer-product-row">
            <td>
                <select name="product_id[]" class="form-control">
                    <option value="">-- selecciona producto --</option>
                    @foreach($products as $product)
                        <option value="{{ $product->id }}">
                            {{ $product->name }} ({{ number_format($product->pricesell * 1.1, 2) }}€)
                        </option>
                    @endforeach
                </select>
            </td>
            <td>
                <input type="number" name="quantity[]" min="1" value="1" class="form-control">
            </td>
            <td>
                <button type="button" class="btn btn-tab remove-offer-row">Quitar</button>
            </td>
        </tr>
    @endforelse
    </tbody>
</table>

<button type="button" id="add-offer-row" class="btn btn-tab">+ Añadir producto</button>

@isset($productsSubtotal)
    <p class="mt-3">
        <b>Suma de productos (PVP):</b> {{ number_format($productsSubtotal, 2) }}€<br>
        <b>Precio final oferta:</b> {{ number_format((float) $offer->final_price, 2) }}€<br>
        <b>Descuento aplicado:</b>
        {{ number_format($productsSubtotal - (float) $offer->final_price, 2) }}€
    </p>
@endisset

<div class="text-center mt-3">
    <button type="submit" class="btn btn-primary">Guardar</button>
    <a href="{{ route('offers.index') }}" class="btn btn-tab">Cancelar</a>
</div>

@section('scripts')
<script>
    jQuery(document).ready(function () {
        $('#add-offer-row').on('click', function () {
            var $template = $('#offer-products-table tbody tr').first().clone();
            $template.find('select').val('');
            $template.find('input[type="number"]').val(1);
            $('#offer-products-table tbody').append($template);
        });

        $('#offer-products-table').on('click', '.remove-offer-row', function () {
            var $rows = $('#offer-products-table tbody tr');
            if ($rows.length > 1) {
                $(this).closest('tr').remove();
            } else {
                $(this).closest('tr').find('select').val('');
                $(this).closest('tr').find('input[type="number"]').val(1);
            }
        });
    });
</script>
@stop
