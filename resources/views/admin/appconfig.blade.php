@extends('layouts.admin')

@section('title', __('Demo config') . ' — ' . config('app.name'))

@section('page_header')
    <h1 class="text-2xl font-bold text-slate-900">{{ __('Demo config') }}</h1>
    <p class="mt-1 text-slate-600">{{ __('Gestiona su local') }}</p>
@endsection

@section('content')
    <div class="card-tw max-w-xl">
        <div class="card-tw-body space-y-6">
            <form action="/appconfig" method="POST">
                @csrf
                <label class="flex cursor-pointer items-center gap-3">
                    <input type="checkbox" name="eatin" class="h-4 w-4 rounded border-slate-300 text-brand focus:ring-brand" @checked(config('customoptions.eatin') == 1)>
                    <span>{{ __('Servicio de mesas') }}</span>
                </label>
                <label class="flex cursor-pointer items-center gap-3">
                    <input type="checkbox" name="takeaway" class="h-4 w-4 rounded border-slate-300 text-brand focus:ring-brand" @checked(config('customoptions.takeaway') == 1)>
                    <span>{{ __('Para llevar') }}</span>
                </label>
                <label class="flex cursor-pointer items-center gap-3">
                    <input type="checkbox" name="eatinprepay" class="h-4 w-4 rounded border-slate-300 text-brand focus:ring-brand" @checked(config('customoptions.eatin_prepay') == 1)>
                    <span>{{ __('Prepago para la mesa') }}</span>
                </label>
                <label class="flex cursor-pointer items-center gap-3">
                    <input type="checkbox" name="cleantableafterorder" class="h-4 w-4 rounded border-slate-300 text-brand focus:ring-brand" @checked(config('customoptions.clean_table_after_order') == 1)>
                    <span>{{ __('NO dejar cuenta abierta') }}</span>
                </label>
                <button type="submit" class="btn-primary">{{ __('Guardar') }}</button>
            </form>
        </div>
    </div>
@endsection
