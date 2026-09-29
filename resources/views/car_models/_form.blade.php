{{-- Használat: @include('car_models._form', ['carModel' => $car_model vagy null, 'manufacturers' => ..., 'selectedManufacturerId' => ..., 'action' => ..., 'method' => 'POST'|'PUT']) --}}
@if($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form method="POST" action="{{ $action }}">
    @csrf
    @if($method !== 'POST')
        @method($method)
    @endif

    <div class="mb-3">
        <label class="form-label">Gyártó *</label>
        <select name="manufacturer_id" class="form-select @error('manufacturer_id') is-invalid @enderror">
            <option value="">-- Válassz gyártót --</option>
            @foreach($manufacturers as $manufacturer)
                <option value="{{ $manufacturer->id }}" @selected(old('manufacturer_id', $carModel?->manufacturer_id ?? $selectedManufacturerId) == $manufacturer->id)>{{ $manufacturer->name }}</option>
            @endforeach
        </select>
        @error('manufacturer_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="row">
        <div class="col-md-8 mb-3">
            <label class="form-label">Név *</label>
            <input type="text" name="name" value="{{ old('name', $carModel?->name) }}" class="form-control @error('name') is-invalid @enderror">
            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="col-md-4 mb-3">
            <label class="form-label">Típuskód * (egyedi)</label>
            <input type="text" name="model_code" value="{{ old('model_code', $carModel?->model_code) }}" class="form-control @error('model_code') is-invalid @enderror">
            @error('model_code') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>
    <div class="row">
        <div class="col-md-4 mb-3">
            <label class="form-label">Évjárat *</label>
            <input type="number" name="model_year" value="{{ old('model_year', $carModel?->model_year) }}" class="form-control @error('model_year') is-invalid @enderror">
            @error('model_year') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="col-md-4 mb-3">
            <label class="form-label">Ár (Ft) *</label>
            <input type="number" step="0.01" name="price" value="{{ old('price', $carModel?->price) }}" class="form-control @error('price') is-invalid @enderror">
            @error('price') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="col-md-4 mb-3">
            <label class="form-label">Teljesítmény (LE)</label>
            <input type="number" name="power_hp" value="{{ old('power_hp', $carModel?->power_hp) }}" class="form-control @error('power_hp') is-invalid @enderror">
            @error('power_hp') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label">Kivitel</label>
            <select name="body_type" class="form-select @error('body_type') is-invalid @enderror">
                <option value="">-- nincs megadva --</option>
                @foreach(\App\Models\CarModel::BODY_TYPES as $type)
                    <option value="{{ $type }}" @selected(old('body_type', $carModel?->body_type) === $type)>{{ $type }}</option>
                @endforeach
            </select>
            @error('body_type') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label">Üzemanyag</label>
            <select name="fuel_type" class="form-select @error('fuel_type') is-invalid @enderror">
                <option value="">-- nincs megadva --</option>
                @foreach(\App\Models\CarModel::FUEL_TYPES as $type)
                    <option value="{{ $type }}" @selected(old('fuel_type', $carModel?->fuel_type) === $type)>{{ $type }}</option>
                @endforeach
            </select>
            @error('fuel_type') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>
    <div class="mb-3">
        <label class="form-label">Kép URL</label>
        <input type="text" name="image_url" value="{{ old('image_url', $carModel?->image_url) }}" placeholder="https://..." class="form-control @error('image_url') is-invalid @enderror">
        @error('image_url') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="mb-3">
        <label class="form-label">Leírás</label>
        <textarea name="description" rows="4" class="form-control @error('description') is-invalid @enderror">{{ old('description', $carModel?->description) }}</textarea>
        @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <button type="submit" class="btn btn-primary">Mentés</button>
    <a href="{{ route('car-models.index') }}" class="btn btn-secondary">Mégse</a>
</form>
