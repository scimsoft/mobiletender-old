@extends('layouts.reg')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <br>
                    <div class="card-header col-centered"><h1 class="display-3">STATS</h1></div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        <br>

                        <form method="GET" action="/stats" class="mb-3">
                            <div class="form-row align-items-end">
                                <div class="col-sm-6">
                                    <label for="stats-date"><b>Fecha para stats</b></label>
                                    <input
                                        id="stats-date"
                                        type="date"
                                        name="date"
                                        class="form-control"
                                        value="{{ $selectedDate }}"
                                    >
                                </div>
                                @if($selectedCategoryId)
                                    <input type="hidden" name="category" value="{{ $selectedCategoryId }}">
                                @endif
                                <div class="col-sm-3 mt-2 mt-sm-0">
                                    <button type="submit" class="btn btn-primary btn-block">Filtrar</button>
                                </div>
                            </div>
                        </form>

                        <div>&nbsp;</div>
                        <table class="table table-sm">

                            <tr class="bg-light"><td colspan="2" class="text-center"><h4>Caja Actual</h4></td></tr>

                            @foreach($cajaActual as $cajaActualLine)
                                <tr><td>{{$cajaActualLine->payment}}</td><td>@money($cajaActualLine->total)</td></tr>
                            @endforeach
                            <tr><td colspan="2" class="">&nbsp;</td></tr>

                            <tr class="bg-light"><td colspan="2" class="text-center"><h4>Venta del día ({{ $selectedDate }}) por el día</h4></td></tr>

                            @foreach($ventaLinesHoy as $ventaLine)
                                <tr><td>{{$ventaLine->PAYMENT}}</td><td>@money($ventaLine->TOTAL)</td></tr>
                            @endforeach
                                <tr><td><b>TOTAL</b></td><td><b>@money($totalDay)</b></td></tr>
                            <tr><td colspan="2" class="">&nbsp;</td></tr>
                            <tr class="bg-light"><td colspan="2" class="text-center"><h4>Venta del día ({{ $selectedDate }}) por la noche</h4></td></tr>

                            @foreach($ventaLinesHoyNight as $ventaLine)
                                <tr><td>{{$ventaLine->PAYMENT}}</td><td>@money($ventaLine->TOTAL)</td></tr>
                            @endforeach
                            <tr><td><b>TOTAL</b></td><td><b>@money($totalNight)</b></td></tr>
                            <tr><td colspan="2" class="">&nbsp;</td></tr>

                            <tr class="bg-light"><td colspan="2" class="text-center"><h4>Venta por categoria ({{ $selectedDate }})</h4></td></tr>
                            @foreach($categoriesHoy as $categorie)
                                <tr>
                                    <td>
                                        <a href="/stats?date={{ $selectedDate }}&category={{ $categorie->ID }}">
                                            {{$categorie->NAME}}
                                        </a>
                                    </td>
                                    <td>@money($categorie->TOTAL)</td>
                                </tr>

                                @endforeach
                            <tr><td colspan="2" class="">&nbsp;</td></tr>
                            @if($selectedCategory)
                                <tr class="bg-light"><td colspan="2" class="text-center"><h4>Detalle de productos: {{ $selectedCategory->NAME }} ({{ $selectedDate }})</h4></td></tr>
                                @forelse($categoryProductDetails as $productDetail)
                                    <tr>
                                        <td>{{ $productDetail->NAME }} <small class="text-muted">(x{{ number_format($productDetail->UNITS, 2) }})</small></td>
                                        <td>@money($productDetail->TOTAL)</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="2" class="text-center text-muted">No hay productos para esta categoria en la fecha seleccionada.</td></tr>
                                @endforelse
                                <tr><td colspan="2" class="">&nbsp;</td></tr>
                            @endif
                            <tr class="bg-light"> <td colspan="2" class="text-center"><h4>Venta por dia</h4></td></tr>
                            @foreach($ventaPorDias as $ventaPorDia)
                                <tr><td>{{Carbon\Carbon::parse($ventaPorDia->daynumber)->format('l')}}&nbsp;- &nbsp;{{$ventaPorDia->daynumber}}</td><td>@money($ventaPorDia->TOTAL)</td></tr>

                            @endforeach

                                <!--tr>
                                    <td>
                                        <a href="/timereport" class="btn btn-primary"> Marcar Entradad o Salida</a> <br>
                                    </td>

                                    <td>Checkin o Checkout</td>
                                </tr-->

                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')




@stop
