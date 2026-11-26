<?php

namespace App\Policies;

use App\Models\Development;
use App\Models\User;

class DevelopmentPolicy
{
    // Hanya pembuat, dan hanya selama penugasannya masih berlaku
    public function update(User $user, Development $development): bool
    {
        $student = $development->student;

        return $user->isTeacher()
            && $development->teacher_id === $user->id
            && $student !== null
            && $user->canMonitor($student, (int) $development->subject_id);
    }

    public function delete(User $user, Development $development): bool
    {
        return $user->isTeacher()
            && $development->teacher_id === $user->id;
    }
}