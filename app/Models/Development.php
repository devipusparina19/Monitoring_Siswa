<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Development extends Model
{
    protected $fillable = [
        'student_id',
        'teacher_id',
        'subject_id',
        'monitoring_date',
        'subject',
        'ability',
        'development',
        'note',
        'suggestion',
    ];

    protected function casts(): array
    {
        return [
            'monitoring_date' => 'date',
        ];
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function subjectRef(): BelongsTo
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }

    // Monitoring yang boleh dilihat: milik sendiri, atau semua monitoring di kelas yang diwalikan
    public function scopeVisibleTo(Builder $query, User $user): Builder
    {
        if ($user->isAdmin()) {
            return $query;
        }

        return $query->where(function (Builder $q) use ($user) {
            $q->where('developments.teacher_id', $user->id)
                ->orWhereIn(
                    'developments.student_id',
                    Student::query()
                        ->whereIn('classroom_id', $user->homeroomClassroomIds())
                        ->select('id')
                );
        });
    }

    /**
     * Jumlah catatan monitoring per bulan (data asli) untuk grafik batang kecil.
     *
     * @return array<int, array{label:string, count:int, percent:int}>
     */
    public static function activityByMonth(iterable $items, int $months = 6): array
    {
        $counts = [];

        foreach ($items as $item) {
            if (!$item->monitoring_date) {
                continue;
            }

            $key = $item->monitoring_date->format('Y-m');
            $counts[$key] = ($counts[$key] ?? 0) + 1;
        }

        $rows = [];
        $max = 0;

        for ($i = $months - 1; $i >= 0; $i--) {
            $month = now()->startOfMonth()->subMonths($i);
            $count = $counts[$month->format('Y-m')] ?? 0;
            $max = max($max, $count);

            $rows[] = [
                'label' => $month->copy()->locale('id')->translatedFormat('M'),
                'count' => $count,
                'percent' => 0,
            ];
        }

        foreach ($rows as $index => $row) {
            $rows[$index]['percent'] = ($max > 0 && $row['count'] > 0)
                ? (int) max(8, round($row['count'] / $max * 100))
                : 0;
        }

        return $rows;
    }
}