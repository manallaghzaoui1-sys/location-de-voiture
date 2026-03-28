<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use RuntimeException;

class ImageStorageService
{
    public function storeCarImage(UploadedFile $file): string
    {
        $extension = strtolower((string) $file->guessExtension());
        $allowed = ['jpg', 'jpeg', 'png', 'webp'];

        if (! in_array($extension, $allowed, true)) {
            throw new RuntimeException('Unsupported image type.');
        }

        $filename = 'car_' . Str::uuid()->toString() . '.' . $extension;
        Storage::disk('car_images')->putFileAs('', $file, $filename);

        return $filename;
    }

    public function storeIdentityDocument(UploadedFile $file, int $userId, string $type): string
    {
        return $file->store("private/identity/{$userId}/{$type}", 'local');
    }

    public function deletePublicFile(?string $relativePath): void
    {
        if (! $relativePath) {
            return;
        }

        if (Storage::disk('car_images')->exists($relativePath)) {
            Storage::disk('car_images')->delete($relativePath);
        }

        // Backward compatibility: old images may still be stored on the legacy public disk.
        if (Storage::disk('public')->exists($relativePath)) {
            Storage::disk('public')->delete($relativePath);
        }
    }

    public function deletePrivateFile(?string $relativePath): void
    {
        if ($relativePath && Storage::disk('local')->exists($relativePath)) {
            Storage::disk('local')->delete($relativePath);
        }
    }
}
