@extends('layouts.app')

@section('content')
    <h1>Új gyártó</h1>

    @include('manufacturers._form', [
        'manufacturer' => null,
        'action' => route('manufacturers.store'),
        'method' => 'POST',
    ])
@endsection
