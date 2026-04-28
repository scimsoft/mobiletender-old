@extends('layouts.admin')

@section('title', 'STATS — ' . config('app.name'))

@section('page_header')
    <h1 class="text-2xl font-bold text-slate-900">STATS</h1>
@endsection

@section('content')
    <form method="GET" action="/stats" class="card-tw mb-4 flex flex-wrap items-end gap-3 p-4">
        <div class="flex flex-col">
            <label for="stats-date" class="mb-1 text-sm font-semibold text-slate-700">{{ __('Fecha para stats') }}</label>
            <input
                id="stats-date"
                type="date"
                name="date"
                value="{{ $selectedDate }}"
                class="rounded-md border border-slate-300 px-3 py-2 text-sm focus:border-slate-500 focus:outline-none focus:ring-1 focus:ring-slate-500"
            >
        </div>
        @if ($selectedCategoryId)
            <input type="hidden" name="category" value="{{ $selectedCategoryId }}">
        @endif
        <button type="submit" class="btn-tab inline-flex items-center gap-2 rounded-md bg-slate-900 px-4 py-2 text-sm font-medium text-white hover:bg-slate-800">
            {{ __('Filtrar') }}
        </button>
    </form>

    <div class="card-tw overflow-x-auto">
        <table class="min-w-full text-sm">
            <tbody>
                <tr class="bg-slate-100">
                    <td class="px-4 py-3 text-center font-semibold" colspan="2">{{ __('Caja Actual') }}</td>
                </tr>
                @foreach ($cajaActual as $cajaActualLine)
                    <tr class="border-b border-slate-100">
                        <td class="px-4 py-2">{{ $cajaActualLine->payment }}</td>
                        <td class="px-4 py-2 text-right">@money($cajaActualLine->total)</td>
                    </tr>
                @endforeach
                <tr><td class="py-2" colspan="2"></td></tr>
                <tr class="bg-slate-100">
                    <td class="px-4 py-3 text-center font-semibold" colspan="2">{{ __('Venta del día') }} ({{ $selectedDate }}) {{ __('por el día') }}</td>
                </tr>
                @foreach ($ventaLinesHoy as $ventaLine)
                    <tr class="border-b border-slate-100">
                        <td class="px-4 py-2">{{ $ventaLine->PAYMENT }}</td>
                        <td class="px-4 py-2 text-right">@money($ventaLine->TOTAL)</td>
                    </tr>
                @endforeach
                <tr class="font-semibold">
                    <td class="px-4 py-2">TOTAL</td>
                    <td class="px-4 py-2 text-right">@money($totalDay)</td>
                </tr>
                <tr><td class="py-2" colspan="2"></td></tr>
                <tr class="bg-slate-100">
                    <td class="px-4 py-3 text-center font-semibold" colspan="2">{{ __('Venta del día') }} ({{ $selectedDate }}) {{ __('por la noche') }}</td>
                </tr>
                @foreach ($ventaLinesHoyNight as $ventaLine)
                    <tr class="border-b border-slate-100">
                        <td class="px-4 py-2">{{ $ventaLine->PAYMENT }}</td>
                        <td class="px-4 py-2 text-right">@money($ventaLine->TOTAL)</td>
                    </tr>
                @endforeach
                <tr class="font-semibold">
                    <td class="px-4 py-2">TOTAL</td>
                    <td class="px-4 py-2 text-right">@money($totalNight)</td>
                </tr>
                <tr><td class="py-2" colspan="2"></td></tr>
                <tr class="bg-slate-100">
                    <td class="px-4 py-3 text-center font-semibold" colspan="2">{{ __('Venta por Categoria') }} ({{ $selectedDate }})</td>
                </tr>
                @foreach ($categoriesHoy as $categorie)
                    <tr class="border-b border-slate-100">
                        <td class="px-4 py-2">
                            <a
                                href="/stats?date={{ $selectedDate }}&category={{ $categorie->ID }}"
                                class="text-sky-700 hover:text-sky-900 hover:underline"
                            >{{ $categorie->NAME }}</a>
                        </td>
                        <td class="px-4 py-2 text-right">@money($categorie->TOTAL)</td>
                    </tr>
                @endforeach
                @if ($selectedCategory)
                    <tr><td class="py-2" colspan="2"></td></tr>
                    <tr class="bg-slate-100">
                        <td class="px-4 py-3 text-center font-semibold" colspan="2">{{ __('Detalle de productos') }}: {{ $selectedCategory->NAME }} ({{ $selectedDate }})</td>
                    </tr>
                    @forelse ($categoryProductDetails as $productDetail)
                        <tr class="border-b border-slate-100">
                            <td class="px-4 py-2">
                                {{ $productDetail->NAME }}
                                <span class="ml-1 text-xs text-slate-500">(x{{ number_format($productDetail->UNITS, 2) }})</span>
                            </td>
                            <td class="px-4 py-2 text-right">@money($productDetail->TOTAL)</td>
                        </tr>
                    @empty
                        <tr class="border-b border-slate-100">
                            <td class="px-4 py-3 text-center text-slate-500" colspan="2">{{ __('No hay productos para esta categoria en la fecha seleccionada.') }}</td>
                        </tr>
                    @endforelse
                @endif
                <tr><td class="py-2" colspan="2"></td></tr>
                <tr class="bg-slate-100">
                    <td class="px-4 py-3 text-center font-semibold" colspan="2">{{ __('Venta por dia') }}</td>
                </tr>
                @foreach ($ventaPorDias as $ventaPorDia)
                    <tr class="border-b border-slate-100">
                        <td class="px-4 py-2">{{ Carbon\Carbon::parse($ventaPorDia->daynumber)->format('l') }} — {{ $ventaPorDia->daynumber }}</td>
                        <td class="px-4 py-2 text-right">@money($ventaPorDia->TOTAL)</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
