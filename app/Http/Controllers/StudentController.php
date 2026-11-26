<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'q' => ['nullable', 'string', 'max:100'],
            'classroom_id' => ['nullable', 'integer'],
        ]);

        $students = Student::with(['classroom', 'parents'])
            ->when($request->q, function ($query, $term) {
                $query->where(function ($w) use ($term) {
                    $w->where('name', 'like', "%{$term}%")
                        ->orWhere('nis', 'like', "%{$term}%");
                });
            })
            ->when($request->classroom_id, fn ($q, $v) => $q->where('classroom_id', $v))
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        return view('students.index', [
            'students' => $students,
            'classrooms' => Classroom::orderBy('name')->get(),
        ]);
    }

    public function create()
    {
        return view('students.form', [
            'student' => new Student(),
            'classrooms' => Classroom::orderBy('name')->get(),
            'parents' => $this->parentOptions(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);

        DB::transaction(function () use ($data) {
            $parentIds = $data['parent_ids'] ?? [];
            unset($data['parent_ids']);

            $student = Student::create($data);
            $student->parents()->sync($parentIds);
        });

        return redirect()
            ->route('students.index')
            ->with('success', 'Data siswa berhasil ditambahkan.');
    }

    public function edit(Student $student)
    {
        $student->load('parents');

        return view('students.form', [
            'student' => $student,
            'classrooms' => Classroom::orderBy('name')->get(),
            'parents' => $this->parentOptions(),
        ]);
    }

    public function update(Request $request, Student $student)
    {
        $data = $this->validated($request, $student);

        DB::transaction(function () use ($data, $student) {
            $parentIds = $data['parent_ids'] ?? [];
            unset($data['parent_ids']);

            // Pindah kelas = memperbarui classroom_id, bukan membuat siswa baru
            $student->update($data);
            $student->parents()->sync($parentIds);
        });

        return redirect()
            ->route('students.index')
            ->with('success', 'Data siswa berhasil diperbarui.');
    }

    public function destroy(Student $student)
    {
        // Soft delete: siswa diarsipkan, riwayat monitoring dan video tidak hilang
        $student->delete();

        return back()->with(
            'success',
            'Siswa diarsipkan. Riwayat monitoring dan videonya tetap tersimpan.'
        );
    }

    private function validated(Request $request, ?Student $student = null): array
    {
        $request->merge([
            'nis' => trim((string) $request->input('nis')),
            'name' => trim((string) $request->input('name')),
        ]);

        return $request->validate([
            'nis' => [
                'required',
                'string',
                'max:30',
                Rule::unique('students', 'nis')->ignore($student?->id),
            ],
            'name' => ['required', 'string', 'max:255'],
            'classroom_id' => ['required', 'integer', 'exists:classrooms,id'],
            'gender' => ['required', 'in:L,P'],
            'birth_date' => ['nullable', 'date', 'before:today'],
            'parent_ids' => ['nullable', 'array'],
            'parent_ids.*' => [
                'integer',
                'distinct',
                Rule::exists('users', 'id')->where('role', 'orang_tua'),
            ],
        ], [
            'nis.unique' => 'NIS sudah dipakai siswa lain (termasuk siswa yang diarsipkan).',
        ]);
    }

    private function parentOptions()
    {
        return User::where('role', 'orang_tua')
            ->orderBy('name')
            ->get();
    }
}