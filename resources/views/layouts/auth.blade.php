<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name'))</title>
    @vite(['resources/css/main.css', 'resources/js/main.js'])
</head>
<body class="flex min-h-screen items-center justify-center bg-gradient-to-br from-brand-light/40 via-white to-brand-accent/10 px-4 py-12" data-page="auth">
<div class="w-full max-w-md">
    <div class="mb-8 text-center">
        <a href="{{ url('/') }}" class="text-2xl font-bold text-slate-900">{{ config('app.name') }}</a>
    </div>
    <div class="card-tw">
        <div class="card-tw-body">
            @include('partials.shared.flash')
            @yield('content')
        </div>
    </div>
</div>
@stack('scripts')
</body>
</html>
