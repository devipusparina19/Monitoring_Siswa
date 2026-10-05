<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\Development;
use App\Models\Notification;
use App\Services\FonnteService;
use Illuminate\Console\Command;
use Carbon\Carbon;

class SendWeeklyReminder extends Command
{
    protected $signature = 'monitoring:reminder';

    protected $description =
        'Mengirim pengingat WhatsApp kepada guru yang belum mengisi monitoring minggu berjalan';

    public function handle(FonnteService $fonnte): int
    {
        $startOfWeek = Carbon::now()
            ->startOfWeek();

        $endOfWeek = Carbon::now()
            ->endOfWeek();

        $teachers = User::where('role', 'guru')
            ->whereNotNull('whatsapp')
            ->get();

        foreach ($teachers as $teacher) {

            $hasMonitoring = Development::where(
                    'teacher_id',
                    $teacher->id
                )
                ->whereBetween(
                    'monitoring_date',
                    [
                        $startOfWeek->toDateString(),
                        $endOfWeek->toDateString()
                    ]
                )
                ->exists();

            if ($hasMonitoring) {
                $this->info(
                    "Lewat: {$teacher->name}"
                );

                continue;
            }

            $message =
                "Pengingat Monitoring Belajar\n\n" .
                "Halo {$teacher->name},\n\n" .
                "Anda belum melakukan pencatatan " .
                "perkembangan belajar siswa pada minggu " .
                "berjalan.\n\n" .
                "Silakan masuk ke sistem untuk " .
                "melakukan monitoring siswa.\n\n" .
                "Terima kasih.";

            $result = $fonnte->send(
                $teacher->whatsapp,
                $message
            );

            Notification::create([
                'user_id' => $teacher->id,
                'student_id' => null,
                'type' => 'teacher_reminder',
                'message' => $message,
                'status' => $result['status']
                    ? 'sent'
                    : 'failed',
                'sent_at' => $result['status']
                    ? now()
                    : null,
            ]);

            $this->info(
                "Reminder dikirim ke {$teacher->name}"
            );
        }

        return self::SUCCESS;
    }
}