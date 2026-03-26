@extends('layouts.app')

@section('title', 'Gestion des véhicules')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="fas fa-car"></i> Gestion des véhicules</h1>
        <a href="{{ route('admin.cars.create') }}" class="btn btn-success">
            <i class="fas fa-plus"></i> Ajouter un véhicule
        </a>
    </div>
    
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
                    <th>Image</th>
                    <th>Marque</th>
                    <th>Modèle</th>
                    <th>Année</th>
                    <th>Carburant</th>
                    <th>Prix/jour</th>
                    <th>Disponible</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($cars as $car)
                    <tr>
                        <td>{{ $car->id }}</td>
                        <td>
                            @if($car->image)
                                <img src="{{ asset('storage/' . $car->image) }}" width="50" height="50" style="object-fit: cover;">
                            @else
                                <img src="https://via.placeholder.com/50x50" width="50">
                            @endif
                        </td>
                        <td>{{ $car->marque }}</td>
                        <td>{{ $car->modele }}</td>
                        <td>{{ $car->annee }}</td>
                        <td>{{ $car->carburant }}</td>
                        <td>{{ $car->prix_par_jour }} DH</td>
                        <td>
                            @if($car->disponible)
                                <span class="badge bg-success">Oui</span>
                            @else
                                <span class="badge bg-danger">Non</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.cars.edit', $car->id) }}" class="btn btn-sm btn-warning">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.cars.destroy', $car->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Supprimer cette voiture?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    <div class="d-flex justify-content-center">
        {{ $cars->links() }}
    </div>
</div>
@endsection