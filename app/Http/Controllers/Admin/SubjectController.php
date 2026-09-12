<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SubjectController extends Controller
{
    public function index()
    {
        $subjects = Subject::with('teacher')
            ->orderBy('name')
            ->get();

        $teachers = User::where('role', 'guru')
            ->where('is_approved', true)
            ->orderBy('name')
            ->get();

        return view('admin.subjects.index', compact('subjects', 'teachers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100', 'unique:subjects,name'],
            'teacher_id' => ['nullable', 'exists:users,id'],
        ]);

        $this->ensureApprovedTeacher($validated['teacher_id'] ?? null);
        Subject::create($validated);

        return back()->with('success', 'Mata pelajaran berhasil ditambahkan.');
    }

    public function update(Request $request, Subject $subject)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100', Rule::unique('subjects', 'name')->ignore($subject->id)],
            'teacher_id' => ['nullable', 'exists:users,id'],
        ]);

        $this->ensureApprovedTeacher($validated['teacher_id'] ?? null);
        $subject->update($validated);

        return back()->with('success', 'Penugasan guru berhasil diperbarui.');
    }

    public function destroy(Subject $subject)
    {
        $subject->delete();

        return back()->with('success', 'Mata pelajaran berhasil dihapus.');
    }

    private function ensureApprovedTeacher(?int $teacherId): void
    {
        if ($teacherId === null) {
            return;
        }

        abort_unless(
            User::whereKey($teacherId)
                ->where('role', 'guru')
                ->where('is_approved', true)
                ->exists(),
            422,
            'Guru yang dipilih belum disetujui.'
        );
    }
}
