<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Car extends Model
{
    protected $fillable = [
        'marque', 
        'modele', 
        'annee', 
        'carburant', 
        'prix_par_jour', 
        'image', 
        'disponible', 
        'description'
    ];

    protected $casts = [
        'disponible' => 'boolean',
    ];

    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }

    public function isDisponible($dateDebut, $dateFin)
    {
        return !$this->reservations()
            ->where('statut', 'confirme')
            ->where(function($query) use ($dateDebut, $dateFin) {
                $query->whereBetween('date_debut', [$dateDebut, $dateFin])
                      ->orWhereBetween('date_fin', [$dateDebut, $dateFin])
                      ->orWhere(function($q) use ($dateDebut, $dateFin) {
                          $q->where('date_debut', '<=', $dateDebut)
                            ->where('date_fin', '>=', $dateFin);
                      });
            })->exists();
    }
}