@extends('layouts.admin')

@section('title', __('Ofertas') . ' — ' . config('app.name'))

@section('page', 'admin-offers-index')

@section('page_header')
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <h1 class="text-2xl font-bold text-slate-900">{{ __('Ofertas') }}</h1>
        <a href="{{ route('offers.create') }}" class="btn-primary no-underline">{{ __('Nueva oferta') }}</a>
    </div>
@endsection

@section('content')
    @if ($message = Session::get('success'))
        <div class="alert-success">{{ $message }}</div>
    @endif
    @if ($message = Session::get('error'))
        <div class="alert-error">{{ $message }}</div>
    @endif

    @if ($offers->isEmpty())
        <div class="card-tw">
            <div class="card-tw-body text-center text-slate-500">
                <p>{{ __('No hay ofertas. Crea una nueva.') }}</p>
            </div>
        </div>
    @else
        {{-- Mobile: card list --}}
        <ul role="list" class="space-y-3 md:hidden">
            @foreach ($offers as $offer)
                <li class="card-tw">
                    <div class="space-y-3 p-4">
                        <div class="flex items-start justify-between gap-3">
                            <div class="min-w-0">
                                <p class="text-base font-semibold text-slate-900">{{ $offer->name }}</p>
                                <p class="mt-1 text-sm text-slate-500">{{ __('Orden') }}: {{ $offer->sort_order }}</p>
                            </div>
                            <span @class([
                                'badge-success' => $offer->active,
                                'badge-tw' => ! $offer->active,
                            ])>{{ $offer->active ? __('Activa') : __('Inactiva') }}</span>
                        </div>
                        <div class="flex flex-wrap gap-1.5">
                            @foreach ($offer->offerProducts as $op)
                                <span class="badge-info">{{ $op->quantity }}× {{ optional($op->product)->name ?? $op->product_id }}</span>
                            @endforeach
                        </div>
                        <div class="flex items-center justify-between gap-3 border-t border-slate-100 pt-3">
                            <span class="text-base font-semibold tabular-nums text-slate-900">@money($offer->final_price)</span>
                            <div class="flex gap-2">
                                <a href="{{ route('offers.edit', $offer->id) }}" class="btn-secondary text-sm no-underline">{{ __('Editar') }}</a>
                                <form action="{{ route('offers.destroy', $offer->id) }}" method="POST" onsubmit="return confirm('{{ __('¿Borrar la oferta?') }}');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-danger text-sm">{{ __('Borrar') }}</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </li>
            @endforeach
        </ul>

        {{-- Desktop: table --}}
        <div class="card-tw hidden md:block">
            <div class="overflow-x-auto">
                <table class="table-tw">
                    <thead>
                        <tr>
                            <th>{{ __('Activa') }}</th>
                            <th>{{ __('Orden') }}</th>
                            <th>{{ __('Nombre') }}</th>
                            <th>{{ __('Productos') }}</th>
                            <th>{{ __('Precio Final') }}</th>
                            <th class="text-right">{{ __('Acciones') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($offers as $offer)
                            <tr>
                                <td>
                                    @if ($offer->active)
                                        <span class="badge-success">{{ __('Sí') }}</span>
                                    @else
                                        <span class="badge-tw">{{ __('No') }}</span>
                                    @endif
                                </td>
                                <td class="tabular-nums">{{ $offer->sort_order }}</td>
                                <td class="font-medium text-slate-900">{{ $offer->name }}</td>
                                <td>
                                    <div class="flex flex-wrap gap-1.5">
                                        @foreach ($offer->offerProducts as $op)
                                            <span class="badge-info">{{ $op->quantity }}× {{ optional($op->product)->name ?? $op->product_id }}</span>
                                        @endforeach
                                    </div>
                                </td>
                                <td class="tabular-nums">@money($offer->final_price)</td>
                                <td>
                                    <div class="flex justify-end gap-2">
                                        <a href="{{ route('offers.edit', $offer->id) }}" class="btn-secondary text-xs no-underline">{{ __('Editar') }}</a>
                                        <form action="{{ route('offers.destroy', $offer->id) }}" method="POST" class="inline" onsubmit="return confirm('{{ __('¿Borrar la oferta?') }}');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-danger text-xs">{{ __('Borrar') }}</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
@endsection
