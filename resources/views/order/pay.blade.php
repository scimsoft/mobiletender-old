@extends('layouts.shop')

@section('title', __('Pago') . ' — ' . config('app.name'))

@section('page', 'pay')

@section('content')
@php
    $ticketId = Session::get('ticketID');
    $onApproveUrl = (config('customoptions.clean_table_after_order') || (is_numeric($ticketId) && (int) $ticketId < 100))
        ? url('/checkout/printOrderOnline/'.$ticketId)
        : url('/checkout/printOrderPagado/'.$ticketId);
    $payPageConfig = [
        'amount' => round((float) $newLinesPrice * 1.1, 2),
        'paypalClientId' => config('paypal.client_id'),
        'onApproveUrl' => $onApproveUrl,
    ];
@endphp
<div class="card-tw mx-auto max-w-3xl">
    <div class="card-tw-header text-center"><b>{{ __('Pedido') }}</b></div>
    <div class="card-tw-body text-center">
                <table id="products-table" class="table ">
                    <thead>


                    </thead>
                    <tbody>

                        <tr>
                            <td colspan="5">&nbsp;</td>
                        </tr>
                        <tr>
                            <td colspan="">&nbsp;</td>
                            <td colspan="">Base</td>
                            <td colspan="">IVA</td>
                            <td colspan=""></td>
                            <td colspan="">Total</td>
                        </tr>
                        <tr>
                            <td colspan="">TOTAL</td>
                            <td>@money($totalBasketPrice)</td>
                            <td>@money($totalBasketPrice*0.1)</td>

                            <td></td>
                            <td>@money($totalBasketPrice*1.1)</td>
                        </tr>
                        <tr>
                            <td colspan=""><b>A Pedir</b></td>
                            <td>@money($newLinesPrice)</td>
                            <td>@money($newLinesPrice*0.1)</td>

                            <td></td>
                            <td>&nbsp;<b>@money($newLinesPrice*1.1)</b></td>


                        </tr>

                        <tr>
                            <td colspan="5">&nbsp;</td>
                        </tr>
                        {{--
                        Si tiene numero de mesa puede ser:
                        1. de prepago
                        2. a cuenta
                        --}}


                    </tbody>
                </table>


                <div class="mx-auto max-w-sm space-y-3 pt-2 text-left">
                    <div id="applepay-container" class="empty:hidden"></div>
                    <div id="googlepay-container" class="empty:hidden"></div>
                    <details class="group rounded-lg border border-slate-200 bg-white">
                        <summary class="cursor-pointer select-none px-4 py-3 text-sm font-medium text-slate-700 marker:hidden">
                            {{ __('Otras formas de pagar') }}
                        </summary>
                        <div class="px-4 pb-4 pt-2">
                            <div id="paypal-button-container"></div>
                        </div>
                    </details>
                </div>

    </div>
</div>
@endsection

@push('scripts')
<script type="application/json" id="pay-page-config">
@json($payPageConfig)
</script>
@endpush
