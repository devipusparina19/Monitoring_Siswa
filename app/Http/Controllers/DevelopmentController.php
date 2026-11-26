<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use App\Models\Development;
use App\Models\Student;
use App\Models\Subject;
use App\Models\User;
use App\Services\ParentNotifier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class DevelopmentController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'q' => ['nullable', 'string', 'max:100'],
            'classroom_id' => ['nullable', 'integer'],
            'subject_id' => ['nullable', 'integer'],
            'teacher_id' => ['nullable', 'integer'],
            'date_from' => ['nullable', 'date'],
            'date_to' => ['nullable', 'date', 'after_or_equal:date_from'],
        ]);

        $developments = Development::with(['student.classroom', 'teacher'])
            ->visibleTo($user)
            ->whereHas('student')
            ->when($request->q, function ($query, $term) {
                $query->whereHas('student', function ($s) use ($term) {
                    $s->where(function ($w) use ($term) {
                        $w->where('name', 'like', "%{$term}%")
                            ->orWhere('nis', 'like', "%{$term}%");
                    });
                });
            })
            ->when($request->classroom_id, function ($query, $id) {
                $query->whereHas('student', fn ($s) => $s->where('classroom_id', $id));
            })
            ->when($request->subject_id, fn ($q, $v) => $q->where('subject_id', $v))
            ->when($request->teacher_id, fn ($q, $v) => $q->where('teacher_id', $v))
            ->when($request->date_from, fn ($q, $v) => $q->whereDate('monitoring_date', '>=', $v))
            ->when($request->date_to, fn ($q, $v) => $q->whereDate('monitoring_date', '<=', $v))
            ->latest('monitoring_date')
            ->latest('id')
            ->paginate(10)
            ->withQueryString();

        return view('developments.index', [
            'developments' => $developments,
            'classrooms' => Classroom::whereIn('id', $user->assignedClassroomIds())
                ->orderBy('name')->get(),
            'subjects' => Subject::orderBy('name')->get(),
            'teachers' => User::where('role', 'guru')->orderBy('name')->get(),
        ]);
    }

    // Form hanya dibuka dari detail siswa: /developments/create?student=ID
    public function create(Request $request)
    {
        $student = Student::with('classroom')->find($request->query('student'));

        if (!$student) {
            return redirect()
                ->route('teacher.students.index')
                ->with('error', 'Pilih siswa terlebih dahulu.');
        }

        Gate::authorize('view', $student);

        $subjects = $request->user()->subjectsFor($student);

        if ($subjects->isEmpty()) {
            return redirect()
                ->route('teacher.students.show', $student)
                ->with('error', 'Anda belum memiliki penugasan mata pelajaran untuk kelas siswa ini.');
        }

        return view('developments.create', compact('student', 'subjects'));
    }

    public function store(Request $request, ParentNotifier $notifier)
    {
        $data = $request->validate([
            'student_id' => ['required', 'integer', 'exists:students,id,deleted_at,NULL'],
            'subject_id' => ['required', 'integer', 'exists:subjects,id'],
            'monitoring_date' => ['required', 'date', 'before_or_equal:today'],
            'ability' => ['required', 'string', 'max:2000'],
            'development' => ['required', 'string', 'max:2000'],
            'note' => ['nullable', 'string', 'max:2000'],
            'suggestion' => ['nullable', 'string', 'max:2000'],
        ]);

        $user = $request->user();
        $student = Student::with('classroom')->findOrFail($data['student_id']);

        // Kewenangan diperiksa di backend: siswa + mata pelajaran
        abort_unless(
            $user->canMonitor($student, (int) $data['subject_id']),
            403,
            'Anda tidak berwenang memonitor siswa atau mata pelajaran ini.'
        );

        $subject = Subject::findOrFail($data['subject_id']);

        // Data utama disimpan lebih dulu di dalam transaction
        $development = DB::transaction(function () use ($data, $user, $subject) {
            return Development::create([
                'student_id' => $data['student_id'],
                'teacher_id' => $user->id,
                'subject_id' => $subject->id,
                'subject' => $subject->name,
                'monitoring_date' => $data['monitoring_date'],
                'ability' => $data['ability'],
                'development' => $data['development'],
                'note' => $data['note'] ?? null,
                'suggestion' => $data['suggestion'] ?? null,
            ]);
        });

        // WhatsApp dikirim setelah data tersimpan; kegagalan tidak membatalkan data
        $summary = $notifier->notify(
            $student,
            'development',
            $this->buildMessage($student, $development)
        );

        return redirect()
            ->route('teacher.students.show', $student)
            ->with(
                'success',
                'Monitoring berhasil disimpan. ' . ParentNotifier::describe($summary)
            );
    }

    public function edit(Request $request, Development $development)
    {
        Gate::authorize('update', $development);

        $development->load('student.classroom');

        $subjects = $request->user()->subjectsFor($development->student);

        return view('developments.edit', compact('development', 'subjects'));
    }

    public function update(Request $request, Development $development)
    {
        Gate::authorize('update', $development);

        // Siswa tidak dapat dipindahkan agar catatan tidak tertukar antar-siswa
        $data = $request->validate([
            'subject_id' => ['required', 'integer', 'exists:subjects,id'],
            'monitoring_date' => ['required', 'date', 'before_or_equal:today'],
            'ability' => ['required', 'string', 'max:2000'],
            'development' => ['required', 'string', 'max:2000'],
            'note' => ['nullable', 'string', 'max:2000'],
            'suggestion' => ['nullable', 'string', 'max:2000'],
        ]);

        abort_unless(
            $request->user()->canMonitor($development->student, (int) $data['subject_id']),
            403,
            'Anda tidak berwenang untuk mata pelajaran ini.'
        );

        $subject = Subject::findOrFail($data['subject_id']);

        DB::transaction(function () use ($development, $data, $subject) {
            $development->update([
                'subject_id' => $subject->id,
                'subject' => $subject->name,
                'monitoring_date' => $data['monitoring_date'],
                'ability' => $data['ability'],
                'development' => $data['development'],
                'note' => $data['note'] ?? null,
                'suggestion' => $data['suggestion'] ?? null,
            ]);
        });

        return redirect()
            ->route('developments.index')
            ->with('success', 'Data perkembangan berhasil diperbarui.');
    }

    public function destroy(Development $development)
    {
        Gate::authorize('delete', $development);

        $development->delete();

        return back()->with('success', 'Data perkembangan berhasil dihapus.');
    }

    private function buildMessage(Student $student, Development $development): string
    {
        return
            "Assalamu'alaikum.\n\n" .
            "Informasi perkembangan belajar siswa\n\n" .
            "Nama: {$student->name}\n" .
            "Kelas: {$student->class_name}\n" .
            "Mata Pelajaran: {$development->subject}\n\n" .
            "Kemampuan:\n{$development->ability}\n\n" .
            "Perkembangan:\n{$development->development}\n\n" .
            "Catatan:\n" . ($development->note ?: '-') .
            "\n\nSaran:\n" . ($development->suggestion ?: '-') .
            "\n\n" .
            "Silakan membuka sistem monitoring untuk melihat informasi lengkap.";
    }
}