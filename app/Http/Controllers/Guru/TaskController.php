<?php

namespace App\Http\Controllers\Guru;

use App\Models\Task;
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
            ->latest()
            ->get();

        return view('guru.tasks.index', compact('tasks'));
    }

    // form create
    public function create()
    {
        return view('guru.tasks.create');
    }

    // simpan tugas
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'class_target' => 'required',
            'deadline' => 'required|date',
            'attachment' => 'nullable|file|max:2048'
        ]);

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
            'class_target' => $request->class_target,
            'deadline' => $request->deadline,

            'attachment' => $attachment,
        ]);

        return redirect('/guru/tasks')
            ->with('success', 'Tugas berhasil dibuat.');
    }

    // form edit
    public function edit(int $id)
    {
        $task = Task::findOrFail($id);

        return view('guru.tasks.edit', compact('task'));
    }

    // update
    public function update(Request $request, int $id)
    {
        $task = Task::findOrFail($id);

        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'class_target' => 'required',
            'deadline' => 'required|date',
        ]);

        $task->update([
            'title' => $request->title,
            'description' => $request->description,
            'class_target' => $request->class_target,
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
            ->latest()
            ->get();

        return view(
            'guru.tasks.history',
            compact('tasks')
        );
    }
}