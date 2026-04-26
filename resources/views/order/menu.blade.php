@extends('layouts.shop')

@section('title', __('Menú') . ' — ' . config('app.name'))

@section('page', 'menu-category')

@php
    $categoryChips = $categories->filter(function ($c) {
        return $c->parentid === null;
    });
    if ($categoryChips->isEmpty()) {
        $categoryChips = $categories;
    }
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
                        href="/menu/category/{{ $cat->id }}"
                        @class([
                            'chip',
                            'chip-active' => (string) $cat->id === (string) $activeCategoryId,
                        ])
                        @if ((string) $cat->id === (string) $activeCategoryId) aria-current="page" @endif
                    >{{ $cat->name }}</a>
                @endforeach
            </nav>

            @if ($productRows->isEmpty())
                <div class="py-12 text-center text-slate-500" role="status">
                    {{ __('No hay productos en esta categoría.') }}
                </div>
            @else
                <ul role="list" class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($productRows as $product)
                        <li class="card-tw card-tw-product flex gap-3 p-3">
                            <img
                                src="/dbimage/{{ $product->id }}.png"
                                class="h-20 w-20 shrink-0 rounded-lg object-cover"
                                alt="{{ $product->name }}"
                            />
                            <div class="flex min-w-0 flex-1 flex-col">
                                <h3 class="text-sm font-semibold text-slate-900">{{ $product->name }}</h3>
                                <p class="mt-1 text-base font-bold text-slate-900">@money($product->pricesell * 1.1)</p>
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
@endsection
