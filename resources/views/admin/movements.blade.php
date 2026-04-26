@extends('layouts.admin')

@section('title', __('Movimientos') . ' — ' . config('app.name'))

@section('page_header')
    <h1 class="text-2xl font-bold text-slate-900">{{ __('Movimientos de Caja') }}</h1>
@endsection

@section('content')
    @if (Session::get('success'))
        <div class="mb-4 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">{{ Session::get('success') }}</div>
    @endif

    <div class="card-tw overflow-x-auto">
        <table class="min-w-full divide-y divide-slate-200 text-sm">
            <thead class="bg-slate-50">
                <tr>
                    <th class="px-4 py-2 text-left font-semibold">{{ __('Tipo') }}</th>
                    <th class="px-4 py-2 text-left font-semibold">{{ __('Cantidad') }}</th>
                    <th class="px-4 py-2 text-left font-semibold">{{ __('Descripcion') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @foreach ($movements as $movement)
                    <tr>
                        <td class="px-4 py-2">{{ $movement->payment }}</td>
                        <td class="px-4 py-2">{{ $movement->total }}</td>
                        <td class="px-4 py-2">{{ $movement->notes }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <form action="{{ route('addmovement') }}" method="POST" class="mt-6 grid gap-4 border-t border-slate-200 p-4 sm:grid-cols-12 sm:items-end">
            @csrf
            <div class="sm:col-span-3">
                <label class="label-tw">{{ __('Tipo') }}</label>
                <select name="payment" class="input-tw w-full">
                    <option value="cashin">{{ __('Entrada') }}</option>
                    <option value="cashout">{{ __('Salida') }}</option>
                </select>
            </div>
            <div class="sm:col-span-3">
                <label class="label-tw">{{ __('Cantidad') }}</label>
                <input type="text" name="total" class="input-tw w-full" size="3">
            </div>
            <div class="sm:col-span-4">
                <label class="label-tw">{{ __('Descripcion') }}</label>
                <input type="text" name="notes" value="" class="input-tw w-full">
            </div>
            <div class="sm:col-span-2">
                <button type="submit" class="btn-primary w-full">{{ __('Guardar') }}</button>
            </div>
        </form>
    </div>
@endsection
