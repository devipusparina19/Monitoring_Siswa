<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->foreignId('classroom_id')
                ->nullable()
                ->after('name')
                ->constrained('classrooms')
                ->restrictOnDelete();

            $table->softDeletes();
        });

        // class_name tidak dihapus agar data lama aman; hanya dibuat boleh kosong
        Schema::table('students', function (Blueprint $table) {
            $table->string('class_name')->nullable()->change();
        });

        DB::transaction(function () {

            // 1) Pindahkan teks kelas lama menjadi data kelas
            $names = DB::table('students')
                ->whereNotNull('class_name')
                ->distinct()
                ->pluck('class_name');

            foreach ($names as $rawName) {
                $name = trim($rawName);

                if ($name === '') {
                    continue;
                }

                $classroomId = DB::table('classrooms')
                    ->where('name', $name)
                    ->value('id');

                if (!$classroomId) {
                    $classroomId = DB::table('classrooms')->insertGetId([
                        'name' => $name,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }

                DB::table('students')
                    ->where('class_name', $rawName)
                    ->update(['classroom_id' => $classroomId]);
            }

            // 2) Pindahkan parent_id lama ke tabel pivot
            DB::table('students')
                ->whereNotNull('parent_id')
                ->orderBy('id')
                ->each(function ($student) {
                    DB::table('student_parent')->insertOrIgnore([
                        'student_id' => $student->id,
                        'parent_id' => $student->parent_id,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                });
        });
    }

    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropForeign(['classroom_id']);
            $table->dropColumn('classroom_id');
            $table->dropSoftDeletes();
        });
    }
};