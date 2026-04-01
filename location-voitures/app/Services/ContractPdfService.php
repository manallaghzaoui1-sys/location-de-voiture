<?php

namespace App\Services;

use App\Models\Reservation;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Storage;

class ContractPdfService
{
    public function __construct(
        private readonly ContractFieldConfigService $fieldConfigService,
    ) {
    }

    public function generateAndStore(Reservation $reservation): string
    {
        $pdf = Pdf::loadView('pdf.contract', [
            'reservation' => $reservation,
            'generatedAt' => now(),
            'contractLinesBySection' => $this->fieldConfigService->buildLinesBySection($reservation),
        ]);

        $relativePath = 'contracts/' . $reservation->contract_reference . '.pdf';
        Storage::disk('local')->put($relativePath, $pdf->output());

        return $relativePath;
    }

    public function downloadResponse(Reservation $reservation)
    {
        if (! $reservation->contract_pdf_path || ! Storage::disk('local')->exists($reservation->contract_pdf_path)) {
            throw (new ModelNotFoundException())->setModel(Reservation::class, [$reservation->id]);
        }

        return response()->download(
            Storage::disk('local')->path($reservation->contract_pdf_path),
            $reservation->contract_reference . '.pdf',
            ['Content-Type' => 'application/pdf']
        );
    }
}
