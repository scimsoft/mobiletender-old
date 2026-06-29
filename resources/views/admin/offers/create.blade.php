@extends('layouts.admin')

@section('title', __('Nueva Oferta') . ' — ' . config('app.name'))

@section('page', 'admin-offers-create')

@section('page_header')
    <div>
        <h1 class="text-2xl font-bold text-slate-900">{{ __('Nueva Oferta') }}</h1>
        <p class="mt-1 text-slate-600">{{ __('Crea un paquete de productos a precio fijo.') }}</p>
    </div>
@endsection

@section('content')
    <div class="card-tw mx-auto max-w-4xl">
        <div class="card-tw-body">
            @if ($errors->any())
                <div class="alert-error">
                    <ul class="list-disc space-y-1 pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form action="{{ route('offers.store') }}" method="POST" class="space-y-2">
                @csrf
                @include('admin.offers._form')
            </form>
        </div>
    </div>
@endsection
