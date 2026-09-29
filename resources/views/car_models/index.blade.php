@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>Modellek</h1>
        <a href="{{ route('car-models.create', $manufacturerId ? ['manufacturer_id' => $manufacturerId] : []) }}" class="btn btn-primary">Új modell</a>
    </div>

    <form method="GET" action="{{ route('car-models.index') }}" class="row g-2 mb-3">
        <div class="col-auto">
            <input type="text" name="search" value="{{ $search }}" class="form-control" placeholder="Keresés (név, típuskód, kivitel, üzemanyag)">
        </div>
        <div class="col-auto">
            <select name="manufacturer_id" class="form-select">
                <option value="">Összes gyártó</option>
                @foreach($manufacturers as $manufacturer)
                    <option value="{{ $manufacturer->id }}" @selected($manufacturerId == $manufacturer->id)>{{ $manufacturer->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-auto">
            <button type="submit" class="btn btn-secondary">Szűrés</button>
        </div>
        @if($search !== '' || $manufacturerId)
            <div class="col-auto">
                <a href="{{ route('car-models.index') }}" class="btn btn-outline-secondary">Szűrő törlése</a>
            </div>
        @endif
    </form>

    <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle">
            <thead>
                <tr>
                    <th>Név</th>
                    <th>Típuskód</th>
                    <th>Gyártó</th>
                    <th>Évjárat</th>
                    <th>Kivitel</th>
                    <th>Üzemanyag</th>
                    <th>LE</th>
                    <th>Ár</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($carModels as $carModel)
                    <tr>
                        <td><a href="{{ route('car-models.show', $carModel) }}">{{ $carModel->name }}</a></td>
                        <td>{{ $carModel->model_code }}</td>
                        <td><a href="{{ route('manufacturers.show', $carModel->manufacturer) }}">{{ $carModel->manufacturer->name }}</a></td>
                        <td>{{ $carModel->model_year }}</td>
                        <td>{{ $carModel->body_type }}</td>
                        <td>{{ $carModel->fuel_type }}</td>
                        <td>{{ $carModel->power_hp }}</td>
                        <td class="text-nowrap">{{ number_format($carModel->price, 0, ',', ' ') }} Ft</td>
                        <td class="text-end text-nowrap">
                            <a href="{{ route('car-models.show', $carModel) }}" class="btn btn-sm btn-info">Megtekintés</a>
                            <a href="{{ route('car-models.edit', $carModel) }}" class="btn btn-sm btn-warning">Szerkesztés</a>
                            <form action="{{ route('car-models.destroy', $carModel) }}" method="POST" class="d-inline" onsubmit="return confirm('Biztosan törlöd?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Törlés</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center">Nincs találat.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $carModels->links() }}
@endsection
