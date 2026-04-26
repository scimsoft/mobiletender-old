@extends('layouts.auth')

@section('title', __('Dashboard') . ' — ' . config('app.name'))

@section('content')
    @if (session('status'))
        <div class="mb-4 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">{{ session('status') }}</div>
    @endif
    <p class="mb-6 text-center text-slate-600">{{ __('You are logged in!') }}</p>
    <div class="flex flex-col gap-3 sm:flex-row sm:justify-center">
        <a href="{{ route('order') }}" class="btn-primary text-center">{{ __('Pedidos') }}</a>
        <a href="{{ route('admin') }}" class="btn-secondary text-center">{{ __('Area Privada') }}</a>
    </div>
@endsection
