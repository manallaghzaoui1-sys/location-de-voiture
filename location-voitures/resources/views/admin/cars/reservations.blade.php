@extends('layouts.app')

@section('title', 'Toutes les réservations')

@section('content')
<div class="container py-5">
    <h1 class="mb-4"><i class="fas fa-calendar-check"></i> Toutes les réservations</h1>
    
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    
    <div class="table-responsive">
        <table class="table table-bordered table-hover">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Client</th>
                    <th>Voiture</th>
                    <th>Date début</th>
                    <th>Date fin</th>
                    <th>Nombre jours</th>
                    <th>Prix total</th>
                    <th>Statut</th>
                    <th>Date réservation</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($reservations as $reservation)
                    <tr>
                        <td>{{ $reservation->id }}</td>
                        <td>{{ $reservation->user->name }}<br><small>{{ $reservation->user->email }}</small></td>
                        <td>{{ $reservation->car->marque }} {{ $reservation->car->modele }}</td>
                        <td>{{ $reservation->date_debut->format('d/m/Y') }}</td>
                        <td>{{ $reservation->date_fin->format('d/m/Y') }}</td>
                        <td>{{ $reservation->nombre_jours }} jours</td>
                        <td><strong>{{ $reservation->prix_total }} DH</strong></td>
                        <td>
                            @if($reservation->statut == 'en_attente')
                                <span class="badge bg-warning">En attente</span>
                            @elseif($reservation->statut == 'confirme')
                                <span class="badge bg-success">Confirmé</span>
                            @else
                                <span class="badge bg-danger">Annulé</span>
                            @endif
                        </td>
                        <td>{{ $reservation->created_at->format('d/m/Y H:i') }}</td>
                        <td>
                            <form action="{{ route('admin.reservation.status', $reservation->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <select name="statut" class="form-select form-select-sm" onchange="this.form.submit()">
                                    <option value="en_attente" {{ $reservation->statut == 'en_attente' ? 'selected' : '' }}>En attente</option>
                                    <option value="confirme" {{ $reservation->statut == 'confirme' ? 'selected' : '' }}>Confirmé</option>
                                    <option value="annule" {{ $reservation->statut == 'annule' ? 'selected' : '' }}>Annulé</option>
                                </select>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    <div class="d-flex justify-content-center">
        {{ $reservations->links() }}
    </div>
</div>
@endsection