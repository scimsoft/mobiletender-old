@extends('layouts.admin')

@section('title', __('Products') . ' — ' . config('app.name'))

@section('page_header')
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <h1 class="text-2xl font-bold text-slate-900">{{ __('Products') }}</h1>
        <a class="btn-primary" href="{{ route('products.create') }}">{{ __('Producto Nuevo') }}</a>
    </div>
@endsection

@section('page', 'admin-products-index')

@section('content')
    @if (Session::get('success'))
        <div class="mb-4 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">{{ Session::get('success') }}</div>
    @endif

    <div id="admin-products-index" class="card-tw mb-6">
        <div class="card-tw-body">
            <div class="mb-4 flex flex-wrap items-center justify-center gap-2">
                @for ($i = 0; $i < min(6, count($categories)); $i++)
                    <a href="/products/index/{{ $categories[$i]->id }}" class="btn-secondary text-sm">{{ $categories[$i]->name }}</a>
                @endfor
                @if (count($categories) > 6)
                    <div class="relative inline-block text-left" x-data="{ open: false }">
                        <button type="button" @click="open = !open" class="btn-secondary text-sm">{{ __('Otros') }}</button>
                        <div x-show="open" @click.outside="open = false" class="absolute left-0 z-20 mt-1 max-h-64 min-w-[10rem] overflow-auto rounded-lg border border-slate-200 bg-white py-1 shadow-lg" style="display:none;">
                            @for ($i = 6; $i < count($categories); $i++)
                                <a href="/products/index/{{ $categories[$i]->id }}" class="block px-3 py-2 text-sm text-slate-700 hover:bg-slate-50">{{ $categories[$i]->name }}</a>
                            @endfor
                        </div>
                    </div>
                @endif
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 text-left text-sm">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-3 py-2 font-semibold text-slate-700">{{ __('Vis.') }}</th>
                            <th class="px-3 py-2 font-semibold text-slate-700">{{ __('Imagen') }}</th>
                            <th class="px-3 py-2 font-semibold text-slate-700">{{ __('Nombre') }}</th>
                            <th class="px-3 py-2 font-semibold text-slate-700">{{ __('Venta') }}</th>
                            <th class="px-3 py-2 font-semibold text-slate-700">{{ __('Acción') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($products as $product)
                            <tr id="{{ $product->id }}">
                                <td class="px-3 py-2">
                                    <input type="checkbox" class="catalog-toggle h-4 w-4 rounded border-slate-300 text-brand focus:ring-brand" name="catalogcheckbox" @if ($product->product_cat) checked @endif>
                                </td>
                                <td class="px-3 py-2">
                                    <img src="/dbimage/{{ $product->id }}.png" alt="" class="h-8 w-8 object-contain">
                                </td>
                                <td class="px-3 py-2 text-slate-900">{{ $product->name }}</td>
                                <td class="px-3 py-2">@money($product->pricesell * 1.1)</td>
                                <td class="px-3 py-2">
                                    <div class="flex flex-wrap gap-2">
                                        <a class="btn-tab text-xs" href="{{ route('products.edit', $product->id) }}">Edit</a>
                                        <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="inline" onsubmit="return confirm('{{ __('¿Borrar?') }}');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-tab text-xs">{{ __('Borrar') }}</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $products->links() }}
            </div>
        </div>
    </div>
@endsection
