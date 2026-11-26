<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Video;

class VideoPolicy
{
    public function delete(User $user, Video $video): bool
    {
        return $user->isTeacher()
            && $video->teacher_id === $user->id;
    }
}