@extends('layouts.admin')

@section('title', 'Ajouter un véhicule')

@section('content')
<div class="container-fluid">
    <div class="admin-panel">
        <div class="admin-panel-head">
            <h5 class="mb-0"><i class="fas fa-plus"></i> Ajouter un véhicule</h5>
        </div>

        <form action="{{ route('admin.cars.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Marque *</label>
                    <input type="text" class="form-control @error('marque') is-invalid @enderror" name="marque" value="{{ old('marque') }}" required>
                    @error('marque')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Modèle *</label>
                    <input type="text" class="form-control @error('modele') is-invalid @enderror" name="modele" value="{{ old('modele') }}" required>
                    @error('modele')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Année *</label>
                    <input type="number" class="form-control @error('annee') is-invalid @enderror" name="annee" value="{{ old('annee') }}" required>
                    @error('annee')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Carburant *</label>
                    <select class="form-control @error('carburant') is-invalid @enderror" name="carburant" required>
                        <option value="">Sélectionner</option>
                        <option value="Essence" @selected(old('carburant') === 'Essence')>Essence</option>
                        <option value="Diesel" @selected(old('carburant') === 'Diesel')>Diesel</option>
                        <option value="Hybride" @selected(old('carburant') === 'Hybride')>Hybride</option>
                        <option value="Electrique" @selected(old('carburant') === 'Electrique')>Électrique</option>
                    </select>
                    @error('carburant')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Prix par jour (DH) *</label>
                    <input type="number" step="0.01" class="form-control @error('prix_par_jour') is-invalid @enderror" name="prix_par_jour" value="{{ old('prix_par_jour') }}" required>
                    @error('prix_par_jour')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Image principale</label>
                    <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image" accept="image/*">
                    <small class="text-muted">Stockage: <code>public/images/images_voiture</code></small>
                    @error('image')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-12">
                    <img id="image-preview" src="{{ asset('images/placeholders/car-default.svg') }}" class="img-thumbnail" style="max-height:150px" alt="preview">
                </div>
                <div class="col-12">
                    <label class="form-label">Description</label>
                    <textarea class="form-control @error('description') is-invalid @enderror" name="description" rows="3">{{ old('description') }}</textarea>
                    @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-12 form-check ms-1">
                    <input type="checkbox" class="form-check-input" id="disponible" name="disponible" value="1" checked>
                    <label class="form-check-label" for="disponible">Disponible immédiatement</label>
                </div>
            </div>

            <button type="submit" class="btn btn-primary w-100 mt-3">Enregistrer</button>
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
