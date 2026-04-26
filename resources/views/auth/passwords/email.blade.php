@extends('layouts.auth')

@section('title', __('Reset Password') . ' — ' . config('app.name'))

@section('content')
    <h2 class="mb-6 text-center text-xl font-semibold text-slate-900">{{ __('Reset Password') }}</h2>
    @if (session('status'))
        <div class="mb-4 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">{{ session('status') }}</div>
    @endif
    <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
        @csrf
        <div>
            <label for="email" class="label-tw">{{ __('E-Mail Address') }}</label>
            <input id="email" type="email" class="input-tw mt-1 w-full @error('email') border-red-500 @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
            @error('email')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
        </div>
        <button type="submit" class="btn-primary w-full">{{ __('Send Password Reset Link') }}</button>
    </form>
@endsection
