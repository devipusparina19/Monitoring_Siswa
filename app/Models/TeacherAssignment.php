<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TeacherAssignment extends Model
{
    protected $fillable = [
        'teacher_id',
        'classroom_id',
        'subject_id',
        'type',
    ];

    protected static function booted(): void
    {
        // Menjaga aturan: wali kelas tidak punya mapel, dan 1 kelas hanya 1 wali kelas
        static::saving(function (self $assignment) {
            if ($assignment->type === 'homeroom') {
                $assignment->subject_id = null;
                $assignment->homeroom_classroom_id = $assignment->classroom_id;
            } else {
                $assignment->homeroom_classroom_id = null;
            }
        });
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function classroom(): BelongsTo
    {
        return $this->belongsTo(Classroom::class);
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    public function isHomeroom(): bool
    {
        return $this->type === 'homeroom';
    }
}