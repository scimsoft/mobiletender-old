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

<div id="allergen-grid" class="flex flex-wrap gap-2">
    @foreach ($allergens as $key => $a)
        @php $active = $detail && $detail->{$key}; @endphp
        <button type="button"
                id="{{ $key }}"
                class="toggleAlergen flex w-20 flex-col items-center rounded-lg border p-2 text-xs transition focus:outline-none focus:ring-2 focus:ring-brand
                       {{ $active
                           ? 'border-emerald-500 bg-emerald-50 text-emerald-800'
                           : 'border-slate-200 bg-white text-slate-600' }}"
                aria-label="{{ $a['label'] }}"
                aria-pressed="{{ $active ? 'true' : 'false' }}"
                data-active="{{ $active ? '1' : '0' }}"
                data-allergen="{{ $key }}"
                title="{{ $a['label'] }}">
            <img src="/img/allergens/{{ $a['file'] }}" alt="" width="32" height="32"
                 style="filter: {{ $active ? 'none' : 'grayscale(100%)' }};">
            <span class="mt-1 block">{{ $a['label'] }}</span>
        </button>
    @endforeach
</div>
