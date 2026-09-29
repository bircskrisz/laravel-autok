@extends('layouts.app')

@section('content')
    <h1>Gyártó szerkesztése</h1>

    @include('manufacturers._form', [
        'manufacturer' => $manufacturer,
        'action' => route('manufacturers.update', $manufacturer),
        'method' => 'PUT',
    ])
@endsection
