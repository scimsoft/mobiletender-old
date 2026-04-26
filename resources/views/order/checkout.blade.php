@extends('layouts.shop')

@section('title', __('Checkout') . ' — ' . config('app.name'))

@section('page', 'checkout')

@section('content')
    @php
        $checkoutPageConfig = [
            'ticketId' => Session::get('ticketID'),
            'newLinesTotal' => round((float) $newLinesPrice * 1.1, 2),
            'paypalPrepay' => (bool) (config('customoptions.eatin_prepay') || config('customoptions.takeaway_prepay') || config('customoptions.delivery_prepay')),
            'paypalClientId' => config('paypal.client_id'),
            'hasTableNumber' => (bool) Session::get('tableNumber'),
        ];
    @endphp
    <div class="card-tw mx-auto max-w-3xl">
        <div class="card-tw-header text-center">
            <b>{{ __('Pedido') }}</b>
        </div>
        <div class="card-tw-body text-center">
                    <table id="products-table" class="table ">
                        <thead>


                        </thead>
                        <tbody>

                        <tr>
                            <td colspan="5">&nbsp;</td>
                        </tr>
                        <tr>
                            <td colspan="">&nbsp;</td>
                            <td colspan="">Base</td>
                            <td colspan="">IVA</td>
                            <td colspan=""></td>
                            <td colspan="">Total</td>
                        </tr>
                        <tr>
                            <td colspan="">TOTAL</td>
                            <td>@money($totalBasketPrice)</td>
                            <td>@money($totalBasketPrice*0.1)</td>

                            <td></td>
                            <td>@money($totalBasketPrice*1.1)</td>
                        </tr>
                        <tr>
                            <td colspan=""><b>A Pedir</b></td>
                            <td>@money($newLinesPrice)</td>
                            <td>@money($newLinesPrice*0.1)</td>

                            <td></td>
                            <td>&nbsp;<b>@money($newLinesPrice*1.1)</b></td>


                        </tr>

                        <tr>
                            <td colspan="5">&nbsp;</td>
                        </tr>
                        {{--
                        Si tiene numero de mesa puede ser:
                        1. de prepago
                        2. a cuenta
                        --}}


                        @if(Session::get('tableNumber'))
                            @if(config('customoptions.clean_table_after_order'))
                            <tr>
                                <td colspan="5">
                                    <button class="btn btn-tab btn-block" id="pagarEfectivo">
                                      Pagar en efectivo
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="5">
                                    <button class="btn btn-tab btn-block" id="pagarTarjeta">
                                        Pagar con tarjeta
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="5">
                                    <button class="btn btn-tab btn-block" id="pagarOnline">
                                        Pagar online
                                    </button>
                                </td>
                            </tr>
                        @else
                            <tr>
                                <td colspan="5">
                                    <button class="btn btn-tab btn-block" id="apuntarEnLaMesa">
                                        Pedir
                                    </button>
                                </td>
                            </tr>
                            @endif

                        @else
                            {{--
                        Si NO tiene numero de mesa puede ser:
                        1. Para tomar en el restaurante
                        2. para llevar
                        3. O para domicilars
                        --}}

                            <tr>
                                <td colspan="5"><button class="btn btn-mobilepos btn-block" id="eatin">Para tomarlo aqui</button></td>
                            </tr>

                            @if(config('customoptions.takeaway'))
                                <tr>
                                    <td colspan="5"><button class="btn btn-mobilepos btn-block" id="takeaway">Para recoger</button></td>
                                </tr>
                            @endif
                            @if(config('customoptions.delivery'))
                                <tr>
                                    <td colspan="5"><a href="" class="btn btn-mobilepos btn-block">Para entregar </a></td>
                                </tr>
                            @endif
                        @endif

                        </tbody>
                    </table>


                    <div id="paypal-button-container"></div>
                    <div id="scan-qr-instructions" style="display:none">
                        <tr>
                            <td colspan="5">
                                Para añadir el pedido a su mesa, <br>
                                escanea con la camera el codgo QR <br>
                                que tiene en su mesa.<br>
                                <img src="/img/qr-example.png" class="flex-column">
                            </td>
                        </tr>
                    </div>
                        <div id="div-takeaway" style="display:none">
                            <tr>
                                <td colspan="5">
                                    Horario de recogido:<br>
                                    12:00 hasta las 20:00


                                </td>
                            </tr>
                        </div>

        </div>
    </div>
@endsection

@push('scripts')
<script type="application/json" id="checkout-page-config">
@json($checkoutPageConfig)
</script>
@endpush
