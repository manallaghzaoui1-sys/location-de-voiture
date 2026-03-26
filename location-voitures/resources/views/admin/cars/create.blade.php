@extends('layouts.app')

@section('title', 'Ajouter un véhicule')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0"><i class="fas fa-plus"></i> Ajouter un véhicule</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.cars.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="marque" class="form-label">Marque *</label>
                                <input type="text" class="form-control @error('marque') is-invalid @enderror" 
                                       id="marque" name="marque" value="{{ old('marque') }}" required>
                                @error('marque')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="modele" class="form-label">Modèle *</label>
                                <input type="text" class="form-control @error('modele') is-invalid @enderror" 
                                       id="modele" name="modele" value="{{ old('modele') }}" required>
                                @error('modele')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="annee" class="form-label">Année *</label>
                                <input type="number" class="form-control @error('annee') is-invalid @enderror" 
                                       id="annee" name="annee" value="{{ old('annee') }}" required>
                                @error('annee')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="carburant" class="form-label">Carburant *</label>
                                <select class="form-control @error('carburant') is-invalid @enderror" id="carburant" name="carburant" required>
                                    <option value="">Sélectionner</option>
                                    <option value="Essence">Essence</option>
                                    <option value="Diesel">Diesel</option>
                                    <option value="Hybride">Hybride</option>
                                    <option value="Electrique">Électrique</option>
                                </select>
                                @error('carburant')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="prix_par_jour" class="form-label">Prix par jour (DH) *</label>
                                <input type="number" class="form-control @error('prix_par_jour') is-invalid @enderror" 
                                       id="prix_par_jour" name="prix_par_jour" value="{{ old('prix_par_jour') }}" required>
                                @error('prix_par_jour')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="image" class="form-label">Image</label>
                                <input type="file" class="form-control @error('image') is-invalid @enderror" 
                                       id="image" name="image" accept="image/*">
                                @error('image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="3">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="disponible" name="disponible" value="1" checked>
                            <label class="form-check-label" for="disponible">Disponible immédiatement</label>
                        </div>
                        
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-save"></i> Enregistrer
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
