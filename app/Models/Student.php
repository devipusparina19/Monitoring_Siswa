<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Student extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'nis',
        'name',
        'classroom_id',
        'gender',
        'birth_date',
    ];

    protected function casts(): array
    {
        return [
            'birth_date' => 'date',
        ];
    }

    public function classroom(): BelongsTo
    {
        return $this->belongsTo(Classroom::class);
    }

    public function parents(): BelongsToMany
    {
        return $this->belongsToMany(
            User::class,
            'student_parent',
            'student_id',
            'parent_id'
        )->withTimestamps();
    }

    public function developments(): HasMany
    {
        return $this->hasMany(Development::class);
    }

    public function videos(): HasMany
    {
        return $this->hasMany(Video::class);
    }

    // Agar view lama yang memakai $student->class_name tetap berjalan
    public function getClassNameAttribute($value): ?string
    {
        return $this->classroom?->name ?? $value;
    }
}