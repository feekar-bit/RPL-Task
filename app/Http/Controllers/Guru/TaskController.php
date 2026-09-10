<?php

namespace App\Http\Controllers\Guru;

use App\Models\Task;
use App\Models\SchoolClass;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    // daftar tugas
    public function index()
    {
        $tasks = Task::where('teacher_id', Auth::id())
            ->where('deadline', '>', now())
            ->with('schoolClass')
            ->latest()
            ->get();

        return view('guru.tasks.index', compact('tasks'));
    }

    // form create
    public function create()
    {
        $classes = SchoolClass::where('status', 'active')
            ->orderByRaw("CASE grade WHEN 'X' THEN 1 WHEN 'XI' THEN 2 WHEN 'XII' THEN 3 ELSE 4 END")
            ->orderBy('rombel', 'asc')
            ->orderBy('name', 'asc')
            ->get();

        return view('guru.tasks.create', compact('classes'));
    }

    // simpan tugas
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'class_id' => 'required',
            'deadline' => 'required|date',
            'attachment' => 'nullable|file|max:2048'
        ]);

        $classId = $request->class_id;
        if (!is_numeric($classId)) {
            $classId = SchoolClass::where('name', $classId)->value('id') ?? $classId;
        }

        $attachment = null;

        // upload file
        if ($request->hasFile('attachment')) {
            $attachment = $request->file('attachment')
                ->store('task_attachments', 'public');
        }

        Task::create([
            'teacher_id' => Auth::id(),

            'title' => $request->title,
            'description' => $request->description,
            'class_id' => $classId,
            'deadline' => $request->deadline,

            'attachment' => $attachment,
        ]);

        return redirect('/guru/tasks')
            ->with('success', 'Tugas berhasil dibuat.');
    }

    // form edit
    public function edit(int $id)
    {
        $task = Task::with('schoolClass')->findOrFail($id);
        $classes = SchoolClass::where('status', 'active')
            ->orderByRaw("CASE grade WHEN 'X' THEN 1 WHEN 'XI' THEN 2 WHEN 'XII' THEN 3 ELSE 4 END")
            ->orderBy('rombel', 'asc')
            ->orderBy('name', 'asc')
            ->get();

        return view('guru.tasks.edit', compact('task', 'classes'));
    }

    // update
    public function update(Request $request, int $id)
    {
        $task = Task::findOrFail($id);

        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'class_id' => 'required',
            'deadline' => 'required|date',
        ]);

        $classId = $request->class_id;
        if (!is_numeric($classId)) {
            $classId = SchoolClass::where('name', $classId)->value('id') ?? $classId;
        }

        $task->update([
            'title' => $request->title,
            'description' => $request->description,
            'class_id' => $classId,
            'deadline' => $request->deadline,
        ]);

        return redirect('/guru/tasks')
            ->with('success', 'Tugas berhasil diupdate.');
    }

    // delete
    public function destroy(int $id)
    {
        $task = Task::findOrFail($id);

        $task->delete();

        return back()
            ->with('success', 'Tugas berhasil dihapus.');
    }

    public function history()
    {
        $tasks = Task::where('teacher_id', Auth::id())
            ->where('deadline', '<', now())
            ->with('schoolClass')
            ->latest()
            ->get();

        return view(
            'guru.tasks.history',
            compact('tasks')
        );
    }
}