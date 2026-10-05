<?php

namespace App\Http\Controllers;

use App\Models\Development;
use App\Models\Student;
use App\Models\Notification;
use App\Services\FonnteService;
use Illuminate\Http\Request;

class DevelopmentController extends Controller
{
    public function index()
    {
        $developments = Development::with([
                'student.parent',
                'teacher'
            ])
            ->where('teacher_id', auth()->id())
            ->latest('monitoring_date')
            ->paginate(10);

        return view(
            'developments.index',
            compact('developments')
        );
    }

    public function create()
    {
        $students = Student::orderBy('name')->get();

        return view(
            'developments.create',
            compact('students')
        );
    }

    public function store(
        Request $request,
        FonnteService $fonnte
    ) {
        $data = $request->validate([
            'student_id' => ['required', 'exists:students,id'],
            'monitoring_date' => ['required', 'date'],
            'subject' => ['required', 'max:255'],
            'ability' => ['required'],
            'development' => ['required'],
            'note' => ['nullable'],
            'suggestion' => ['nullable'],
        ]);

        $data['teacher_id'] = auth()->id();

        $development = Development::create($data);

        $student = Student::with('parent')
            ->findOrFail($data['student_id']);

        $status = 'failed';

        if ($student->parent && $student->parent->whatsapp) {

            $message =
                "Assalamu'alaikum.\n\n" .
                "Informasi perkembangan belajar siswa\n\n" .
                "Nama: {$student->name}\n" .
                "Kelas: {$student->class_name}\n" .
                "Mata Pelajaran: {$development->subject}\n\n" .
                "Kemampuan:\n{$development->ability}\n\n" .
                "Perkembangan:\n{$development->development}\n\n" .
                "Catatan:\n" .
                ($development->note ?: '-') .
                "\n\nSaran:\n" .
                ($development->suggestion ?: '-') .
                "\n\n" .
                "Silakan membuka sistem monitoring untuk melihat informasi lengkap.";

            $result = $fonnte->send(
                $student->parent->whatsapp,
                $message
            );

            $status = $result['status']
                ? 'sent'
                : 'failed';

            Notification::create([
                'user_id' => $student->parent->id,
                'student_id' => $student->id,
                'type' => 'development',
                'message' => $message,
                'status' => $status,
                'sent_at' => $result['status']
                    ? now()
                    : null,
            ]);
        }

        return redirect()
            ->route('developments.index')
            ->with(
                'success',
                'Monitoring berhasil disimpan. Status WhatsApp: ' .
                strtoupper($status)
            );
    }

    public function edit(Development $development)
    {
        abort_unless(
            $development->teacher_id === auth()->id(),
            403
        );

        $students = Student::orderBy('name')->get();

        return view(
            'developments.edit',
            compact('development', 'students')
        );
    }

    public function update(
        Request $request,
        Development $development
    ) {
        abort_unless(
            $development->teacher_id === auth()->id(),
            403
        );

        $data = $request->validate([
            'student_id' => ['required', 'exists:students,id'],
            'monitoring_date' => ['required', 'date'],
            'subject' => ['required'],
            'ability' => ['required'],
            'development' => ['required'],
            'note' => ['nullable'],
            'suggestion' => ['nullable'],
        ]);

        $development->update($data);

        return redirect()
            ->route('developments.index')
            ->with(
                'success',
                'Data perkembangan berhasil diperbarui.'
            );
    }

    public function destroy(Development $development)
    {
        abort_unless(
            $development->teacher_id === auth()->id(),
            403
        );

        $development->delete();

        return back()->with(
            'success',
            'Data perkembangan berhasil dihapus.'
        );
    }
}