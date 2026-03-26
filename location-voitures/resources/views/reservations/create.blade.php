@extends('layouts.app')

@section('title', 'Réserver ' . $car->marque . ' ' . $car->modele)

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0"><i class="fas fa-calendar-check"></i> Formulaire de réservation</h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('reservation.store') }}">
                        @csrf
                        <input type="hidden" name="car_id" value="{{ $car->id }}">
                        
                        <div class="mb-3">
                            <label class="form-label">Voiture</label>
                            <input type="text" class="form-control" value="{{ $car->marque }} {{ $car->modele }}" disabled>
                        </div>
                        
                        <div class="mb-3">
                            <label for="date_debut" class="form-label">Date de début</label>
                            <input type="date" class="form-control @error('date_debut') is-invalid @enderror" 
                                   id="date_debut" name="date_debut" min="{{ date('Y-m-d') }}" required>
                            @error('date_debut')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="date_fin" class="form-label">Date de fin</label>
                            <input type="date" class="form-control @error('date_fin') is-invalid @enderror" 
                                   id="date_fin" name="date_fin" required>
                            @error('date_fin')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="alert alert-info" id="prix-info">
                            <strong>Prix par jour:</strong> {{ $car->prix_par_jour }} DH
                            <br>
                            <strong>Total estimé:</strong> <span id="prix-total">0</span> DH
                        </div>
                        
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-check"></i> Confirmer la réservation
                        </button>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header bg-info text-white">
                    <h4 class="mb-0"><i class="fas fa-info-circle"></i> Informations sur la voiture</h4>
                </div>
                <div class="card-body">
                    @if($car->image)
                        <img src="{{ asset('storage/' . $car->image) }}" class="img-fluid rounded mb-3" alt="{{ $car->marque }}">
                    @else
                        <img src="https://via.placeholder.com/400x300?text={{ $car->marque }}" class="img-fluid rounded mb-3">
                    @endif
                    <h5>{{ $car->marque }} {{ $car->modele }}</h5>
                    <p><i class="fas fa-calendar-alt"></i> Année: {{ $car->annee }}</p>
                    <p><i class="fas fa-gas-pump"></i> Carburant: {{ $car->carburant }}</p>
                    <p><i class="fas fa-tachometer-alt"></i> Prix: {{ $car->prix_par_jour }} DH/jour</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const prixParJour = {{ $car->prix_par_jour }};
    
    function calculerPrix() {
        const dateDebut = document.getElementById('date_debut').value;
        const dateFin = document.getElementById('date_fin').value;
        
        if (dateDebut && dateFin) {
            const debut = new Date(dateDebut);
            const fin = new Date(dateFin);
            const diffTime = Math.abs(fin - debut);
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
            
            if (diffDays > 0) {
                const total = diffDays * prixParJour;
                document.getElementById('prix-total').innerHTML = total + ' DH';
            } else {
                document.getElementById('prix-total').innerHTML = '0 DH';
            }
        }
    }
    
    document.getElementById('date_debut').addEventListener('change', calculerPrix);
    document.getElementById('date_fin').addEventListener('change', calculerPrix);
</script>
@endsection