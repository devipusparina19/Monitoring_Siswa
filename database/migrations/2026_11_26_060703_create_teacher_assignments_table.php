<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('teacher_assignments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('teacher_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignId('classroom_id')
                ->constrained('classrooms')
                ->cascadeOnDelete();

            // NULL untuk wali kelas, wajib terisi untuk guru mata pelajaran
            $table->foreignId('subject_id')
                ->nullable()
                ->constrained('subjects')
                ->cascadeOnDelete();

            $table->enum('type', ['homeroom', 'subject']);

            // Diisi otomatis oleh model hanya untuk wali kelas.
            // UNIQUE di sini menjamin 1 kelas hanya punya 1 wali kelas di level database.
            $table->unsignedBigInteger('homeroom_classroom_id')
                ->nullable()
                ->unique();

            $table->timestamps();

            // Mencegah penugasan mapel yang sama tercatat dua kali
            $table->unique(
                ['teacher_id', 'classroom_id', 'subject_id', 'type'],
                'teacher_assignments_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('teacher_assignments');
    }
};