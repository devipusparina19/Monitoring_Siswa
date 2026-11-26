<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'whatsapp',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /* ---------- Relasi ---------- */

    // Anak yang terhubung dengan akun orang tua (lewat pivot student_parent)
    public function children(): BelongsToMany
    {
        return $this->belongsToMany(
            Student::class,
            'student_parent',
            'parent_id',
            'student_id'
        )->withTimestamps();
    }

    public function assignments(): HasMany
    {
        return $this->hasMany(TeacherAssignment::class, 'teacher_id');
    }

    public function developments(): HasMany
    {
        return $this->hasMany(Development::class, 'teacher_id');
    }

    public function videos(): HasMany
    {
        return $this->hasMany(Video::class, 'teacher_id');
    }

    /* ---------- Peran ---------- */

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isTeacher(): bool
    {
        return $this->role === 'guru';
    }

    public function isParent(): bool
    {
        return $this->role === 'orang_tua';
    }

    /* ---------- Hak akses guru ---------- */

    public function homeroomClassroomIds(): array
    {
        $this->loadMissing('assignments');

        return $this->assignments
            ->where('type', 'homeroom')
            ->pluck('classroom_id')
            ->unique()
            ->values()
            ->all();
    }

    public function assignedClassroomIds(): array
    {
        $this->loadMissing('assignments');

        return $this->assignments
            ->pluck('classroom_id')
            ->unique()
            ->values()
            ->all();
    }

    // Siswa yang boleh diakses akun ini
    public function accessibleStudents(): Builder
    {
        if ($this->isAdmin()) {
            return Student::query();
        }

        if ($this->isTeacher()) {
            return Student::query()
                ->whereIn('classroom_id', $this->assignedClassroomIds());
        }

        return Student::query()->whereHas(
            'parents',
            fn ($q) => $q->where('users.id', $this->id)
        );
    }

    public function canAccessStudent(Student $student): bool
    {
        if ($this->isAdmin()) {
            return true;
        }

        if ($this->isTeacher()) {
            return $student->classroom_id !== null
                && in_array($student->classroom_id, $this->assignedClassroomIds());
        }

        return false;
    }

    // Mapel yang boleh dimonitor guru untuk siswa tertentu
    public function subjectsFor(Student $student): Collection
    {
        if (!$this->isTeacher() || !$student->classroom_id) {
            return new Collection();
        }

        // Wali kelas: semua mapel di kelasnya
        if (in_array($student->classroom_id, $this->homeroomClassroomIds())) {
            return Subject::orderBy('name')->get();
        }

        // Guru mapel: hanya mapel yang ditugaskan di kelas siswa tersebut
        $subjectIds = $this->assignments
            ->where('type', 'subject')
            ->where('classroom_id', $student->classroom_id)
            ->pluck('subject_id')
            ->filter()
            ->all();

        return Subject::whereIn('id', $subjectIds)
            ->orderBy('name')
            ->get();
    }

    public function canMonitor(Student $student, int $subjectId): bool
    {
        return $this->subjectsFor($student)->contains('id', $subjectId);
    }
}