@extends('layouts.client')

@section('title', 'Inscription')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="auth-card p-4 p-md-5">
                <h4 class="mb-3"><i class="fas fa-user-plus"></i> Inscription client</h4>
                <p class="text-muted mb-4">Créez votre compte pour réserver rapidement.</p>

                <form method="POST" action="{{ route('register.post') }}" enctype="multipart/form-data">
                    @csrf

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="name" class="form-label">Nom complet</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required>
                            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label for="telephone" class="form-label">Téléphone</label>
                            <input type="text" class="form-control @error('telephone') is-invalid @enderror" id="telephone" name="telephone" value="{{ old('telephone') }}">
                            @error('telephone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label for="cin" class="form-label">CIN</label>
                            <input type="text" class="form-control @error('cin') is-invalid @enderror" id="cin" name="cin" value="{{ old('cin') }}" required>
                            @error('cin')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label for="numero_permis" class="form-label">Numéro de permis</label>
                            <input type="text" class="form-control @error('numero_permis') is-invalid @enderror" id="numero_permis" name="numero_permis" value="{{ old('numero_permis') }}" required>
                            @error('numero_permis')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label for="cin_document" class="form-label">Document CIN (optionnel)</label>
                            <input type="file" class="form-control @error('cin_document') is-invalid @enderror" id="cin_document" name="cin_document" accept=".jpg,.jpeg,.png,.pdf">
                            @error('cin_document')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label for="permis_document" class="form-label">Document permis (optionnel)</label>
                            <input type="file" class="form-control @error('permis_document') is-invalid @enderror" id="permis_document" name="permis_document" accept=".jpg,.jpeg,.png,.pdf">
                            @error('permis_document')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label for="password" class="form-label">Mot de passe</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required>
                            @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label for="password_confirmation" class="form-label">Confirmer le mot de passe</label>
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 mt-4">Créer mon compte</button>
                </form>

                <div class="text-center mt-3">
                    <small>Déjà inscrit <a href="{{ route('login') }}">Connectez-vous</a></small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
