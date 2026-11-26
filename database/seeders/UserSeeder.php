<?php

namespace Database\Seeders;

use App\Models\Classroom;
use App\Models\Development;
use App\Models\Student;
use App\Models\Subject;
use App\Models\TeacherAssignment;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $make = fn (string $name, string $email, string $role) => User::firstOrCreate(
            ['email' => $email],
            ['name' => $name, 'password' => 'password', 'role' => $role, 'whatsapp' => null]
        );

        $admin     = $make('Admin Sekolah', 'admin@monitoring.test', 'admin');
        $wali      = $make('Guru Demo (Wali Kelas V)', 'guru@monitoring.test', 'guru');
        $olahraga  = $make('Guru Olahraga', 'olahraga@monitoring.test', 'guru');
        $parent1   = $make('Orang Tua Demo', 'orangtua@monitoring.test', 'orang_tua');
        $parent2   = $make('Orang Tua Dua', 'orangtua2@monitoring.test', 'orang_tua');

        $kelasIV = Classroom::firstOrCreate(['name' => 'IV']);
        $kelasV  = Classroom::firstOrCreate(['name' => 'V']);

        $matematika = Subject::firstOrCreate(['name' => 'Matematika']);
        $pjok       = Subject::firstOrCreate(['name' => 'PJOK']);

        // Wali kelas V
        TeacherAssignment::firstOrCreate(
            ['teacher_id' => $wali->id, 'classroom_id' => $kelasV->id, 'type' => 'homeroom']
        );

        // Guru olahraga mengajar kelas IV dan V
        foreach ([$kelasIV, $kelasV] as $kelas) {
            TeacherAssignment::firstOrCreate([
                'teacher_id' => $olahraga->id,
                'classroom_id' => $kelas->id,
                'subject_id' => $pjok->id,
                'type' => 'subject',
            ]);
        }

        $siswaV = Student::firstOrCreate(
            ['nis' => 'DEMO001'],
            ['name' => 'Siswa Demo', 'classroom_id' => $kelasV->id, 'gender' => 'L']
        );

        $siswaIV = Student::firstOrCreate(
            ['nis' => 'DEMO002'],
            ['name' => 'Siswi Demo Dua', 'classroom_id' => $kelasIV->id, 'gender' => 'P']
        );

        $siswaV->parents()->syncWithoutDetaching([$parent1->id]);
        $siswaIV->parents()->syncWithoutDetaching([$parent2->id]);

        Development::firstOrCreate(
            [
                'student_id' => $siswaV->id,
                'teacher_id' => $wali->id,
                'subject_id' => $matematika->id,
                'monitoring_date' => now()->toDateString(),
            ],
            [
                'subject' => $matematika->name,
                'ability' => 'Mampu memahami operasi dasar pecahan.',
                'development' => 'Siswa mulai memahami konsep pecahan dengan baik.',
                'note' => 'Masih perlu latihan pada soal cerita.',
                'suggestion' => 'Berikan latihan pecahan secara bertahap di rumah.',
            ]
        );
    }
}