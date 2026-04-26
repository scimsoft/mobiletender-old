@extends('layouts.admin')

@section('title', __('Tickets cobrados') . ' — ' . config('app.name'))

@section('page_header')
    <h1 class="text-2xl font-bold text-slate-900">{{ __('Tickets cobrados') }}</h1>
@endsection

@section('page', 'admin-receipt-index')

@section('content')
    @if (Session::get('success'))
        <div class="mb-4 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">{{ Session::get('success') }}</div>
    @endif

    <div id="admin-receipt-index" class="card-tw overflow-x-auto">
        <table class="min-w-full divide-y divide-slate-200 text-sm">
            <thead class="bg-slate-50">
                <tr>
                    <th class="px-3 py-2 text-left font-semibold">{{ __('Fecha') }}</th>
                    <th class="px-3 py-2 text-left font-semibold">Nr</th>
                    <th class="px-3 py-2 text-left font-semibold">{{ __('Persona') }}</th>
                    <th class="px-3 py-2 text-left font-semibold">{{ __('Tipo') }}</th>
                    <th class="px-3 py-2 text-left font-semibold">{{ __('Cantidad') }}</th>
                    <th class="px-3 py-2 text-left font-semibold">{{ __('Acción') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @foreach ($receipts as $receipt)
                    <tr id="{{ $receipt->id }}">
                        <td class="px-3 py-2 whitespace-nowrap">{{ $receipt->datenew }}</td>
                        <td class="px-3 py-2">{{ $receipt->ticketid }}</td>
                        <td class="px-3 py-2">{{ $receipt->person }}</td>
                        <td class="px-3 py-2">
                            <select name="category" class="receipt-payment-type input-tw min-w-[8rem]">
                                <option value="cash" @selected($receipt->payment == 'cash')>cash</option>
                                <option value="tarjeta" @selected($receipt->payment == 'tarjeta')>tarjeta</option>
                                <option value="online" @selected($receipt->payment == 'online')>online</option>
                            </select>
                        </td>
                        <td class="px-3 py-2">@money($receipt->total)</td>
                        <td class="px-3 py-2">
                            <div class="flex flex-wrap gap-2">
                                <a href="/editreceipt/{{ $receipt->id }}" class="btn-primary text-xs">{{ __('Edit') }}</a>
                                <form method="POST" action="{{ url('/deletereceipt/'.$receipt->id) }}" class="inline" onsubmit="return confirm('¿Borrar recibo?');">
                                    @csrf
                                    <button type="submit" class="btn-danger text-xs">{{ __('Borrar') }}</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
