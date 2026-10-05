<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Student;
use App\Models\Development;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::create([
            'name' => 'Admin Sekolah',
            'email' => 'admin@monitoring.test',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'whatsapp' => null,
        ]);

        $guru = User::create([
            'name' => 'Guru Demo',
            'email' => 'guru@monitoring.test',
            'password' => Hash::make('password'),
            'role' => 'guru',
            'whatsapp' => null,
        ]);

        $orangTua = User::create([
            'name' => 'Orang Tua Demo',
            'email' => 'orangtua@monitoring.test',
            'password' => Hash::make('password'),
            'role' => 'orang_tua',
            'whatsapp' => null,
        ]);

        $student = Student::create([
            'nis' => 'DEMO001',
            'name' => 'Siswa Demo',
            'class_name' => 'V',
            'gender' => 'L',
            'parent_id' => $orangTua->id,
        ]);

        Development::create([
            'student_id' => $student->id,
            'teacher_id' => $guru->id,
            'monitoring_date' => now()->toDateString(),
            'subject' => 'Matematika',
            'ability' => 'Mampu memahami operasi dasar pecahan.',
            'development' => 'Siswa mulai memahami konsep pecahan dengan baik.',
            'note' => 'Masih perlu latihan pada soal cerita.',
            'suggestion' => 'Berikan latihan pecahan secara bertahap di rumah.',
        ]);
    }
}