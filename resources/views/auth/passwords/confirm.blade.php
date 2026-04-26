@extends('layouts.auth')

@section('title', __('Confirm Password') . ' — ' . config('app.name'))

@section('content')
    <h2 class="mb-4 text-center text-lg font-semibold text-slate-900">{{ __('Confirm Password') }}</h2>
    <p class="mb-6 text-center text-sm text-slate-600">{{ __('Please confirm your password before continuing.') }}</p>
    <form method="POST" action="{{ route('password.confirm') }}" class="space-y-4">
        @csrf
        <div>
            <label for="password" class="label-tw">{{ __('Password') }}</label>
            <input id="password" type="password" class="input-tw mt-1 w-full @error('password') border-red-500 @enderror" name="password" required autocomplete="current-password">
            @error('password')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
        </div>
        <button type="submit" class="btn-primary w-full">{{ __('Confirm Password') }}</button>
        @if (Route::has('password.request'))
            <p class="text-center text-sm">
                <a class="text-brand-dark hover:underline" href="{{ route('password.request') }}">{{ __('Forgot Your Password?') }}</a>
            </p>
        @endif
    </form>
@endsection
