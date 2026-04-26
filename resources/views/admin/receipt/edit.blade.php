@extends('layouts.admin')

@section('title', __('Ticket') . ' — ' . config('app.name'))

@section('page_header')
    <h1 class="text-2xl font-bold text-slate-900">{{ __('Ticket NR:') }} {{ $ticketNumber }}</h1>
    <a href="/receipts" class="btn-secondary mt-2 inline-flex">{{ __('Volver') }}</a>
@endsection

@section('content')
    @foreach ($receiptlines as $receiptline)
        @if (count($receiptlines) > 1)
            <form id="del-line-{{ $receiptline->id }}-{{ $receiptline->line }}" method="POST" action="{{ url('/deletereceiptline/'.$receiptline->id.'/'.$receiptline->line) }}" class="hidden">@csrf</form>
        @endif
    @endforeach

    <div class="card-tw overflow-x-auto">
        <table id="products-table" class="min-w-full divide-y divide-slate-200 text-sm">
            <thead class="bg-slate-50">
                <tr>
                    <th class="px-3 py-2 text-left">Pay</th>
                    <th class="px-3 py-2 text-left">{{ __('Product') }}</th>
                    <th class="px-3 py-2 text-left">{{ __('Price') }}</th>
                    <th class="px-3 py-2 text-left">{{ __('Action') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @foreach ($receiptlines as $receiptline)
                    <tr class="productrow">
                        <td class="px-3 py-2">
                            <img src="/dbimage/{{ $receiptline->productid }}.png" alt="" class="h-8 w-8 object-contain">
                        </td>
                        <td class="px-3 py-2">{{ $receiptline->productname }}</td>
                        <td class="amount px-3 py-2 font-semibold">@money($receiptline->price * 1.1)</td>
                        <td class="px-3 py-2">
                            @if (count($receiptlines) > 1)
                                <button type="submit" form="del-line-{{ $receiptline->id }}-{{ $receiptline->line }}" class="btn-tab text-xs">{{ __('Borrar') }}</button>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
