<?php

namespace App\Http\Controllers;

use App\Models\Development;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SubjectController extends Controller
{
    public function index()
    {
        $subjects = Subject::orderBy('name')->paginate(15);

        return view('subjects.index', compact('subjects'));
    }

    public function create()
    {
        return view('subjects.form', ['subject' => new Subject()]);
    }

    public function store(Request $request)
    {
        $request->merge(['name' => trim((string) $request->input('name'))]);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:100', 'unique:subjects,name'],
        ]);

        Subject::create($data);

        return redirect()
            ->route('subjects.index')
            ->with('success', 'Mata pelajaran berhasil ditambahkan.');
    }

    public function edit(Subject $subject)
    {
        return view('subjects.form', compact('subject'));
    }

    public function update(Request $request, Subject $subject)
    {
        $request->merge(['name' => trim((string) $request->input('name'))]);

        $data = $request->validate([
            'name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('subjects', 'name')->ignore($subject->id),
            ],
        ]);

        $subject->update($data);

        // Nama mapel pada catatan lama ikut diperbarui agar tidak berbeda
        Development::where('subject_id', $subject->id)
            ->update(['subject' => $subject->name]);

        return redirect()
            ->route('subjects.index')
            ->with('success', 'Mata pelajaran berhasil diperbarui.');
    }

    public function destroy(Subject $subject)
    {
        if ($subject->assignments()->exists() || $subject->developments()->exists()) {
            return back()->with(
                'error',
                'Mata pelajaran sudah dipakai pada penugasan atau data monitoring, sehingga tidak dapat dihapus.'
            );
        }

        $subject->delete();

        return back()->with('success', 'Mata pelajaran berhasil dihapus.');
    }
}