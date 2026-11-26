<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class FonnteService
{
    public function send(
        string $target,
        string $message
    ): array {
        $token = config('services.fonnte.token');

        if (!$token) {
            return [
                'status' => false,
                'response' => [
                    'reason' => 'Fonnte token belum diatur.'
                ],
            ];
        }

        $target = self::normalizePhone($target) ?? $target;

        try {
            $response = Http::timeout(20)
                ->asMultipart()
                ->withHeaders([
                    'Authorization' => $token,
                ])
                ->post(
                    config('services.fonnte.url'),
                    [
                        'target' => $target,
                        'message' => $message,
                        'countryCode' => '62',
                    ]
                );

            $json = $response->json();

            return [
                'status' => $response->successful()
                    && ($json['status'] ?? false) === true,
                'response' => $json,
            ];

        } catch (\Throwable $e) {

            return [
                'status' => false,
                'response' => [
                    'reason' => $e->getMessage(),
                ],
            ];
        }
    }

    // 0812..., +62812..., 812... -> 62812...
    public static function normalizePhone(?string $number): ?string
    {
        if ($number === null) {
            return null;
        }

        $number = preg_replace('/[^0-9+]/', '', $number);

        if ($number === '') {
            return null;
        }

        if (str_starts_with($number, '+62')) {
            return substr($number, 1);
        }

        if (str_starts_with($number, '0')) {
            return '62' . substr($number, 1);
        }

        if (str_starts_with($number, '8')) {
            return '62' . $number;
        }

        return $number;
    }

    // Nomor seluler Indonesia: 628 + 8 sampai 12 digit
    public static function isValidPhone(?string $number): bool
    {
        $number = self::normalizePhone($number);

        return $number !== null
            && preg_match('/^628[0-9]{8,12}$/', $number) === 1;
    }
}