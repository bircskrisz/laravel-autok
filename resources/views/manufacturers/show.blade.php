@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>{{ $manufacturer->name }}</h1>
        <div>
            <a href="{{ route('manufacturers.edit', $manufacturer) }}" class="btn btn-warning">Szerkesztés</a>
            <a href="{{ route('manufacturers.index') }}" class="btn btn-secondary">Vissza a listához</a>
        </div>
    </div>

    <div class="row mb-4">
        @if($manufacturer->logo_url)
            <div class="col-md-3 mb-3">
                <img src="{{ $manufacturer->logo_url }}" alt="{{ $manufacturer->name }} logó" class="img-fluid img-thumbnail">
            </div>
        @endif
        <div class="col">
            <dl class="row mb-0">
                <dt class="col-sm-3">Ország</dt>
                <dd class="col-sm-9">{{ $manufacturer->country ?: '-' }}</dd>
                <dt class="col-sm-3">Alapítás éve</dt>
                <dd class="col-sm-9">{{ $manufacturer->founded_year ?: '-' }}</dd>
                <dt class="col-sm-3">Weboldal</dt>
                <dd class="col-sm-9">
                    @if($manufacturer->website)
                        <a href="{{ $manufacturer->website }}" target="_blank" rel="noopener">{{ $manufacturer->website }}</a>
                    @else
                        -
                    @endif
                </dd>
                <dt class="col-sm-3">Leírás</dt>
                <dd class="col-sm-9">{{ $manufacturer->description ?: '-' }}</dd>
            </dl>
        </div>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-2">
        <h2 class="h4 mb-0">Modellek ({{ $carModels->count() }})</h2>
        <div>
            <a href="{{ route('car-models.index', ['manufacturer_id' => $manufacturer->id]) }}" class="btn btn-outline-primary btn-sm">Megnyitás szűrt listaként</a>
            <a href="{{ route('car-models.create', ['manufacturer_id' => $manufacturer->id]) }}" class="btn btn-primary btn-sm">Új modell</a>
        </div>
    </div>

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Név</th>
                <th>Típuskód</th>
                <th>Évjárat</th>
                <th>Ár</th>
            </tr>
        </thead>
        <tbody>
            @forelse($carModels as $carModel)
                <tr>
                    <td><a href="{{ route('car-models.show', $carModel) }}">{{ $carModel->name }}</a></td>
                    <td>{{ $carModel->model_code }}</td>
                    <td>{{ $carModel->model_year }}</td>
                    <td>{{ number_format($carModel->price, 0, ',', ' ') }} Ft</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center">Ehhez a gyártóhoz még nincs modell.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection
