{{-- Extendemos del layout principal --}}
@extends('layouts.app')

{{-- Le asignamos en sufijo al titlo de la pagina --}}
@section('title_postfix', 'Home')

{{-- En esta seccion va algun estilo CSS que queramos agregar --}}
@section('css')

    {{-- <link rel="stylesheet" href="cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css">
    <link rel="stylesheet" href="cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css"> --}}

@endsection

{{-- Agregamos un header arriba del contente --}}
@section('content_header')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                <link class="fas fa-fw fa-home" rel="icon">
                <a class="text-uppercase" href="{{ route('home') }}">Codeway</a>
            </h3>
        </div>
    </div>
@endsection

{{-- En esta section va nuestro content --}}
@section('content')

    <div>
        @livewire('user.events-component')

        @livewire('profile.miprojects-component')
    </div>

@stop



{{-- En esta section va algun js que queramos agregar --}}
@section('js')

    {{-- <script src="cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script> --}}

@endsection
