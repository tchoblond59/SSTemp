@extends('beautymail::templates.widgets')

@section('content')

    @include('beautymail::templates.widgets.articleStart')

    <h4 class="secondary"><strong>Alerte température serveur</strong></h4>
    <p>Sonde à {{$temperature}}°C le {{$time}} </p>

    @include('beautymail::templates.widgets.articleEnd')

@stop