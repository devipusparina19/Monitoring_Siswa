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
        'youtube_video_id',
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

    // ID video yang aman dipakai di iframe
    public function getYoutubeIdAttribute(): ?string
    {
        return $this->youtube_video_id ?: self::extractYoutubeId($this->youtube_url);
    }

    // Mengambil ID 11 karakter dari URL YouTube yang sah; selain itu mengembalikan null
    public static function extractYoutubeId(?string $url): ?string
    {
        if (!$url) {
            return null;
        }

        $parts = parse_url(trim($url));

        if (!$parts || empty($parts['host'])) {
            return null;
        }

        if (!in_array(strtolower($parts['scheme'] ?? ''), ['http', 'https'], true)) {
            return null;
        }

        $host = strtolower(preg_replace('/^www\./', '', $parts['host']));
        $path = $parts['path'] ?? '';
        $id = null;

        if ($host === 'youtu.be') {
            $id = trim($path, '/');
        } elseif (in_array($host, [
            'youtube.com',
            'm.youtube.com',
            'music.youtube.com',
            'youtube-nocookie.com',
        ], true)) {
            if (!empty($parts['query'])) {
                parse_str($parts['query'], $query);
                $id = is_string($query['v'] ?? null) ? $query['v'] : null;
            }

            if (!$id && preg_match('#^/(embed|shorts|live|v)/([^/?]+)#', $path, $m)) {
                $id = $m[2];
            }
        }

        return ($id && preg_match('/^[A-Za-z0-9_-]{11}$/', $id)) ? $id : null;
    }
}