@extends('layouts.app')

@section('content')
    <h1>Új modell</h1>

    @include('car_models._form', [
        'carModel' => null,
        'manufacturers' => $manufacturers,
        'selectedManufacturerId' => $selectedManufacturerId,
        'action' => route('car-models.store'),
        'method' => 'POST',
    ])
@endsection
