<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

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

    public function isDisponible($dateDebut, $dateFin): bool
    {
        if (! $this->disponible) {
            return false;
        }

        return ! $this->reservations()
            ->whereIn('statut', ['en_attente', 'confirme'])
            ->where(function($query) use ($dateDebut, $dateFin) {
                $query->whereBetween('date_debut', [$dateDebut, $dateFin])
                      ->orWhereBetween('date_fin', [$dateDebut, $dateFin])
                      ->orWhere(function($q) use ($dateDebut, $dateFin) {
                          $q->where('date_debut', '<=', $dateDebut)
                            ->where('date_fin', '>=', $dateFin);
                      });
            })
            ->exists();
    }

    public function getImageUrlAttribute(): string
    {
        if (! $this->image) {
            return asset('images/placeholders/car-default.svg');
        }

        if (Storage::disk('car_images')->exists($this->image)) {
            return asset('images/images_voiture/' . $this->image);
        }

        // Backward compatibility for legacy records using storage/app/public.
        if (Storage::disk('public')->exists($this->image)) {
            return asset('storage/' . $this->image);
        }

        // Backward compatibility for legacy absolute-ish values already starting with images/.
        if (str_starts_with($this->image, 'images/') && file_exists(public_path($this->image))) {
            return asset($this->image);
        }

        return asset('images/placeholders/car-default.svg');
    }
}
