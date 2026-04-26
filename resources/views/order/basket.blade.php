@extends('layouts.shop')

@section('title', __('Cuenta') . ' — ' . config('app.name'))

@section('page', 'basket')

@php
    $tableNum = Session::get('tableNumber');
    $isTableMesa = $tableNum && (int) $tableNum < 100;
@endphp

@section('content')
    @foreach ($lines as $line)
        @if ($line->productid && ($line->attributes->updated == 'true' || (Auth::user() && Auth::user()->isManager())))
            <form id="cancel-basket-line-{{ $line->m_iLine }}" method="POST" action="{{ url('/order/cancelproduct/'.$line->m_iLine) }}" class="hidden">@csrf</form>
        @endif
    @endforeach

    <div
        id="shop-basket-page"
        class="mx-auto max-w-3xl max-sm:pb-28"
        data-ticket-id="{{ Session::get('ticketID') }}"
    >
        <div class="mb-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <h1 class="text-xl font-semibold text-slate-900">{{ __('Cuenta') }}</h1>
            <div class="flex flex-wrap items-center gap-2">
                <a href="/order/" class="btn-secondary inline-flex no-underline">{{ __('Mas cosas') }}</a>
                @if ($isTableMesa && $unprintedlines)
                    <a href="/checkout/printOrder/{{ Session::get('ticketID') }}" class="btn-primary inline-flex no-underline">{{ __('Pedir') }}</a>
                @endif
            </div>
        </div>

        <div class="card-tw overflow-hidden">
            @if ($basketIsEmpty)
                <div class="p-8 text-center text-slate-500" role="status">
                    {{ __('Tu cuenta está vacía.') }}
                </div>
            @else
                <ul role="list" class="divide-y divide-slate-100">
                    @foreach ($groupedLines as $group)
                        <li class="flex flex-wrap items-center gap-3 p-3 sm:gap-4">
                            <img
                                src="/dbimage/{{ $group->productid }}.png"
                                class="h-14 w-14 shrink-0 rounded-lg object-cover"
                                width="56"
                                height="56"
                                alt=""
                            />
                            <div class="min-w-0 flex-1 text-left">
                                <p class="font-medium text-slate-900">{{ $group->name }}</p>
                                <p class="text-sm text-slate-500">
                                    @if ($group->qty > 1)
                                        {{ $group->qty }}× @money($group->unitPrice * 1.1)
                                    @else
                                        @money($group->unitPrice * 1.1)
                                    @endif
                                </p>
                            </div>
                            <div class="ml-auto text-right sm:ml-0">
                                <span class="text-base font-semibold tabular-nums text-slate-900" aria-label="{{ __('Línea') }}">@money($group->total * 1.1)</span>
                            </div>
                            <div class="w-full pl-[4.5rem] sm:ml-auto sm:w-auto sm:pl-0">
                                @if ($group->canCancel)
                                    <button
                                        type="submit"
                                        form="cancel-basket-line-{{ $group->lastLineId }}"
                                        class="btn-secondary text-sm text-red-700 ring-red-200 hover:bg-red-50"
                                    >{{ __('Quitar') }}</button>
                                @else
                                    <span class="inline-block rounded-full bg-slate-100 px-2.5 py-1 text-xs text-slate-600">{{ __('Enviado') }}</span>
                                @endif
                            </div>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>

        @if (! $basketIsEmpty)
            <section class="card-tw mt-4 p-4" aria-label="{{ __('Resumen') }}">
                <div class="flex items-baseline justify-between gap-4">
                    <span class="text-sm font-medium uppercase tracking-wide text-slate-500">{{ __('TOTAL') }}</span>
                    <span class="text-2xl font-bold tabular-nums text-slate-900" id="basket-total-display" aria-live="polite">@money($totalBasketPrice * 1.1)</span>
                </div>
            </section>

            <div class="mt-4 hidden sm:block">
                @include('partials.shop.basket-actions')
            </div>

            <div id="scan-qr-instructions" class="mt-4 hidden">
                <p class="text-sm text-slate-600">
                    {{ __('Para añadir el pedido a su mesa, escanea con la camera el codgo QR que tiene en su mesa.') }}<br>
                    <img src="/img/qr-example.png" class="mx-auto mt-2 max-w-xs" alt="" />
                </p>
            </div>
            <div id="div-takeaway" class="mt-4 hidden">
                <p class="text-sm text-slate-600">{{ __('Horario de recogido:') }} 12:00 — 20:00</p>
            </div>
        @endif
    </div>
@endsection

@section('shop_sticky_cta')
    @if (! $basketIsEmpty)
        @include('partials.shop.basket-actions')
    @endif
@endsection
