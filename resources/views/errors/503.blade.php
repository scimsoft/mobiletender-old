@extends('layouts.reg')

@section('content')
    <div class="container">
        <div class="row justify-content-center">

            <div class="card">


                <div class="card-body text-center">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    <h2>&nbsp;</h2>
                        <h2>Un fallo de conexion, error 503</h2>
                        <h4>{{ $exception->getMessage() }}</h4>
                    <a href="javascript:history.back()" class="btn btn-primary"> Volver </a>





                </div>
            </div>

        </div>
    </div>



@endsection
