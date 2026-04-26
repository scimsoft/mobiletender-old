@php
    $tableNum = Session::get('tableNumber');
    $isTableMesa = $tableNum && (int) $tableNum < 100;
    $takeaway = config('customoptions.takeaway');
    $delivery = config('customoptions.delivery');
@endphp

<div class="mt-0 flex w-full flex-col gap-2">
    @if ($isTableMesa)
        @if (! $unprintedlines)
            <button type="button" class="btn-pay" data-basket-action="pagar-online">{{ __('Pagar online') }}</button>
            <div class="grid grid-cols-1 gap-2 sm:grid-cols-2">
                <button type="button" class="btn-secondary" data-basket-action="pagar-tarjeta">{{ __('Pagar con tarjeta') }}</button>
                <button type="button" class="btn-secondary" data-basket-action="pagar-efectivo">{{ __('Pagar en efectivo') }}</button>
            </div>
        @else
            <button type="button" class="btn-pay" data-basket-action="apuntar-mesa">{{ __('Pedir') }}</button>
        @endif
    @else
        <button type="button" class="btn-pay" data-basket-action="eatin-toggle">{{ __('Para tomarlo aqui') }}</button>
        @if ($takeaway)
            <button type="button" class="btn-secondary" data-basket-action="takeaway">{{ __('Para recoger') }}</button>
        @endif
        @if ($delivery)
            <a href="" class="btn-secondary inline-flex w-full items-center justify-center no-underline">{{ __('Para entregar') }}</a>
        @endif
    @endif
</div>
