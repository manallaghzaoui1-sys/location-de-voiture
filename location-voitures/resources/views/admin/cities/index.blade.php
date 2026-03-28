@extends('layouts.admin')

@section('title', 'Villes')

@section('content')
<div class="admin-panel mb-3">
    <div class="admin-panel-head">
        <h5 class="mb-0">Ajouter une ville</h5>
    </div>
    <form method="POST" action="{{ route('admin.cities.store') }}" class="row g-2 align-items-end">
        @csrf
        <div class="col-md-4">
            <label class="form-label">Nom</label>
            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" required>
        </div>
        <div class="col-md-3">
            <label class="form-label">Frais de déplacement (DH)</label>
            <input type="number" step="0.01" min="0" name="travel_fee" class="form-control @error('travel_fee') is-invalid @enderror" required>
        </div>
        <div class="col-md-3">
            <div class="form-check mt-4">
                <input type="checkbox" class="form-check-input" id="is_active_new" name="is_active" value="1" checked>
                <label for="is_active_new" class="form-check-label">Active</label>
            </div>
        </div>
        <div class="col-md-2 d-grid">
            <button class="btn btn-primary">Ajouter</button>
        </div>
    </form>
</div>

<div class="admin-panel">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead>
                <tr>
                    <th>Ville</th>
                    <th>Frais déplacement</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($cities as $city)
                    <tr>
                        <td>
                            <form method="POST" action="{{ route('admin.cities.update', $city) }}" class="row g-2">
                                @csrf
                                @method('PUT')
                                <div class="col-md-12">
                                    <input type="text" name="name" class="form-control" value="{{ $city->name }}" required>
                                </div>
                        </td>
                        <td>
                            <input type="number" step="0.01" min="0" name="travel_fee" class="form-control" value="{{ $city->travel_fee }}" required>
                        </td>
                        <td>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" name="is_active" value="1" {{ $city->is_active ? 'checked' : '' }}>
                                <label class="form-check-label">{{ $city->is_active ? 'Active' : 'Inactive' }}</label>
                            </div>
                        </td>
                        <td class="d-flex gap-1">
                                <button class="btn btn-sm btn-outline-warning">Modifier</button>
                            </form>
                            <form method="POST" action="{{ route('admin.cities.destroy', $city) }}" onsubmit="return confirm('Supprimer cette ville ?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="text-center text-muted py-4">Aucune ville.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="pt-3 d-flex justify-content-center">{{ $cities->links() }}</div>
</div>
@endsection
