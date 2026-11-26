<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Classroom extends Model
{
    protected $fillable = ['name'];

    public function students(): HasMany
    {
        return $this->hasMany(Student::class);
    }

    public function assignments(): HasMany
    {
        return $this->hasMany(TeacherAssignment::class);
    }

    public function homeroomAssignment(): HasOne
    {
        return $this->hasOne(TeacherAssignment::class)
            ->where('type', 'homeroom');
    }
}