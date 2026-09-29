@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>{{ $car_model->manufacturer->name }} {{ $car_model->name }}</h1>
        <div>
            <a href="{{ route('car-models.edit', $car_model) }}" class="btn btn-warning">Szerkesztés</a>
            <a href="{{ route('car-models.index') }}" class="btn btn-secondary">Vissza a listához</a>
        </div>
    </div>

    <div class="row">
        @if($car_model->image_url)
            <div class="col-md-4 mb-3">
                <img src="{{ $car_model->image_url }}" alt="{{ $car_model->name }}" class="img-fluid img-thumbnail">
            </div>
        @endif
        <div class="col">
            <dl class="row">
                <dt class="col-sm-3">Gyártó</dt>
                <dd class="col-sm-9"><a href="{{ route('manufacturers.show', $car_model->manufacturer) }}">{{ $car_model->manufacturer->name }}</a></dd>
                <dt class="col-sm-3">Típuskód</dt>
                <dd class="col-sm-9">{{ $car_model->model_code }}</dd>
                <dt class="col-sm-3">Évjárat</dt>
                <dd class="col-sm-9">{{ $car_model->model_year }}</dd>
                <dt class="col-sm-3">Ár</dt>
                <dd class="col-sm-9">{{ number_format($car_model->price, 0, ',', ' ') }} Ft</dd>
                <dt class="col-sm-3">Kivitel</dt>
                <dd class="col-sm-9">{{ $car_model->body_type ?: '-' }}</dd>
                <dt class="col-sm-3">Üzemanyag</dt>
                <dd class="col-sm-9">{{ $car_model->fuel_type ?: '-' }}</dd>
                <dt class="col-sm-3">Teljesítmény</dt>
                <dd class="col-sm-9">{{ $car_model->power_hp ? $car_model->power_hp.' LE' : '-' }}</dd>
                <dt class="col-sm-3">Leírás</dt>
                <dd class="col-sm-9">{{ $car_model->description ?: '-' }}</dd>
            </dl>
        </div>
    </div>
@endsection
