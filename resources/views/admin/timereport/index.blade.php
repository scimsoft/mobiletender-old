@extends('layouts.admin')

@section('title', __('Time report') . ' — ' . config('app.name'))

@section('page', 'timereport-page')

@section('page_header')
    <h1 class="text-2xl font-bold text-slate-900">{{ __('Marcar Entrada o Salida') }}</h1>
@endsection

@section('content')
    @if (Session::get('success'))
        <div class="mb-4 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">{{ Session::get('success') }}</div>
    @endif

    <div id="timereport-page" class="card-tw">
        <div class="card-tw-body">
            <input class="input-tw mb-4 max-w-md" id="searchText" type="text" placeholder="Search..">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 text-sm">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-3 py-2 text-left font-semibold">{{ __('Nombre') }}</th>
                            <th class="px-3 py-2 text-left font-semibold">{{ __('Fecha') }}</th>
                            <th class="px-3 py-2 text-left font-semibold">{{ __('Entrada') }}</th>
                            <th class="px-3 py-2 text-left font-semibold">{{ __('Salida') }}</th>
                            <th class="px-3 py-2 text-left font-semibold">{{ __('Tiempo') }}</th>
                        </tr>
                    </thead>
                    <tbody id="reportTable" class="divide-y divide-slate-100">
                        <tr>
                            <td class="px-3 py-2 font-medium">{{ Auth::user()->name }}</td>
                            @if (! $isChecking)
                                <td class="px-3 py-2">{{ \Carbon\Carbon::now()->format('D d-M-y') }}</td>
                                <td class="px-3 py-2">
                                    <a href="/timereport/enter" class="btn-primary text-sm">{{ __('Entrada') }}</a>
                                </td>
                                <td class="px-3 py-2"></td>
                            @else
                                <td class="px-3 py-2">{{ date('D d-M-y', strtotime($lastChecking->starttime)) }}</td>
                                <td class="px-3 py-2">{{ date('H:i', strtotime($lastChecking->starttime)) }}</td>
                                <td class="px-3 py-2"></td>
                            @endif
                            <td class="px-3 py-2">
                                @if ($isChecking)
                                    <a href="/timereport/exit" class="btn-primary text-sm">{{ __('Salida') }}</a>
                                @endif
                            </td>
                        </tr>
                        @foreach ($timereports->reverse() as $timereport)
                            @if ($loop->index >= count($timereports) - 1 && $isChecking)
                                @break
                            @endif
                            <tr>
                                <td class="px-3 py-2">{{ $timereport->user?->name }}</td>
                                <td class="px-3 py-2">{{ date('D d-M-y', strtotime($timereport->starttime)) }}</td>
                                <td class="px-3 py-2">{{ date('H:i', strtotime($timereport->starttime)) }}</td>
                                <td class="px-3 py-2">{{ date('H:i', strtotime($timereport->endtime)) }}</td>
                                @if (! is_null($timereport->endtime) && Auth::user()->isAdmin())
                                    <td class="px-3 py-2">{{ \Carbon\Carbon::parse($timereport->starttime)->diffForHumans($timereport->endtime, ['parts' => 2, 'syntax' => \Carbon\CarbonInterface::DIFF_ABSOLUTE]) }}</td>
                                @else
                                    <td class="px-3 py-2"></td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
