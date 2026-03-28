<?php

namespace App\Policies;

use App\Models\Reservation;
use App\Models\User;

class ReservationPolicy
{
    public function view(User $user, Reservation $reservation): bool
    {
        return $user->isAdmin() || $reservation->user_id === $user->id;
    }

    public function downloadContract(User $user, Reservation $reservation): bool
    {
        return $this->view($user, $reservation);
    }

    public function updateStatus(User $user): bool
    {
        return $user->isAdmin();
    }
}

