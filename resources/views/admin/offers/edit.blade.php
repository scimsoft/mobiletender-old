@extends('layouts.reg')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header"><h3>Editar Oferta</h3></div>
            <div class="card-body">
                @if ($message = Session::get('success'))
                    <div class="alert alert-success"><p>{{ $message }}</p></div>
                @endif
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form action="{{ route('offers.update', $offer->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    @include('admin.offers._form')
                </form>
            </div>
        </div>
    </div>
@endsection
