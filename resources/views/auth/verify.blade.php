@extends('layouts.auth')

@section('title', __('Verify Your Email Address') . ' — ' . config('app.name'))

@section('content')
    <h2 class="mb-4 text-center text-lg font-semibold text-slate-900">{{ __('Verify Your Email Address') }}</h2>
    @if (session('resent'))
        <div class="mb-4 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">{{ __('A fresh verification link has been sent to your email address.') }}</div>
    @endif
    <p class="text-center text-sm text-slate-600">
        {{ __('Before proceeding, please check your email for a verification link.') }}
        {{ __('If you did not receive the email') }},
    </p>
    <form class="mt-2 text-center" method="POST" action="{{ route('verification.resend') }}">
        @csrf
        <button type="submit" class="text-sm font-medium text-brand-dark hover:underline">{{ __('click here to request another') }}</button>
    </form>
@endsection
