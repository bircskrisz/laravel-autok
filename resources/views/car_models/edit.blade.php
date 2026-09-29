@extends('layouts.app')

@section('content')
    <h1>Modell szerkesztése</h1>

    @include('car_models._form', [
        'carModel' => $car_model,
        'manufacturers' => $manufacturers,
        'selectedManufacturerId' => null,
        'action' => route('car-models.update', $car_model),
        'method' => 'PUT',
    ])
@endsection
