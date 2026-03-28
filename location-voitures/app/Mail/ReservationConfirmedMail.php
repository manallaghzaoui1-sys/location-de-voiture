<?php

namespace App\Mail;

use App\Models\Reservation;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class ReservationConfirmedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Reservation $reservation)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Confirmation de reservation - ' . $this->reservation->contract_reference,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.reservation-confirmation',
            with: [
                'reservation' => $this->reservation,
            ],
        );
    }

    public function attachments(): array
    {
        if (! $this->reservation->contract_pdf_path || ! Storage::disk('local')->exists($this->reservation->contract_pdf_path)) {
            return [];
        }

        return [
            Attachment::fromPath(Storage::disk('local')->path($this->reservation->contract_pdf_path))
                ->as($this->reservation->contract_reference . '.pdf')
                ->withMime('application/pdf'),
        ];
    }
}
