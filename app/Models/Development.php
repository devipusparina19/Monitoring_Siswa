<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Development extends Model
{
    protected $fillable = [
        'student_id',
        'teacher_id',
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
}