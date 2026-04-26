@extends('layouts.auth')

@section('title', __('Reset Password') . ' — ' . config('app.name'))

@section('content')
    <h2 class="mb-6 text-center text-xl font-semibold text-slate-900">{{ __('Reset Password') }}</h2>
    <form method="POST" action="{{ route('password.update') }}" class="space-y-4">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">
        <div>
            <label for="email" class="label-tw">{{ __('E-Mail Address') }}</label>
            <input id="email" type="email" class="input-tw mt-1 w-full @error('email') border-red-500 @enderror" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus>
            @error('email')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
        </div>
        <div>
            <label for="password" class="label-tw">{{ __('Password') }}</label>
            <input id="password" type="password" class="input-tw mt-1 w-full @error('password') border-red-500 @enderror" name="password" required autocomplete="new-password">
            @error('password')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
        </div>
        <div>
            <label for="password-confirm" class="label-tw">{{ __('Confirm Password') }}</label>
            <input id="password-confirm" type="password" class="input-tw mt-1 w-full" name="password_confirmation" required autocomplete="new-password">
        </div>
        <button type="submit" class="btn-primary w-full">{{ __('Reset Password') }}</button>
    </form>
@endsection
