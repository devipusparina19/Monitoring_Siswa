<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Video extends Model
{
    protected $fillable = [
        'student_id',
        'teacher_id',
        'title',
        'youtube_url',
        'material',
        'description',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function getYoutubeIdAttribute(): ?string
    {
        $url = $this->youtube_url;

        if (!$url) {
            return null;
        }

        $parts = parse_url($url);

        if (!$parts) {
            return null;
        }

        $host = $parts['host'] ?? '';

        if (str_contains($host, 'youtu.be')) {
            return trim($parts['path'] ?? '/', '/');
        }

        if (str_contains($host, 'youtube.com')) {
            if (isset($parts['query'])) {
                parse_str($parts['query'], $query);

                if (!empty($query['v'])) {
                    return $query['v'];
                }
            }

            if (str_contains($parts['path'] ?? '', '/embed/')) {
                return last(explode('/embed/', $parts['path']));
            }
        }

        return null;
    }
}