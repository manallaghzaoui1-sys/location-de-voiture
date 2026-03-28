<?php

namespace App\Services;

use App\Mail\ReservationAdminNotificationMail;
use App\Mail\ReservationConfirmedMail;
use App\Models\Reservation;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ReservationNotificationService
{
    public function sendReservationConfirmation(Reservation $reservation): bool
    {
        try {
            Mail::to($reservation->user->email)->send(new ReservationConfirmedMail($reservation));
            $reservation->update(['email_sent_at' => now()]);

            return true;
        } catch (\Throwable $exception) {
            Log::error('Reservation confirmation email failed.', [
                'reservation_id' => $reservation->id,
                'contract_reference' => $reservation->contract_reference,
                'error' => $exception->getMessage(),
            ]);

            return false;
        }
    }

    public function sendReservationAdminNotification(Reservation $reservation): bool
    {
        $adminAddress = (string) config('mail.admin_address');

        if ($adminAddress === '') {
            Log::warning('Reservation admin notification skipped: mail.admin_address is empty.', [
                'reservation_id' => $reservation->id,
                'contract_reference' => $reservation->contract_reference,
            ]);

            return false;
        }

        try {
            Mail::to($adminAddress)->send(new ReservationAdminNotificationMail($reservation));

            return true;
        } catch (\Throwable $exception) {
            Log::error('Reservation admin notification email failed.', [
                'reservation_id' => $reservation->id,
                'contract_reference' => $reservation->contract_reference,
                'admin_email' => $adminAddress,
                'error' => $exception->getMessage(),
            ]);

            return false;
        }
    }
}
