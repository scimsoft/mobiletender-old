@extends('layouts.admin')

@section('title', __('Time report') . ' — ' . config('app.name'))

@section('page_header')
    <h1 class="text-2xl font-bold text-slate-900">{{ __('Informe') }}</h1>
@endsection

@section('content')
    @if (Session::get('success'))
        <div class="mb-4 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">{{ Session::get('success') }}</div>
    @endif

    <div class="card-tw overflow-x-auto">
        <table class="min-w-full divide-y divide-slate-200 text-sm">
            <thead class="bg-slate-50">
                <tr>
                    <th class="px-3 py-2 text-left font-semibold">User</th>
                    <th class="px-3 py-2 text-left font-semibold">Date</th>
                    <th class="px-3 py-2 text-left font-semibold">{{ __('Hora de entrada') }}</th>
                    <th class="px-3 py-2 text-left font-semibold">Start Break</th>
                    <th class="px-3 py-2 text-left font-semibold">End Break</th>
                    <th class="px-3 py-2 text-left font-semibold">{{ __('Hora de Salida') }}</th>
                    <th class="px-3 py-2 text-left font-semibold">Total</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @if (Auth::user() && Auth::user()->isAdmin())
                    @foreach ($timereports as $timereport)
                        <tr>
                            <td class="px-3 py-2">{{ $timereport->user->name }}</td>
                            <td class="px-3 py-2">{{ date('d-M-y', strtotime($timereport->starttime)) }}</td>
                            <td class="px-3 py-2">{{ date('H:i', strtotime($timereport->starttime)) }}</td>
                            <td class="px-3 py-2">{{ date('H:i', strtotime($timereport->breakstarttime)) }}</td>
                            <td class="px-3 py-2">{{ date('H:i', strtotime($timereport->breakendtime)) }}</td>
                            <td class="px-3 py-2">{{ date('H:i', strtotime($timereport->endtime)) }}</td>
                            @if (! is_null($timereport->endtime))
                                <td class="px-3 py-2">{{ \Carbon\Carbon::parse($timereport->starttime)->diffForHumans($timereport->endtime, ['parts' => 2, 'syntax' => \Carbon\CarbonInterface::DIFF_ABSOLUTE]) }}</td>
                            @else
                                <td class="px-3 py-2"></td>
                            @endif
                        </tr>
                    @endforeach
                @endif
                <tr>
                    <td class="px-3 py-2"></td>
                    @if (! $isChecking)
                        <td class="px-3 py-2"></td>
                        <td class="px-3 py-2">
                            <a href="/timereport/enter" class="btn-primary text-sm">{{ __('Entrada') }}</a>
                        </td>
                    @else
                        <td class="px-3 py-2">{{ date('d-M-y', strtotime($lastChecking->starttime)) }}</td>
                        <td class="px-3 py-2">{{ date('H:i', strtotime($lastChecking->starttime)) }}</td>
                    @endif
                    <td class="px-3 py-2"></td>
                    <td class="px-3 py-2"></td>
                    <td class="px-3 py-2">
                        @if ($isChecking)
                            <a href="/timereport/exit" class="btn-primary text-sm">{{ __('Salida') }}</a>
                        @endif
                    </td>
                    <td class="px-3 py-2"></td>
                </tr>
            </tbody>
        </table>
    </div>
@endsection
