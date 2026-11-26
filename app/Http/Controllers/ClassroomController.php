<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ClassroomController extends Controller
{
    public function index()
    {
        $classrooms = Classroom::withCount('students')
            ->with('homeroomAssignment.teacher')
            ->orderBy('name')
            ->paginate(15);

        return view('classrooms.index', compact('classrooms'));
    }

    public function create()
    {
        return view('classrooms.form', ['classroom' => new Classroom()]);
    }

    public function store(Request $request)
    {
        $request->merge(['name' => trim((string) $request->input('name'))]);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:50', 'unique:classrooms,name'],
        ]);

        Classroom::create($data);

        return redirect()
            ->route('classrooms.index')
            ->with('success', 'Kelas berhasil ditambahkan.');
    }

    public function edit(Classroom $classroom)
    {
        return view('classrooms.form', compact('classroom'));
    }

    public function update(Request $request, Classroom $classroom)
    {
        $request->merge(['name' => trim((string) $request->input('name'))]);

        $data = $request->validate([
            'name' => [
                'required',
                'string',
                'max:50',
                Rule::unique('classrooms', 'name')->ignore($classroom->id),
            ],
        ]);

        $classroom->update($data);

        return redirect()
            ->route('classrooms.index')
            ->with('success', 'Kelas berhasil diperbarui.');
    }

    public function destroy(Classroom $classroom)
    {
        if (Student::withTrashed()->where('classroom_id', $classroom->id)->exists()) {
            return back()->with(
                'error',
                'Kelas masih memiliki siswa (termasuk siswa arsip). Pindahkan siswa terlebih dahulu.'
            );
        }

        if ($classroom->assignments()->exists()) {
            return back()->with(
                'error',
                'Kelas masih memiliki penugasan guru. Hapus penugasannya terlebih dahulu.'
            );
        }

        $classroom->delete();

        return back()->with('success', 'Kelas berhasil dihapus.');
    }
}