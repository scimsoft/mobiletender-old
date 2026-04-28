@props([
    'product',
    'detail' => null,
])

@php
    $detail = $detail ?? ($product->product_detail ?? null);
    $allergens = [
        'alerg_apio'        => ['file' => 'Apio.png',           'label' => 'Apio'],
        'alerg_crustaceans' => ['file' => 'Crustaceans.png',    'label' => 'Marisco'],
        'alerg_dairy'       => ['file' => 'DairyProducts.png',  'label' => 'Lácteo'],
        'alerg_sulphites'   => ['file' => 'DioxideSulphites.png','label' => 'Sulfitos'],
        'alerg_gluten'      => ['file' => 'Gluten.png',         'label' => 'Gluten'],
        'alerg_lupins'      => ['file' => 'Lupins.png',         'label' => 'Altramuces'],
        'alerg_mollusks'    => ['file' => 'Mollusks.png',       'label' => 'Moluscos'],
        'alerg_egg'         => ['file' => 'Egg.png',            'label' => 'Huevo'],
        'alerg_mustard'     => ['file' => 'Mustard.png',        'label' => 'Mostaza'],
        'alerg_peanuts'     => ['file' => 'Peanuts.png',        'label' => 'Cacahuete'],
        'alerg_peelfruits'  => ['file' => 'PeelFruits.png',     'label' => 'Frutos Secos'],
        'alerg_sesame'      => ['file' => 'SesameGrains.png',   'label' => 'Sésamo'],
        'alerg_soy'         => ['file' => 'Soy.png',            'label' => 'Soja'],
        'alerg_fish'        => ['file' => 'Fish.png',           'label' => 'Pescado'],
    ];
@endphp

<div class="d-flex flex-wrap" id="allergen-grid">
    @foreach ($allergens as $key => $a)
        @php $active = $detail && $detail->{$key}; @endphp
        <button type="button"
                class="btn p-2 m-1 toggleAlergen {{ $active ? 'btn-success' : 'btn-outline-secondary' }}"
                data-toggle="popover"
                data-trigger="hover"
                data-placement="top"
                data-content="{{ $a['label'] }}"
                aria-label="{{ $a['label'] }}"
                aria-pressed="{{ $active ? 'true' : 'false' }}"
                data-active="{{ $active ? '1' : '0' }}"
                data-allergen="{{ $key }}"
                id="{{ $key }}">
            <img src="/img/allergens/{{ $a['file'] }}" alt="" width="32" height="32"
                 style="filter: {{ $active ? 'none' : 'grayscale(100%)' }};">
            <span class="d-block small mt-1">{{ $a['label'] }}</span>
        </button>
    @endforeach
</div>
