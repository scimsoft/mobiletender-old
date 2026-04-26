@extends('layouts.admin')

@section('title', __('Pedidos') . ' — ' . config('app.name'))

@section('page_header')
    <h1 class="text-2xl font-bold text-slate-900">{{ __('Pedidos') }}</h1>
@endsection

@section('content')
    <div class="card-tw overflow-x-auto">
        <table class="min-w-full divide-y divide-slate-200 text-sm">
            <thead class="bg-slate-50">
                <tr>
                    <th class="px-4 py-2 text-left font-semibold">{{ __('Mesa') }}</th>
                    <th class="px-4 py-2 text-left font-semibold">{{ __('Cantidad') }}</th>
                    <th class="px-4 py-2 text-left font-semibold" colspan="3">{{ __('Acción') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @foreach ($openorders as $index => $order)
                    <tr>
                        <td class="px-4 py-3 font-semibold">{{ $order->id }}</td>
                        <td class="px-4 py-3">@money($openSums[$index] * 1.1)</td>
                        <td class="px-4 py-3">
                            <a href="{{ url('/adminvertable/'.$order->id) }}" class="btn-primary text-sm">{{ __('Ver') }}</a>
                        </td>
                        <td class="px-4 py-3">
                            <a href="{{ url('/printbill/'.$order->id) }}" class="btn-danger text-sm">{{ __('La Cuenta') }}</a>
                        </td>
                        <td class="px-4 py-3">
                            @if (Auth::user()->isAdmin())
                                <form method="POST" action="{{ url('/openorders/delete/'.$order->id) }}" class="inline" onsubmit="return confirm('¿Borrar pedido?');">
                                    @csrf
                                    <button type="submit" class="btn-danger text-sm">{{ __('Borrar') }}</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
