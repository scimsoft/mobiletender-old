@extends('layouts.auth')

@section('title', __('Servicio no disponible') . ' — ' . config('app.name'))

@section('content')
    @if (session('status'))
        <div class="alert-success">{{ session('status') }}</div>
    @endif
    <div class="space-y-4 text-center">
        <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-red-100 text-red-600" aria-hidden="true">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-8 w-8">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
            </svg>
        </div>
        <h1 class="text-2xl font-bold text-slate-900">{{ __('Un fallo de conexión') }}</h1>
        <p class="text-sm font-semibold uppercase tracking-wide text-red-600">{{ __('Error 503') }}</p>
        @if (isset($exception) && $exception->getMessage())
            <p class="text-sm text-slate-600">{{ $exception->getMessage() }}</p>
        @endif
        <a href="javascript:history.back()" class="btn-primary inline-flex no-underline">{{ __('Volver') }}</a>
    </div>
@endsection
