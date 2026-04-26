@extends('layouts.admin')

@section('title', __('Categorias') . ' — ' . config('app.name'))

@section('page_header')
    <h1 class="text-2xl font-bold text-slate-900">{{ __('Categorias (Botones)') }}</h1>
@endsection

@section('page', 'admin-categories-index')

@section('content')
    @if (Session::get('success'))
        <div class="mb-4 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">{{ Session::get('success') }}</div>
    @endif

    <div id="admin-categories-index" class="card-tw overflow-x-auto">
        <table class="min-w-full divide-y divide-slate-200 text-left text-sm">
            <thead class="bg-slate-50">
                <tr>
                    <th class="px-3 py-2 font-semibold">{{ __('Active') }}</th>
                    <th class="px-3 py-2 font-semibold" colspan="4">{{ __('Categoría') }}</th>
                    <th class="px-3 py-2 font-semibold">{{ __('Borrar') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @foreach ($categories as $category)
                    <tr id="{{ $category->id }}">
                        <td class="align-top px-3 py-3">
                            <input type="checkbox" class="category-active-toggle h-4 w-4 rounded border-slate-300 text-brand focus:ring-brand" @if ($category->catshowname) checked @endif>
                        </td>
                        <td class="px-3 py-3" colspan="4">
                            <form action="{{ route('categories.update', $category->id) }}" method="POST" class="grid gap-3 sm:grid-cols-12 sm:items-end">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="id" value="{{ $category->id }}">
                                <div class="sm:col-span-4">
                                    <label class="label-tw">{{ __('Padre') }}</label>
                                    <select name="parentid" class="input-tw w-full">
                                        <option value="">&nbsp;</option>
                                        @foreach ($categories as $subcategory)
                                            <option value="{{ $subcategory->id }}" @selected($subcategory->id == $category->parentid)>{{ $subcategory->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="sm:col-span-4">
                                    <label class="label-tw">{{ __('Nombre') }}</label>
                                    <input type="text" name="name" value="{{ $category->name }}" class="input-tw w-full" required>
                                </div>
                                <div class="sm:col-span-2">
                                    <label class="label-tw">{{ __('Orden') }}</label>
                                    <input type="text" name="catorder" value="{{ $category->catorder }}" class="input-tw w-full">
                                </div>
                                <div class="sm:col-span-2">
                                    <button type="submit" class="btn-tab w-full justify-center">{{ __('Guardar') }}</button>
                                </div>
                            </form>
                        </td>
                        <td class="align-top px-3 py-3">
                            <form action="{{ route('categories.destroy', $category->id) }}" method="POST" onsubmit="return confirm('{{ __('¿Borrar categoría?') }}');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-tab">{{ __('Borrar') }}</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                <tr>
                    <td class="align-top px-3 py-3">
                        <input form="category-new-form" type="checkbox" name="catshowname" checked class="h-4 w-4 rounded border-slate-300 text-brand focus:ring-brand">
                    </td>
                    <td class="px-3 py-3" colspan="4">
                        <form id="category-new-form" action="{{ route('categories.store') }}" method="POST" class="grid gap-3 sm:grid-cols-12 sm:items-end">
                            @csrf
                            <div class="sm:col-span-4">
                                <label class="label-tw">{{ __('Padre') }}</label>
                                <select name="parentid" class="input-tw w-full">
                                    <option value="">&nbsp;</option>
                                    @foreach ($categories as $subcategory)
                                        <option value="{{ $subcategory->id }}">{{ $subcategory->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="sm:col-span-4">
                                <label class="label-tw">{{ __('Nombre') }}</label>
                                <input type="text" name="name" value="" class="input-tw w-full">
                            </div>
                            <div class="sm:col-span-2">
                                <label class="label-tw">{{ __('Orden') }}</label>
                                <input type="text" name="catorder" value="" class="input-tw w-full">
                            </div>
                            <div class="sm:col-span-2">
                                <button type="submit" class="btn-tab w-full justify-center">{{ __('Guardar') }}</button>
                            </div>
                        </form>
                    </td>
                    <td></td>
                </tr>
            </tbody>
        </table>
    </div>
@endsection
