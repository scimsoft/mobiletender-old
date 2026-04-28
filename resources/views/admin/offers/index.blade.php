@extends('layouts.reg')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-lg-12 margin-tb">
                <div class="pull-left"><h2>Ofertas</h2></div>
            </div>
        </div>

        @if ($message = Session::get('success'))
            <div class="alert alert-success"><p>{{ $message }}</p></div>
        @endif
        @if ($message = Session::get('error'))
            <div class="alert alert-danger"><p>{{ $message }}</p></div>
        @endif

        <div class="float-right mb-2">
            <a class="btn btn-tab" href="{{ route('offers.create') }}">Nueva oferta</a>
        </div>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Activa</th>
                    <th>Orden</th>
                    <th>Nombre</th>
                    <th>Productos</th>
                    <th>Precio Final</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
            @forelse($offers as $offer)
                <tr>
                    <td>{{ $offer->active ? 'Si' : 'No' }}</td>
                    <td>{{ $offer->sort_order }}</td>
                    <td>{{ $offer->name }}</td>
                    <td>
                        @foreach($offer->offerProducts as $op)
                            <span class="badge badge-info">
                                {{ $op->quantity }}x
                                {{ optional($op->product)->name ?? $op->product_id }}
                            </span>
                        @endforeach
                    </td>
                    <td>@money($offer->final_price)</td>
                    <td>
                        <a href="{{ route('offers.edit', $offer->id) }}" class="btn btn-tab">Editar</a>
                        <form action="{{ route('offers.destroy', $offer->id) }}" method="POST" style="display:inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-tab"
                                    onclick="return confirm('¿Borrar la oferta?');">Borrar</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">No hay ofertas. Crea una nueva.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
@endsection
