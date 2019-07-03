@extends('layouts.app')

@section('content')
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript" src="{{asset('js/tchoblond59/sstemp/sstemp.js')}}"></script>
    <div class="container">
        <div class="row">

            <div class="col-md-12">
                <h1>Gestion Sensor </h1>
                <hr>
            </div>
            <div class="col-md-12">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>
            <div class="col-md-12">
                <div class="d-flex justify-content-around flex-wrap">
                    <div>
                        <form action="{{ url("update/sstemp/$id") }}" method="POST">
                            {{ csrf_field() }}
                            <div class="form-group">
                                <label for="temp_lim">Entrez le seuil de temperature : </label>
                                <input class="form-control" type="text" name="temp_lim" id="temp_lim"
                                       placeholder="{{$last_temp}}">
                            </div>
                            <div class="form-group">
                                <label for="mail">Adresse mail: </label>
                                <input class="form-control" type="email" name="mail" id="mail"
                                       placeholder="exemple@exemple.com">
                            </div>
                            <input class="btn btn-secondary float-right" type="submit" value="Envoyer !">
                        </form>
                    </div>
                    <div>
                        <label>Mail :</label>
                        @foreach($mails as $mail)
                            <form action="{{ url("delete/sstemp/mail/$mail->id") }}" method="POST">
                                {{ csrf_field() }}
                                <div class="form-group">
                                    <div style="margin-top: 15px"><input class="btn btn-danger" type="submit"
                                                                         value="-"> {{$mail->email}}</div>
                                </div>
                            </form>
                        @endforeach
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div id="sstemp_chart" style="width: 100%; height: 500px">

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        var sstemp_data = {!! $data_chart !!}
    </script>






@endsection