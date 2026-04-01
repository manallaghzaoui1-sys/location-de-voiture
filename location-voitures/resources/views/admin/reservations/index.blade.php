@extends('layouts.admin')

@section('title', 'Réservations')

@section('content')
<div class="admin-panel mb-3">
    <div class="admin-panel-head">
        <h5 class="mb-0">Gestion des réservations</h5>
    </div>

    <form method="GET" class="row g-2 mt-1">
        <div class="col-md-3">
            <input type="text" name="search" class="form-control" placeholder="Référence / client" value="{{ $search ?? '' }}">
        </div>
        <div class="col-md-2">
            <select name="status" class="form-select">
                <option value="">Tous statuts</option>
                @foreach(['en_attente' => 'En attente', 'confirme' => 'Confirmée', 'annule' => 'Annulée', 'termine' => 'Terminée'] as $key => $label)
                    <option value="{{ $key }}" @selected(($status ?? '') === $key)>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <select name="city_id" class="form-select">
                <option value="">Toutes villes</option>
                @foreach($cities as $city)
                    <option value="{{ $city->id }}" @selected(($cityId ?? '') == (string) $city->id)>{{ $city->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <input type="date" name="date_from" class="form-control" value="{{ $dateFrom ?? '' }}" title="Date début >=">
        </div>
        <div class="col-md-2">
            <input type="date" name="date_to" class="form-control" value="{{ $dateTo ?? '' }}" title="Date fin <=">
        </div>
        <div class="col-md-1 d-grid">
            <button type="submit" class="btn btn-outline-primary">OK</button>
        </div>
    </form>
</div>

<div class="admin-panel">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead>
                <tr>
                    <th>Réf</th>
                    <th>Client</th>
                    <th>Voiture</th>
                    <th>Ville</th>
                    <th>Période</th>
                    <th>Total</th>
                    <th>Statut</th>
                    <th>Contrat</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reservations as $reservation)
                    <tr>
                        <td>
                            <strong>{{ $reservation->contract_reference ?? 'N/A' }}</strong><br>
                            <small class="text-muted">#{{ $reservation->id }}</small>
                        </td>
                        <td>
                            <div><strong>{{ $reservation->user->name }}</strong></div>
                            <small>{{ $reservation->user->email }}</small><br>
                            <small>CIN: {{ $reservation->user->cin ?? '-' }}</small><br>
                            <small>Permis: {{ $reservation->user->numero_permis ?? '-' }}</small>
                        </td>
                        <td>{{ $reservation->car->marque }} {{ $reservation->car->modele }}</td>
                        <td>{{ optional($reservation->city)->name ?? '-' }}</td>
                        <td>{{ $reservation->date_debut->format('d/m/Y') }} - {{ $reservation->date_fin->format('d/m/Y') }}</td>
                        <td>
                            <small>Location: {{ number_format($reservation->prix_location, 2) }} DH</small><br>
                            <small>Ville: {{ number_format($reservation->frais_deplacement, 2) }} DH</small><br>
                            <strong>{{ number_format($reservation->prix_total, 2) }} DH</strong>
                        </td>
                        <td>
                            <form action="{{ route('admin.reservation.status', $reservation->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <select name="statut" class="form-select form-select-sm" onchange="this.form.submit()">
                                    <option value="en_attente" @selected($reservation->statut === 'en_attente')>En attente</option>
                                    <option value="confirme" @selected($reservation->statut === 'confirme')>Confirmée</option>
                                    <option value="annule" @selected($reservation->statut === 'annule')>Annulée</option>
                                    <option value="termine" @selected($reservation->statut === 'termine')>Terminée</option>
                                </select>
                            </form>
                        </td>
                        <td>
                            @if($reservation->statut === 'confirme' && $reservation->contract_pdf_path)
                                <a href="{{ \Illuminate\Support\Facades\URL::temporarySignedRoute('admin.reservation.contract.download', now()->addMinutes(20), ['reservation' => $reservation->id]) }}" class="btn btn-sm btn-outline-primary mb-1">PDF</a>
                            @endif
                            @if($reservation->user->cin_document_path)
                                <a href="{{ \Illuminate\Support\Facades\URL::temporarySignedRoute('admin.users.documents.download', now()->addMinutes(20), ['user' => $reservation->user->id, 'type' => 'cin']) }}" class="btn btn-sm btn-outline-secondary mb-1">Doc CIN</a>
                            @endif
                            @if($reservation->user->permis_document_path)
                                <a href="{{ \Illuminate\Support\Facades\URL::temporarySignedRoute('admin.users.documents.download', now()->addMinutes(20), ['user' => $reservation->user->id, 'type' => 'permis']) }}" class="btn btn-sm btn-outline-secondary mb-1">Doc Permis</a>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="text-center text-muted py-4">Aucune réservation trouvée.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="pt-3 d-flex justify-content-center">{{ $reservations->links() }}</div>
</div>
@endsection
