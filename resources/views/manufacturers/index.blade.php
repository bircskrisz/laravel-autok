@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>Gyártók</h1>
        <a href="{{ route('manufacturers.create') }}" class="btn btn-primary">Új gyártó</a>
    </div>

    <form method="GET" action="{{ route('manufacturers.index') }}" class="row g-2 mb-3">
        <div class="col-auto">
            <input type="text" name="search" value="{{ $search }}" class="form-control" placeholder="Keresés (név, ország)">
        </div>
        <div class="col-auto">
            <button type="submit" class="btn btn-secondary">Keresés</button>
        </div>
        @if($search !== '')
            <div class="col-auto">
                <a href="{{ route('manufacturers.index') }}" class="btn btn-outline-secondary">Szűrő törlése</a>
            </div>
        @endif
    </form>

    <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle">
            <thead>
                <tr>
                    <th>Név</th>
                    <th>Ország</th>
                    <th>Alapítás éve</th>
                    <th>Modellek</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($manufacturers as $manufacturer)
                    <tr>
                        <td><a href="{{ route('manufacturers.show', $manufacturer) }}">{{ $manufacturer->name }}</a></td>
                        <td>{{ $manufacturer->country }}</td>
                        <td>{{ $manufacturer->founded_year }}</td>
                        <td>
                            <a href="{{ route('car-models.index', ['manufacturer_id' => $manufacturer->id]) }}" class="badge text-bg-primary text-decoration-none">
                                {{ $manufacturer->car_models_count }} modell
                            </a>
                        </td>
                        <td class="text-end text-nowrap">
                            <a href="{{ route('manufacturers.show', $manufacturer) }}" class="btn btn-sm btn-info">Megtekintés</a>
                            <a href="{{ route('manufacturers.edit', $manufacturer) }}" class="btn btn-sm btn-warning">Szerkesztés</a>
                            <form action="{{ route('manufacturers.destroy', $manufacturer) }}" method="POST" class="d-inline" onsubmit="return confirm('Biztosan törlöd? A gyártó összes modellje is törlődik!');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Törlés</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center">Nincs találat.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $manufacturers->links() }}
@endsection
