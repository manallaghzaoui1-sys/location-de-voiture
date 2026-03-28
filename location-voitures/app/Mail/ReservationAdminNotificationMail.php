<?php

namespace App\Mail;

use App\Models\Reservation;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ReservationAdminNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Reservation $reservation)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Nouvelle reservation client - ' . $this->reservation->contract_reference,
            replyTo: [
                new Address(
                    $this->reservation->user->email,
                    $this->reservation->user->name
                ),
            ],
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.reservation-admin-notification',
            with: [
                'reservation' => $this->reservation,
            ],
        );
    }
}
