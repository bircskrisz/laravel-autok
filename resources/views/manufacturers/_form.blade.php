{{-- Használat: @include('manufacturers._form', ['manufacturer' => $manufacturer vagy null, 'action' => ..., 'method' => 'POST'|'PUT']) --}}
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
        <label class="form-label">Név *</label>
        <input type="text" name="name" value="{{ old('name', $manufacturer?->name) }}" class="form-control @error('name') is-invalid @enderror">
        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label">Ország</label>
            <input type="text" name="country" value="{{ old('country', $manufacturer?->country) }}" class="form-control @error('country') is-invalid @enderror">
            @error('country') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label">Alapítás éve</label>
            <input type="number" name="founded_year" value="{{ old('founded_year', $manufacturer?->founded_year) }}" class="form-control @error('founded_year') is-invalid @enderror">
            @error('founded_year') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>
    <div class="mb-3">
        <label class="form-label">Weboldal</label>
        <input type="text" name="website" value="{{ old('website', $manufacturer?->website) }}" placeholder="https://..." class="form-control @error('website') is-invalid @enderror">
        @error('website') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="mb-3">
        <label class="form-label">Logó URL</label>
        <input type="text" name="logo_url" value="{{ old('logo_url', $manufacturer?->logo_url) }}" placeholder="https://..." class="form-control @error('logo_url') is-invalid @enderror">
        @error('logo_url') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="mb-3">
        <label class="form-label">Leírás</label>
        <textarea name="description" rows="4" class="form-control @error('description') is-invalid @enderror">{{ old('description', $manufacturer?->description) }}</textarea>
        @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <button type="submit" class="btn btn-primary">Mentés</button>
    <a href="{{ route('manufacturers.index') }}" class="btn btn-secondary">Mégse</a>
</form>
