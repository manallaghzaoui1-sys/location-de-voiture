<?php

namespace App\Services;

use App\Models\Reservation;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class ContractPdfService
{
    public function generateAndStore(Reservation $reservation): string
    {
        $pdf = Pdf::loadView('pdf.contract', [
            'reservation' => $reservation,
            'generatedAt' => now(),
        ]);

        $relativePath = 'private/contracts/' . $reservation->contract_reference . '.pdf';
        Storage::disk('local')->put($relativePath, $pdf->output());

        return $relativePath;
    }

    public function downloadResponse(Reservation $reservation)
    {
        if (! $reservation->contract_pdf_path || ! Storage::disk('local')->exists($reservation->contract_pdf_path)) {
            $relativePath = $this->generateAndStore($reservation);
            $reservation->update(['contract_pdf_path' => $relativePath]);
        }

        return response()->download(
            Storage::disk('local')->path($reservation->contract_pdf_path),
            $reservation->contract_reference . '.pdf',
            ['Content-Type' => 'application/pdf']
        );
    }
}

