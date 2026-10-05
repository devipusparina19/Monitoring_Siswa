<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index()
    {
        $students = Student::with('parent')
            ->latest()
            ->paginate(10);

        return view('students.index', compact('students'));
    }

    public function create()
    {
        $parents = User::where('role', 'orang_tua')
            ->orderBy('name')
            ->get();

        return view('students.form', [
            'student' => new Student(),
            'parents' => $parents,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nis' => ['required', 'unique:students,nis'],
            'name' => ['required'],
            'class_name' => ['required'],
            'gender' => ['required', 'in:L,P'],
            'birth_date' => ['nullable', 'date'],
            'parent_id' => ['nullable', 'exists:users,id'],
        ]);

        Student::create($data);

        return redirect()
            ->route('students.index')
            ->with('success', 'Data siswa berhasil ditambahkan.');
    }

    public function edit(Student $student)
    {
        $parents = User::where('role', 'orang_tua')
            ->orderBy('name')
            ->get();

        return view('students.form', compact(
            'student',
            'parents'
        ));
    }

    public function update(Request $request, Student $student)
    {
        $data = $request->validate([
            'nis' => [
                'required',
                'unique:students,nis,' . $student->id,
            ],
            'name' => ['required'],
            'class_name' => ['required'],
            'gender' => ['required', 'in:L,P'],
            'birth_date' => ['nullable', 'date'],
            'parent_id' => ['nullable', 'exists:users,id'],
        ]);

        $student->update($data);

        return redirect()
            ->route('students.index')
            ->with('success', 'Data siswa berhasil diperbarui.');
    }

    public function destroy(Student $student)
    {
        $student->delete();

        return back()->with(
            'success',
            'Data siswa berhasil dihapus.'
        );
    }
}