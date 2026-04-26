@extends('layouts.admin')

@section('title', __('Producto Nuevo') . ' — ' . config('app.name'))

@section('page_header')
    <h1 class="text-2xl font-bold text-slate-900">{{ __('Producto Nuevo') }}</h1>
@endsection

@section('content')
    <div class="card-tw max-w-2xl">
        <div class="card-tw-header">{{ __('Product') }}</div>
        <div class="card-tw-body">
            <form action="{{ route('products.store') }}" method="POST" class="space-y-6">
                @csrf
                <div>
                    <label for="name" class="label-tw">{{ __('Nombre') }}</label>
                    <input name="name" id="name" class="input-tw" type="text" required>
                </div>
                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label for="category" class="label-tw">{{ __('Categoria') }}</label>
                        <select name="category" id="category" class="input-tw w-full">
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="pricesell" class="label-tw">{{ __('Precio Venta') }}</label>
                        <input name="pricesell" id="pricesell" class="input-tw w-full" type="text" value="">
                        <input name="taxcat" type="hidden" value="001">
                    </div>
                </div>
                <input name="reference" type="hidden" value="{{ random_int(100000, 9999999) }}">
                <input name="code" type="hidden" value="{{ random_int(100000, 9999999) }}">
                <input name="pricebuy" type="hidden" value="1">
                <button type="submit" class="btn-primary w-full sm:w-auto">{{ __('Guardar') }}</button>
            </form>
        </div>
    </div>
@endsection
