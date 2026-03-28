@extends('layouts.admin')

@section('title', 'Véhicules')

@section('content')
<div class="admin-panel mb-3">
    <div class="admin-panel-head">
        <h5 class="mb-0">Gestion des véhicules</h5>
        <a href="{{ route('admin.cars.create') }}" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Ajouter</a>
    </div>

    <form method="GET" class="row g-2 mt-1">
        <div class="col-md-4">
            <input type="text" name="search" class="form-control" placeholder="Marque / modèle" value="{{ $search ?? '' }}">
        </div>
        <div class="col-md-3">
            <select name="fuel" class="form-select">
                <option value="">Tous carburants</option>
                @foreach($fuelOptions as $option)
                    <option value="{{ $option }}" @selected(($fuel ?? '') === $option)>{{ $option }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <select name="availability" class="form-select">
                <option value="">Disponibilité</option>
                <option value="1" @selected(($availability ?? '') === '1')>Disponible</option>
                <option value="0" @selected(($availability ?? '') === '0')>Indisponible</option>
            </select>
        </div>
        <div class="col-md-2 d-grid">
            <button type="submit" class="btn btn-outline-primary">Filtrer</button>
        </div>
    </form>
</div>

<div class="admin-panel">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Image</th>
                    <th>Véhicule</th>
                    <th>Carburant</th>
                    <th>Prix/jour</th>
                    <th>Disponibilité</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($cars as $car)
                    <tr>
                        <td>{{ $car->id }}</td>
                        <td><img src="{{ $car->image_url }}" width="62" height="62" class="rounded" style="object-fit:cover" alt="car"></td>
                        <td>
                            <strong>{{ $car->marque }} {{ $car->modele }}</strong><br>
                            <small class="text-muted">Année {{ $car->annee }}</small>
                        </td>
                        <td>{{ $car->carburant }}</td>
                        <td>{{ number_format($car->prix_par_jour, 2) }} DH</td>
                        <td>
                            @if($car->disponible)
                                <span class="badge bg-success">Disponible</span>
                            @else
                                <span class="badge bg-danger">Indisponible</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <a href="{{ route('admin.cars.edit', $car->id) }}" class="btn btn-sm btn-outline-warning"><i class="fas fa-pen"></i></a>
                            <form action="{{ route('admin.cars.destroy', $car->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Supprimer ce véhicule ')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center text-muted py-4">Aucun véhicule trouvé.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="pt-3 d-flex justify-content-center">{{ $cars->links() }}</div>
</div>
@endsection
