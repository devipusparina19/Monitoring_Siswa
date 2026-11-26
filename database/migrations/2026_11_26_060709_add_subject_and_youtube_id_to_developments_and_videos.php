<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('developments', function (Blueprint $table) {
            $table->foreignId('subject_id')
                ->nullable()
                ->after('teacher_id')
                ->constrained('subjects')
                ->restrictOnDelete();
        });

        Schema::table('developments', function (Blueprint $table) {
            // kolom teks lama dipertahankan (dipakai view yang sudah ada)
            $table->string('subject')->nullable()->change();
        });

        Schema::table('videos', function (Blueprint $table) {
            $table->string('youtube_video_id', 20)
                ->nullable()
                ->after('youtube_url');
        });

        DB::transaction(function () {

            // 1) Mapel teks lama -> data mapel
            $names = DB::table('developments')
                ->whereNotNull('subject')
                ->distinct()
                ->pluck('subject');

            foreach ($names as $rawName) {
                $name = trim($rawName);

                if ($name === '') {
                    continue;
                }

                $subjectId = DB::table('subjects')
                    ->where('name', $name)
                    ->value('id');

                if (!$subjectId) {
                    $subjectId = DB::table('subjects')->insertGetId([
                        'name' => $name,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }

                DB::table('developments')
                    ->where('subject', $rawName)
                    ->update(['subject_id' => $subjectId]);
            }

            // 2) Isi ID video YouTube; duplikat (siswa+video sama) dibiarkan NULL
            $seen = [];

            DB::table('videos')->orderBy('id')->each(function ($video) use (&$seen) {
                $id = null;
                $parts = parse_url(trim((string) $video->youtube_url));

                if ($parts && !empty($parts['host'])) {
                    $host = strtolower(preg_replace('/^www\./', '', $parts['host']));
                    $path = $parts['path'] ?? '';

                    if ($host === 'youtu.be') {
                        $id = trim($path, '/');
                    } elseif (in_array($host, ['youtube.com', 'm.youtube.com'], true)) {
                        if (!empty($parts['query'])) {
                            parse_str($parts['query'], $q);
                            $id = is_string($q['v'] ?? null) ? $q['v'] : null;
                        }
                        if (!$id && preg_match('#^/(embed|shorts|live)/([^/?]+)#', $path, $m)) {
                            $id = $m[2];
                        }
                    }
                }

                if (!$id || !preg_match('/^[A-Za-z0-9_-]{11}$/', $id)) {
                    return;
                }

                $key = $video->student_id . '|' . $id;

                if (isset($seen[$key])) {
                    return;
                }

                $seen[$key] = true;

                DB::table('videos')
                    ->where('id', $video->id)
                    ->update(['youtube_video_id' => $id]);
            });
        });

        Schema::table('videos', function (Blueprint $table) {
            $table->unique(
                ['student_id', 'youtube_video_id'],
                'videos_student_youtube_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::table('videos', function (Blueprint $table) {
            $table->dropUnique('videos_student_youtube_unique');
            $table->dropColumn('youtube_video_id');
        });

        Schema::table('developments', function (Blueprint $table) {
            $table->dropForeign(['subject_id']);
            $table->dropColumn('subject_id');
        });
    }
};