@extends('layouts.admin')

@section('title', 'STATS — ' . config('app.name'))

@section('page_header')
    <h1 class="text-2xl font-bold text-slate-900">STATS</h1>
@endsection

@section('content')
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
                    <td class="px-4 py-3 text-center font-semibold" colspan="2">{{ __('Venta de Hoy por el dia') }}</td>
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
                    <td class="px-4 py-3 text-center font-semibold" colspan="2">{{ __('Venta de Hoy por la noche') }}</td>
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
                    <td class="px-4 py-3 text-center font-semibold" colspan="2">{{ __('Venta por Categoria') }}</td>
                </tr>
                @foreach ($categoriesHoy as $categorie)
                    <tr class="border-b border-slate-100">
                        <td class="px-4 py-2">{{ $categorie->NAME }}</td>
                        <td class="px-4 py-2 text-right">@money($categorie->TOTAL)</td>
                    </tr>
                @endforeach
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
