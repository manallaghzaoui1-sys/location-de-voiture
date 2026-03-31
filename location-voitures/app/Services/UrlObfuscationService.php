<?php

namespace App\Services;

class UrlObfuscationService
{
    public function encodeCarId(int $carId): string
    {
        $base = strtolower(base_convert((string) $carId, 10, 36));
        $signature = $this->signature('car', $base);

        return $base . '-' . $signature;
    }

    public function decodeCarToken(string $token): ?int
    {
        $parts = explode('-', strtolower(trim($token)), 2);
        if (count($parts) !== 2) {
            return null;
        }

        [$base, $signature] = $parts;
        if ($base === '' || $signature === '' || ! ctype_alnum($base) || ! ctype_xdigit($signature)) {
            return null;
        }

        $expected = $this->signature('car', $base);
        if (! hash_equals($expected, $signature)) {
            return null;
        }

        $decoded = base_convert($base, 36, 10);
        if (! ctype_digit((string) $decoded)) {
            return null;
        }

        $id = (int) $decoded;

        return $id > 0 ? $id : null;
    }

    private function signature(string $scope, string $value): string
    {
        $appKey = (string) config('app.key', '');

        return substr(hash_hmac('sha256', $scope . '|' . $value, $appKey), 0, 12);
    }
}
