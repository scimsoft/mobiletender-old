@extends('layouts.admin')

@section('title', __('Cuenta') . ' — ' . config('app.name'))

@section('page', 'admin-pay-basket')

@section('page_header')
    <h1 class="text-2xl font-bold text-slate-900">{{ __('Cuenta mesa') }} {{ $tableId }}</h1>
@endsection

@section('content')
    <div id="admin-pay-basket" class="card-tw">
        <div class="card-tw-body">
            <div class="mb-4 flex flex-wrap gap-2">
                <button type="button" onclick="history.back()" class="btn-secondary">{{ __('Volver') }}</button>
                <a href="/movefrom/{{ $tableId }}" class="btn-primary">{{ __('Mover') }}</a>
            </div>

            @foreach ($lines as $line)
                @if ($line->productid && ($line->attributes->updated == 'true' || (Auth::user() && Auth::user()->isManager())))
                    <form id="cancel-line-{{ $line->m_iLine }}" action="{{ url('/order/admincancelproduct/'.$line->m_iLine) }}" method="POST" class="hidden">@csrf</form>
                @endif
            @endforeach

            <form method="POST" action="/payed">
                @csrf
                <input type="hidden" name="tableId" value="{{ $tableId }}">
                <div class="overflow-x-auto">
                    <table id="products-table" class="min-w-full divide-y divide-slate-200 text-sm">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-2 py-2 text-left">Pay</th>
                                <th class="px-2 py-2 text-left" colspan="2">{{ __('Product') }}</th>
                                <th class="px-2 py-2 text-left">{{ __('Price') }}</th>
                                <th class="px-2 py-2"></th>
                                <th class="px-2 py-2 text-left">{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach ($lines as $line)
                                @if ($line->productid)
                                    <tr class="productrow">
                                        <td class="px-2 py-2 align-middle">
                                            <input class="box h-4 w-4 rounded border-slate-300 text-brand focus:ring-brand" type="checkbox" name="toPay[]" value="{{ $line->m_iLine }}" checked>
                                        </td>
                                        <td class="px-2 py-2">
                                            <img src="/dbimage/{{ $line->productid }}.png" alt="" class="h-8 w-8 object-contain">
                                        </td>
                                        <td class="px-2 py-2">{{ $line->attributes->product->name }}</td>
                                        <td class="amount px-2 py-2 font-semibold whitespace-nowrap">@money($line->price * 1.1)</td>
                                        <td class="px-2 py-2">
                                            @if ($line->attributes->updated != 'true')
                                                <img src="/img/printer-icon.png" width="24" alt="">
                                            @endif
                                        </td>
                                        <td class="px-2 py-2">
                                            @if ($line->attributes->updated == 'true' || (Auth::user() && Auth::user()->isManager()))
                                                <button type="submit" form="cancel-line-{{ $line->m_iLine }}" class="btn-tab text-xs">{{ __('Cancelar') }}</button>
                                            @else
                                                <button disabled class="btn-primary cursor-not-allowed text-xs opacity-60" type="button">{{ __('Enviado') }}</button>
                                            @endif
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                            <tr class="bg-slate-100 font-semibold">
                                <td class="px-2 py-2"></td>
                                <td class="px-2 py-2" colspan="2">{{ __('TOTAL') }}</td>
                                <td class="px-2 py-2">@money($totalBasketPrice * 1.1)</td>
                                <td class="px-2 py-2"></td>
                                <td class="px-2 py-2"></td>
                            </tr>
                            <tr class="bg-slate-50" id="subTotalRow">
                                <td class="px-2 py-2"></td>
                                <td class="px-2 py-2" colspan="2">{{ __('sub total') }}</td>
                                <td class="px-2 py-2" id="subTotal"></td>
                                <td class="px-2 py-2"></td>
                                <td class="px-2 py-2"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                @if ($totalBasketPrice > 0)
                    <div class="mt-6 space-y-3">
                        <a href="/checkout/printOrderTicket/{{ $tableId }}" class="btn-primary block w-full text-center" id="pagarEfectivo">TICKET</a>
                        <button type="submit" name="submit" class="btn-primary block w-full" value="cash">{{ __('Cobrar en efectivo') }}</button>
                        <button type="submit" name="submit" class="btn-primary block w-full" value="tarjeta">{{ __('Cobrar con tarjeta') }}</button>
                        <button type="submit" name="submit" class="btn-primary block w-full" value="online">{{ __('Cobrar online') }}</button>
                    </div>
                @endif
            </form>
        </div>
    </div>
@endsection
