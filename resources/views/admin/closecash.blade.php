@extends('layouts.admin')

@section('title', __('Cerrar Caja') . ' — ' . config('app.name'))

@section('page_header')
    <h1 class="text-2xl font-bold text-slate-900">{{ __('Cerrar Caja') }}</h1>
@endsection

@section('content')
    @php($mtotal = 0)
    <div class="card-tw overflow-x-auto">
        <table class="min-w-full divide-y divide-slate-200 text-sm">
            <tbody class="divide-y divide-slate-100">
                @foreach ($totals as $total)
                    @if (str_contains($total->payment, 'cash'))
                        <tr>
                            <td class="px-4 py-2">{{ $total->payment }}</td>
                            <td class="px-4 py-2">{{ $total->notes }}</td>
                            <td class="px-4 py-2 text-right">@money($total->total)</td>
                        </tr>
                        @php($mtotal += $total->total)
                    @endif
                @endforeach
                <tr class="bg-slate-100 font-semibold">
                    <td class="px-4 py-2" colspan="2">{{ __('TOTAL Cash') }}</td>
                    <td class="px-4 py-2 text-right">@money($mtotal)</td>
                </tr>
                @foreach ($totals as $total)
                    @if (! str_contains($total->payment, 'cash'))
                        <tr>
                            <td class="px-4 py-2">{{ $total->payment }}</td>
                            <td class="px-4 py-2"></td>
                            <td class="px-4 py-2 text-right">@money($total->total)</td>
                        </tr>
                        @php($mtotal += $total->total)
                    @endif
                @endforeach
                <tr class="bg-slate-100 font-semibold">
                    <td class="px-4 py-2" colspan="2">{{ __('TOTAL') }}</td>
                    <td class="px-4 py-2 text-right">@money($mtotal)</td>
                </tr>
                <tr>
                    <td class="px-4 py-4" colspan="3">
                        <a href="/closemoney" class="btn-primary">{{ __('Cerrar Caja') }}</a>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
@endsection
