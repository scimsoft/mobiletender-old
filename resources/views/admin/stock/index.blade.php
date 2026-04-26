@extends('layouts.admin')

@section('title', __('Stock') . ' — ' . config('app.name'))

@section('page_header')
    <h1 class="text-2xl font-bold text-slate-900">{{ __('Stock') }}</h1>
@endsection

@section('page', 'admin-stock-index')

@section('content')
    @if (Session::get('success'))
        <div class="mb-4 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">{{ Session::get('success') }}</div>
    @endif

    <div id="admin-stock-index" class="card-tw">
        <div class="card-tw-body">
            <div class="mb-4 flex flex-wrap justify-center gap-2">
                @for ($i = 0; $i < min(6, count($categories)); $i++)
                    <a href="/stockindex/{{ $categories[$i]->id }}" class="btn-secondary text-sm">{{ $categories[$i]->name }}</a>
                @endfor
                @if (count($categories) > 6)
                    <div class="relative inline-block text-left" x-data="{ open: false }">
                        <button type="button" @click="open = !open" class="btn-secondary text-sm">{{ __('Otros') }}</button>
                        <div x-show="open" @click.outside="open = false" class="absolute left-0 z-20 mt-1 max-h-64 min-w-[10rem] overflow-auto rounded-lg border border-slate-200 bg-white py-1 shadow-lg" style="display:none;">
                            @for ($i = 6; $i < count($categories); $i++)
                                <a href="/stockindex/{{ $categories[$i]->id }}" class="block px-3 py-2 text-sm text-slate-700 hover:bg-slate-50">{{ $categories[$i]->name }}</a>
                            @endfor
                        </div>
                    </div>
                @endif
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 text-sm">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-3 py-2 text-left font-semibold">{{ __('Product') }}</th>
                            <th class="px-3 py-2 text-left font-semibold">{{ __('Count') }}</th>
                            <th class="px-3 py-2 text-left font-semibold">{{ __('Add') }}</th>
                            <th class="px-3 py-2 text-left font-semibold">{{ __('Acción') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($stocks as $stock)
                            <tr id="{{ $stock->id }}">
                                <td class="px-3 py-2">{{ $stock->name }}</td>
                                <td class="px-3 py-2">
                                    <input type="text" size="3" name="currentunits" value="{{ $stock->units }}" class="w-20 border-0 bg-transparent" readonly>
                                </td>
                                <td class="px-3 py-2">
                                    <input type="text" size="3" name="newunits" class="input-tw w-24">
                                </td>
                                <td class="px-3 py-2">
                                    <button type="button" class="btn-primary text-sm" name="addbutton">{{ __('Add') }}</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
