@extends('layouts.admin')

@section('title', 'Modifier véhicule')

@section('content')
<div class="container-fluid">
    <div class="admin-panel">
        <div class="admin-panel-head">
            <h5 class="mb-0"><i class="fas fa-edit"></i> Modifier le véhicule</h5>
        </div>

        <form action="{{ route('admin.cars.update', $car->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Marque *</label>
                    <input type="text" class="form-control @error('marque') is-invalid @enderror" name="marque" value="{{ old('marque', $car->marque) }}" required>
                    @error('marque')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Modèle *</label>
                    <input type="text" class="form-control @error('modele') is-invalid @enderror" name="modele" value="{{ old('modele', $car->modele) }}" required>
                    @error('modele')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Année *</label>
                    <input type="number" class="form-control @error('annee') is-invalid @enderror" name="annee" value="{{ old('annee', $car->annee) }}" required>
                    @error('annee')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Carburant *</label>
                    <select class="form-control @error('carburant') is-invalid @enderror" name="carburant" required>
                        @foreach(['Essence', 'Diesel', 'Hybride', 'Electrique'] as $fuel)
                            <option value="{{ $fuel }}" @selected(old('carburant', $car->carburant) === $fuel)>{{ $fuel }}</option>
                        @endforeach
                    </select>
                    @error('carburant')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Prix par jour (DH) *</label>
                    <input type="number" step="0.01" class="form-control @error('prix_par_jour') is-invalid @enderror" name="prix_par_jour" value="{{ old('prix_par_jour', $car->prix_par_jour) }}" required>
                    @error('prix_par_jour')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Nouvelle image</label>
                    <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image" accept="image/*">
                    <small class="text-muted">Laisser vide pour garder l'image actuelle.</small><br>
                    <small class="text-muted">Stockage: <code>public/images/images_voiture</code></small>
                    @error('image')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-12">
                    <img id="image-preview" src="{{ $car->image_url }}" class="img-thumbnail" style="max-height:150px" alt="preview">
                </div>
                <div class="col-12">
                    <label class="form-label">Description</label>
                    <textarea class="form-control @error('description') is-invalid @enderror" name="description" rows="3">{{ old('description', $car->description) }}</textarea>
                    @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-12 form-check ms-1">
                    <input type="checkbox" class="form-check-input" id="disponible" name="disponible" value="1" {{ old('disponible', $car->disponible) ? 'checked' : '' }}>
                    <label class="form-check-label" for="disponible">Disponible</label>
                </div>
            </div>

            <button type="submit" class="btn btn-primary w-100 mt-3">Mettre à jour</button>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.getElementById('image').addEventListener('change', function (event) {
        const [file] = event.target.files;
        if (!file) return;
        document.getElementById('image-preview').src = URL.createObjectURL(file);
    });
</script>
@endpush
