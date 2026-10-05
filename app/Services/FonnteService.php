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

        $target = $this->normalizeNumber($target);

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

    private function normalizeNumber(string $number): string
    {
        $number = preg_replace('/[^0-9+]/', '', $number);

        if (str_starts_with($number, '+62')) {
            return substr($number, 1);
        }

        if (str_starts_with($number, '0')) {
            return '62' . substr($number, 1);
        }

        return $number;
    }
}