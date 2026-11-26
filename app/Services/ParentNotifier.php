<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\Student;

class ParentNotifier
{
    public function __construct(private FonnteService $fonnte)
    {
    }

    /**
     * Kirim WhatsApp ke semua orang tua/wali siswa dan catat hasilnya.
     * Method ini TIDAK melempar error agar data utama tidak terganggu.
     *
     * @return array{sent:int, failed:int, skipped:int}
     */
    public function notify(Student $student, string $type, string $message): array
    {
        $summary = ['sent' => 0, 'failed' => 0, 'skipped' => 0];

        try {
            $sentTo = [];

            foreach ($student->parents()->get() as $parent) {

                if (!FonnteService::isValidPhone($parent->whatsapp)) {
                    Notification::create([
                        'user_id' => $parent->id,
                        'student_id' => $student->id,
                        'type' => $type,
                        'target' => $parent->whatsapp,
                        'message' => $message,
                        'status' => 'failed',
                        'response' => 'Nomor WhatsApp kosong atau tidak valid.',
                    ]);

                    $summary['skipped']++;
                    continue;
                }

                $target = FonnteService::normalizePhone($parent->whatsapp);

                // Ayah dan ibu memakai nomor yang sama: kirim satu kali saja
                if (in_array($target, $sentTo, true)) {
                    continue;
                }

                $sentTo[] = $target;

                try {
                    $result = $this->fonnte->send($target, $message);
                } catch (\Throwable $e) {
                    $result = [
                        'status' => false,
                        'response' => ['reason' => $e->getMessage()],
                    ];
                }

                Notification::create([
                    'user_id' => $parent->id,
                    'student_id' => $student->id,
                    'type' => $type,
                    'target' => $target,
                    'message' => $message,
                    'status' => $result['status'] ? 'sent' : 'failed',
                    'response' => json_encode($result['response']),
                    'sent_at' => $result['status'] ? now() : null,
                ]);

                $summary[$result['status'] ? 'sent' : 'failed']++;
            }
        } catch (\Throwable $e) {
            report($e);
        }

        return $summary;
    }

    public static function describe(array $summary): string
    {
        if ($summary['sent'] + $summary['failed'] + $summary['skipped'] === 0) {
            return 'Tidak ada orang tua yang terhubung dengan siswa ini.';
        }

        $text = "WhatsApp: {$summary['sent']} terkirim, {$summary['failed']} gagal";

        if ($summary['skipped'] > 0) {
            $text .= ", {$summary['skipped']} dilewati (nomor tidak valid)";
        }

        return $text . '.';
    }
}