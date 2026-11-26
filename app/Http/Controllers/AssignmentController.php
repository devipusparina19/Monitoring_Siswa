<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use App\Models\Subject;
use App\Models\TeacherAssignment;
use App\Models\User;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class AssignmentController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'teacher_id' => ['nullable', 'integer'],
        ]);

        $assignments = TeacherAssignment::with(['teacher', 'classroom', 'subject'])
            ->when($request->teacher_id, fn ($q, $v) => $q->where('teacher_id', $v))
            ->orderBy('teacher_id')
            ->orderBy('type')
            ->orderBy('classroom_id')
            ->paginate(20)
            ->withQueryString();

        return view('assignments.index', [
            'assignments' => $assignments,
            'teachers' => User::where('role', 'guru')->orderBy('name')->get(),
            'classrooms' => Classroom::orderBy('name')->get(),
            'subjects' => Subject::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'teacher_id' => [
                'required',
                Rule::exists('users', 'id')->where('role', 'guru'),
            ],
            'type' => ['required', 'in:homeroom,subject'],
            'classroom_id' => ['required', 'exists:classrooms,id'],
            'subject_id' => [
                'nullable',
                'required_if:type,subject',
                'exists:subjects,id',
            ],
        ], [
            'subject_id.required_if' => 'Mata pelajaran wajib dipilih untuk guru mata pelajaran.',
        ]);

        try {
            DB::transaction(function () use ($data) {

                if ($data['type'] === 'homeroom') {

                    if (TeacherAssignment::where('type', 'homeroom')
                        ->where('classroom_id', $data['classroom_id'])
                        ->lockForUpdate()
                        ->exists()) {
                        throw ValidationException::withMessages([
                            'classroom_id' => 'Kelas ini sudah memiliki wali kelas.',
                        ]);
                    }

                    if (TeacherAssignment::where('type', 'homeroom')
                        ->where('teacher_id', $data['teacher_id'])
                        ->lockForUpdate()
                        ->exists()) {
                        throw ValidationException::withMessages([
                            'teacher_id' => 'Guru ini sudah menjadi wali kelas di kelas lain.',
                        ]);
                    }

                    $data['subject_id'] = null;

                } elseif (TeacherAssignment::where('type', 'subject')
                    ->where('teacher_id', $data['teacher_id'])
                    ->where('classroom_id', $data['classroom_id'])
                    ->where('subject_id', $data['subject_id'])
                    ->lockForUpdate()
                    ->exists()) {
                    throw ValidationException::withMessages([
                        'subject_id' => 'Penugasan guru, kelas, dan mata pelajaran ini sudah ada.',
                    ]);
                }

                TeacherAssignment::create($data);
            });
        } catch (UniqueConstraintViolationException) {
            return back()
                ->withInput()
                ->with('error', 'Penugasan tersebut sudah ada.');
        }

        return redirect()
            ->route('assignments.index')
            ->with('success', 'Penugasan guru berhasil ditambahkan.');
    }

    public function destroy(TeacherAssignment $assignment)
    {
        $assignment->delete();

        return back()->with(
            'success',
            'Penugasan dihapus. Data monitoring yang sudah ada tetap tersimpan.'
        );
    }
}