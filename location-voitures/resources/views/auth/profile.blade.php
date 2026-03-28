@extends('layouts.client')

@section('title', 'Mon profil')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="auth-card p-4 p-md-5">
                <h4 class="mb-3"><i class="fas fa-id-card"></i> Mes informations</h4>
                <p class="text-muted mb-4">Complétez vos informations pour accélérer vos prochaines réservations.</p>

                <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Téléphone</label>
                            <input type="text" name="telephone" class="form-control @error('telephone') is-invalid @enderror" value="{{ old('telephone', auth()->user()->telephone) }}">
                            @error('telephone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Adresse</label>
                            <input type="text" name="adresse" class="form-control @error('adresse') is-invalid @enderror" value="{{ old('adresse', auth()->user()->adresse) }}">
                            @error('adresse')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">CIN</label>
                            <input type="text" name="cin" class="form-control @error('cin') is-invalid @enderror" value="{{ old('cin', auth()->user()->cin) }}" required>
                            @error('cin')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Numéro de permis</label>
                            <input type="text" name="numero_permis" class="form-control @error('numero_permis') is-invalid @enderror" value="{{ old('numero_permis', auth()->user()->numero_permis) }}" required>
                            @error('numero_permis')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Document CIN</label>
                            <input type="file" name="cin_document" class="form-control @error('cin_document') is-invalid @enderror" accept=".jpg,.jpeg,.png,.pdf">
                            @error('cin_document')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Document permis</label>
                            <input type="file" name="permis_document" class="form-control @error('permis_document') is-invalid @enderror" accept=".jpg,.jpeg,.png,.pdf">
                            @error('permis_document')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 mt-4">Mettre à jour</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
