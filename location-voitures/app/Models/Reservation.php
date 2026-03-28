<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reservation extends Model
{
    protected $fillable = [
        'user_id', 
        'car_id', 
        'city_id',
        'date_debut', 
        'date_fin', 
        'prix_total', 
        'statut',
        'contract_reference',
        'prix_location',
        'frais_deplacement',
        'contract_pdf_path',
        'email_sent_at',
    ];

    protected $casts = [
        'date_debut' => 'date',
        'date_fin' => 'date',
        'prix_total' => 'float',
        'prix_location' => 'float',
        'frais_deplacement' => 'float',
        'email_sent_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function car(): BelongsTo
    {
        return $this->belongsTo(Car::class);
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    public function getNombreJoursAttribute()
    {
        return $this->date_debut->diffInDays($this->date_fin);
    }
}
