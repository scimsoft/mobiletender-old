@extends('layouts.shop')

@section('title', __('Ofertas') . ' — ' . config('app.name'))

@section('page', 'order-offers')

@section('content')
    <div class="mx-auto max-w-3xl space-y-4">
        <div class="flex items-center justify-between gap-3">
            <h1 class="text-xl font-semibold text-slate-900">{{ __('Ofertas') }}</h1>
            <a href="/order" class="btn-secondary text-sm no-underline">{{ __('Volver al menú') }}</a>
        </div>

        @if (session('status'))
            <div class="alert-success">{{ session('status') }}</div>
        @endif
        @if (session('error'))
            <div class="alert-error">{{ session('error') }}</div>
        @endif

        @if ($offers->isEmpty())
            <div class="card-tw">
                <div class="card-tw-body text-center text-slate-500">
                    <p>{{ __('No hay ofertas disponibles ahora mismo.') }}</p>
                </div>
            </div>
        @else
            <ul role="list" class="space-y-3">
                @foreach ($offers as $offer)
                    <li class="card-tw">
                        <div class="flex flex-col gap-3 p-4 sm:flex-row sm:items-center sm:gap-4">
                            <div class="min-w-0 flex-1">
                                <p class="text-base font-semibold text-slate-900">{{ $offer->name }}</p>
                                <p class="mt-1 text-sm text-slate-500">
                                    @foreach ($offer->offerProducts as $op)
                                        <span class="whitespace-nowrap">{{ $op->quantity }}× {{ optional($op->product)->name ?? '?' }}</span>@if (! $loop->last)<span class="text-slate-300">·</span> @endif
                                    @endforeach
                                </p>
                            </div>
                            <div class="flex items-center justify-between gap-3 sm:flex-shrink-0 sm:gap-4">
                                <span class="text-lg font-bold tabular-nums text-slate-900">@money($offer->final_price)</span>
                                <a href="{{ route('order.addoffer', $offer->id) }}" class="btn-primary no-underline">
                                    {{ __('Añadir') }}&nbsp;<img src="/img/cart.svg" width="16" alt="" class="inline" />
                                </a>
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
@endsection
