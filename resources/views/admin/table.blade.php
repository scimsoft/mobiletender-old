@extends('layouts.admin')

@section('title', __('Mesas') . ' — ' . config('app.name'))

@section('content')
    <div class="flex flex-wrap gap-4">
        @foreach ($places as $place)
            @php
                $hasOpen = in_array($place->id, $openTicket) && $openTicketSum[$loop->iteration - 1] > 0;
                $unord = $ticketWithUnorderdItems[$loop->iteration - 1] ?? false;
            @endphp
            <a href="/order/table/{{ $place->id }}"
               class="inline-flex min-h-[5rem] min-w-[10rem] items-center justify-center rounded-xl border-2 px-4 py-4 text-center text-lg font-semibold transition hover:opacity-90
                @if ($hasOpen && $unord) border-amber-500 bg-amber-400 text-slate-900
                @elseif($hasOpen) border-green-600 bg-green-500 text-white
                @else border-slate-300 bg-slate-200 text-slate-700
                @endif">
                {{ $place->name }}
            </a>
        @endforeach
    </div>
@endsection
