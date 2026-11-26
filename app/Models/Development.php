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
}