@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="card">
                <div class="card-header text-center"><h4>Ofertas</h4></div>
                <div class="card-body text-center">
                    @if (session('status'))
                        <div class="alert alert-success">{{ session('status') }}</div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    @if($offers->isEmpty())
                        <p class="text-muted">No hay ofertas disponibles ahora mismo.</p>
                        <a href="/order" class="btn btn-tab">Volver al menú</a>
                    @else
                        <table id="offers-table" class="table middleTable">
                            <tbody>
                            @foreach($offers as $offer)
                                <tr class="productrow">
                                    <td class="align-middle text-left">
                                        <h5>{{ $offer->name }}</h5>
                                        <small class="text-muted">
                                            @foreach($offer->offerProducts as $op)
                                                {{ $op->quantity }}x
                                                {{ optional($op->product)->name ?? '?' }}@if(!$loop->last), @endif
                                            @endforeach
                                        </small>
                                    </td>
                                    <td class="nowrapcol align-middle">
                                        <b>@money($offer->final_price)</b>
                                    </td>
                                    <td class="align-middle">
                                        <a href="{{ route('order.addoffer', $offer->id) }}"
                                           class="btn btn-tab btn-add">
                                            {{ __('Añadir') }}&nbsp;<img src="/img/cart.svg" width="16">
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <a href="/order" class="btn btn-tab m-1">Volver al menú</a>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
