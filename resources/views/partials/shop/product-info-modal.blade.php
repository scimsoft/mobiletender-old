@php
    $detail = $product->product_detail;
    $langKeys = array_keys(config('languages', []));
@endphp
<dialog
    id="product-info-modal-{{ $product->id }}"
    class="w-[min(100vw-2rem,28rem)] max-h-[min(90vh,36rem)] overflow-y-auto rounded-xl border border-slate-200 p-0 shadow-xl backdrop:bg-slate-900/50"
    aria-labelledby="product-info-title-{{ $product->id }}"
>
    <div class="border-b border-slate-100 px-4 py-3">
        <h2 id="product-info-title-{{ $product->id }}" class="text-lg font-semibold text-slate-900">{{ $product->name }}</h2>
    </div>
    <div class="max-h-[60vh] overflow-y-auto p-4">
        <div class="mb-3 text-center">
            <img
                src="/dbimage/{{ $product->id }}.png"
                class="mx-auto max-h-48 w-auto max-w-full rounded-lg object-contain"
                alt=""
            />
        </div>
        @if (isset($detail->lang1) && $detail->lang1)
            <div class="mb-2 flex items-start gap-2 text-left text-sm text-slate-700">
                <img
                    src="/img/{{ $langKeys[1] ?? 'en' }}.svg"
                    width="16"
                    height="16"
                    class="mt-0.5 shrink-0"
                    alt=""
                />
                <span>{{ $detail->lang1 }}</span>
            </div>
        @endif
        @if (isset($detail->lang2) && $detail->lang2)
            <div class="mb-2 flex items-start gap-2 text-left text-sm text-slate-700">
                <img
                    src="/img/{{ $langKeys[2] ?? 'en' }}.svg"
                    width="16"
                    height="16"
                    class="mt-0.5 shrink-0"
                    alt=""
                />
                <span>{{ $detail->lang2 }}</span>
            </div>
        @endif
        @if (isset($detail->lang3) && $detail->lang3)
            <div class="mb-2 flex items-start gap-2 text-left text-sm text-slate-700">
                <img
                    src="/img/{{ $langKeys[3] ?? 'en' }}.svg"
                    width="16"
                    height="16"
                    class="mt-0.5 shrink-0"
                    alt=""
                />
                <span>{{ $detail->lang3 }}</span>
            </div>
        @endif
        @if ($detail->description)
            <p class="mt-2 whitespace-pre-line text-sm text-slate-700">{{ $detail->description }}</p>
        @endif

        <p class="mt-4 text-sm font-medium text-slate-800">{{ __('Alergenicos:') }}</p>
        <div class="mt-2 flex flex-wrap gap-1.5" role="list" aria-label="{{ __('Alérgenos') }}">
            @foreach (config('product_allergens') as $allergen)
                @if (! empty($detail->{$allergen['field']}))
                    <span role="listitem">
                        <img
                            src="/img/allergens/{{ $allergen['file'] }}"
                            class="h-8 w-8"
                            width="32"
                            height="32"
                            title="{{ __($allergen['label']) }}"
                            alt="{{ __($allergen['label']) }}"
                        />
                    </span>
                @endif
            @endforeach
        </div>
    </div>
    <div class="flex flex-wrap justify-end gap-2 border-t border-slate-100 bg-slate-50 px-4 py-3">
        <form method="dialog">
            <button type="submit" class="btn-secondary" value="close">{{ __('Cerrar') }}</button>
        </form>
        <button
            type="button"
            class="btn-primary add-to-cart"
            onclick="addProduct('{{ $product->id }}'); document.getElementById('product-info-modal-{{ $product->id }}').close();"
        >{{ __('Añadir') }}</button>
    </div>
</dialog>
