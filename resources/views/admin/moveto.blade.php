@extends('layouts.admin')

@section('title', __('Cuentas abiertas') . ' — ' . config('app.name'))

@section('page_header')
    <h1 class="text-2xl font-bold text-slate-900">{{ __('Cuentas abiertas') }}</h1>
@endsection

@section('content')
    <div class="flex flex-wrap gap-4">
        @foreach ($places as $place)
            <a href="/moveto/{{ $fromID }}/{{ $place->id }}"
               class="inline-flex min-h-[5rem] min-w-[10rem] flex-col items-center justify-center rounded-xl border-2 px-4 py-4 text-center text-lg font-semibold transition hover:opacity-90
                @if (in_array($place->id, $openTicket)) border-red-500 bg-red-500 text-white
                @else border-slate-300 bg-slate-200 text-slate-800
                @endif">
                <span>{{ $place->name }}</span>
                @if ($openTicketSum[$loop->iteration - 1] > 0)
                    <span class="mt-2 text-base">@money($openTicketSum[$loop->iteration - 1] * 1.1)</span>
                @endif
            </a>
        @endforeach
    </div>
@endsection
