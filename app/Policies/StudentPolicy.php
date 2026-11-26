<?php

namespace App\Policies;

use App\Models\Student;
use App\Models\User;

class StudentPolicy
{
    public function view(User $user, Student $student): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        if ($user->isTeacher()) {
            return $user->canAccessStudent($student);
        }

        if ($user->isParent()) {
            return $student->parents()
                ->where('users.id', $user->id)
                ->exists();
        }

        return false;
    }
}