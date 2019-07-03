@extends('layouts.app')

@section('content')
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript" src="{{asset('js/tchoblond59/sstemp/sstemp.js')}}"></script>
    <div class="container-fluid">
        <div class="row">

            <div class="col-md-12">
                <h1>Gestion Sensor </h1>
                <hr>
                <div class="row">
                    <div class="col-sm">
                        <form action="{{ url("update/sstemp/$id") }}" method="POST">
                            {{ csrf_field() }}
                            <div class="form-group">
                                <div>
                                    <label for="nom">Entrez le seuil de temperature : </label>
                                </div>
                                <input type="text" name="temp_lim" id="temp_lim" placeholder="{{$last_temp}}">

                                <div>
                                    <label for="nom">Adresse mail: </label>
                                </div>
                                <input type="email" name="mail" id="mail" placeholder="exemple@exemple.com">

                                <div style="padding-top: 15px">
                                    <input type="submit" value="Envoyer !">
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="col-sm">
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