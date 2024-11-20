@extends('layouts.master')

@section('content')
    <div class="row m-4">

        <div class="col-sm-4">

            <img
                src="https://upload.wikimedia.org/wikipedia/commons/thumb/9/9f/Curriculum-vitae-warning-icon.svg/256px-Curriculum-vitae-warning-icon.svg.png" />

        </div>
        <div class="col-sm-8">

            <h3><strong>ID Usuario: </strong>{{ $curriculo['user_id'] }}</h3>
            <h4><strong>Video Currículo: </strong>
                <a href="{{ $curriculo['video_curriculum'] }}">
                    {{ $curriculo['video_curriculum'] }}
                </a>
            </h4>
            <h4><strong>Skills: </strong>{{ $curriculo['texto_curriculum'] }}</h4>

            <a class="btn btn-warning"
                href="{{ action([App\Http\Controllers\CurriculoController::class, 'getEdit'], ['id' => $id]) }}">
                <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                Editar curriculo
            </a>
            <a class="btn btn-outline-info"
                href="{{ action([App\Http\Controllers\CurriculoController::class, 'getIndex']) }}">
                Volver al listado
            </a>


        </div>
    </div>
@endsection
