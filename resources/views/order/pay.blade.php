@extends('layouts.shop')

@section('title', __('Pago') . ' — ' . config('app.name'))

@section('page', 'pay')

@section('content')
@php
    $ticketId = Session::get('ticketID');
    $tableNumber = Session::get('tableNumber');
    $isPickup = (bool) Session::get('isPickup');
    $isTableMesa = $tableNumber && (int) $tableNumber < 100;

    $base = (float) $newLinesPrice;
    $iva = $base * 0.1;
    $amountDue = round($base + $iva, 2);
    $hasPreviousLines = $totalBasketPrice > $newLinesPrice;
    $previousBase = (float) $totalBasketPrice - $base;
    $previousIva = $previousBase * 0.1;
    $previousTotal = $previousBase + $previousIva;
    $grandTotal = round((float) $totalBasketPrice * 1.1, 2);

    $onApproveUrl = (config('customoptions.clean_table_after_order') || (is_numeric($ticketId) && (int) $ticketId < 100))
        ? url('/checkout/printOrderOnline/'.$ticketId)
        : url('/checkout/printOrderPagado/'.$ticketId);
    $payPageConfig = [
        'amount' => $amountDue,
        'paypalClientId' => config('paypal.client_id'),
        'onApproveUrl' => $onApproveUrl,
    ];

    if ($isPickup) {
        $referenceLabel = __('Pedido para recoger');
        $referenceValue = '#' . $tableNumber;
    } elseif ($isTableMesa) {
        $referenceLabel = __('Mesa');
        $referenceValue = (string) $tableNumber;
    } else {
        $referenceLabel = null;
        $referenceValue = null;
    }
@endphp

<div class="mx-auto max-w-md space-y-4 max-sm:pb-28">
    <header class="text-center">
        <h1 class="text-xl font-semibold text-slate-900">{{ __('Pago') }}</h1>
        @if ($referenceLabel)
            <p class="mt-1 text-sm text-slate-500">
                {{ $referenceLabel }}
                <span class="ml-1 inline-flex items-center rounded-full bg-slate-900 px-2.5 py-0.5 text-xs font-semibold text-white tabular-nums">{{ $referenceValue }}</span>
            </p>
        @endif
    </header>

    <section class="card-tw" aria-labelledby="pay-amount-label">
        <div class="flex flex-col items-center gap-1 px-6 py-6 text-center">
            <span id="pay-amount-label" class="text-xs font-medium uppercase tracking-wide text-slate-500">
                {{ __('A pagar') }}
            </span>
            <span class="text-4xl font-bold tabular-nums text-slate-900" data-pay-amount>
                @money($amountDue)
            </span>
            <dl class="mt-2 grid w-full max-w-xs grid-cols-2 gap-x-4 gap-y-0.5 text-xs text-slate-500">
                <dt class="text-left">{{ __('Base') }}</dt>
                <dd class="text-right tabular-nums">@money($base)</dd>
                <dt class="text-left">{{ __('IVA') }} (10%)</dt>
                <dd class="text-right tabular-nums">@money($iva)</dd>
            </dl>
        </div>

        @if ($hasPreviousLines)
            <div class="border-t border-slate-100 bg-slate-50 px-6 py-3 text-xs text-slate-600">
                <div class="flex items-baseline justify-between">
                    <span>{{ __('Ya en su cuenta') }}</span>
                    <span class="tabular-nums">@money($previousTotal)</span>
                </div>
                <div class="mt-1 flex items-baseline justify-between font-semibold text-slate-800">
                    <span>{{ __('Total acumulado') }}</span>
                    <span class="tabular-nums">@money($grandTotal)</span>
                </div>
            </div>
        @endif
    </section>

    <section class="space-y-3" aria-label="{{ __('Métodos de pago') }}">
        <div id="applepay-container" class="empty:hidden"></div>
        <div id="googlepay-container" class="empty:hidden"></div>
        <details class="group rounded-lg border border-slate-200 bg-white">
            <summary class="cursor-pointer select-none px-4 py-3 text-sm font-medium text-slate-700 marker:hidden">
                <span class="inline-flex items-center gap-2">
                    <svg class="h-4 w-4 text-slate-400 transition group-open:rotate-90" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 0 1 .02-1.06L11.168 10 7.23 6.29a.75.75 0 1 1 1.04-1.08l4.5 4.25a.75.75 0 0 1 0 1.08l-4.5 4.25a.75.75 0 0 1-1.06-.02Z" clip-rule="evenodd" />
                    </svg>
                    {{ __('Otras formas de pagar') }}
                </span>
            </summary>
            <div class="px-4 pb-4 pt-2">
                <div id="paypal-button-container"></div>
            </div>
        </details>
    </section>

    <p class="text-center text-xs text-slate-400">
        {{ __('Pago seguro. No se guardan los datos de la tarjeta.') }}
    </p>
</div>
@endsection

@push('scripts')
<script type="application/json" id="pay-page-config">
@json($payPageConfig)
</script>
@endpush
