@extends('layouts.shop')

@section('title', __('Pedido') . ' — ' . config('app.name'))

@section('page', 'order-category')

@php
    $categoryChips = $categories->filter(function ($c) {
        return $c->parentid === null;
    });
    $activeCategoryId = $currentCategoryId ?? ($categoryChips->first()->id ?? null);
    $productRows = $products->filter(fn ($p) => (bool) $p->product_cat);
@endphp

@section('content')
    <div class="card-tw mx-auto max-w-5xl">
        <div class="card-tw-body">
            <nav
                class="-mx-1 mb-4 flex gap-2 overflow-x-auto px-1 pb-1 sm:mx-0 sm:flex-wrap sm:overflow-visible"
                aria-label="{{ __('Categorías') }}"
            >
                @foreach ($categoryChips as $cat)
                    <a
                        href="/order/category/{{ $cat->id }}"
                        @class([
                            'chip',
                            'chip-active' => (string) $cat->id === (string) $activeCategoryId,
                        ])
                        @if ((string) $cat->id === (string) $activeCategoryId) aria-current="page" @endif
                    >{{ __($cat->name) }}</a>
                @endforeach
            </nav>

            @if ($productRows->isEmpty())
                <div class="py-12 text-center text-slate-500" role="status">
                    {{ __('No hay productos en esta categoría.') }}
                </div>
            @else
                <ul
                    id="products-grid"
                    role="list"
                    class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-3"
                >
                    @foreach ($productRows as $product)
                        <li class="card-tw product-card card-tw-product flex gap-3 p-3" data-product-id="{{ $product->id }}">
                            <img
                                src="/dbimage/{{ $product->id }}.png"
                                class="img-drag h-20 w-20 shrink-0 cursor-pointer rounded-lg object-cover"
                                data-product-image
                                onclick="addProduct('{{ $product->id }}');"
                                alt="{{ $product->name }}"
                            />
                            <div class="flex min-w-0 flex-1 flex-col">
                                <h3 class="text-sm font-semibold leading-tight text-slate-900">{{ $product->name }}</h3>
                                <p class="mt-1 text-base font-bold text-slate-900">@money($product->pricesell * 1.1)</p>
                                <div class="mt-auto flex flex-wrap gap-2 pt-2">
                                    <button
                                        type="button"
                                        class="btn-primary add-to-cart text-sm"
                                        onclick="addProduct('{{ $product->id }}');"
                                    >{{ __('Añadir') }}&nbsp;<img src="/img/cart.svg" width="16" alt="" class="inline" /></button>
                                    @if ($product->product_detail)
                                        <button
                                            type="button"
                                            class="btn-secondary text-sm"
                                            onclick="document.getElementById('product-info-modal-{{ $product->id }}').showModal();"
                                        >{{ __('+info') }}</button>
                                    @endif
                                    @if (Auth::user() && Auth::user()->isAdmin())
                                        <a href="{{ route('products.edit', $product->id) }}" class="btn-secondary text-sm">{{ __('Editar') }}</a>
                                    @endif
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @endif

            <div class="mt-6 flex justify-center">
                {{ $products->links() }}
            </div>
        </div>
    </div>

    <div
        id="selectAddOnModal"
        class="fixed inset-0 z-[70] hidden items-center justify-center p-4"
        role="dialog"
        aria-modal="true"
        aria-labelledby="addonModalTitle"
    >
        <div
            class="absolute inset-0 bg-slate-900/50"
            data-close-addon-modal
        ></div>
        <div class="relative z-10 flex max-h-[90vh] w-full max-w-md flex-col overflow-hidden rounded-xl border border-slate-200 bg-white shadow-xl">
            <div class="border-b border-slate-100 px-4 py-3">
                <h2 id="addonModalTitle" class="text-lg font-semibold text-slate-900">{{ __('Con') }}</h2>
            </div>
            <div class="min-h-0 flex-1 overflow-y-auto p-4">
                <div id="addOnProductsList" class="space-y-2" role="listbox" aria-label="{{ __('Extras') }}"></div>
            </div>
            <div class="flex flex-wrap gap-2 border-t border-slate-100 px-4 py-3">
                <button type="button" class="btn-secondary" data-close-addon-modal>{{ __('Nada') }}</button>
                <button type="button" class="btn-primary" id="addAdonProductButton" hidden disabled aria-hidden="true">
                    {{ __('Añadir') }}
                </button>
            </div>
        </div>
    </div>

    @foreach ($productRows as $product)
        @if ($product->product_detail)
            @include('partials.shop.product-info-modal', ['product' => $product])
        @endif
    @endforeach
@endsection
