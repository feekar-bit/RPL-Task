<?php

namespace App\Http\Controllers\Siswa;

use App\Models\Task;
use App\Models\TaskSubmission;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class TaskSubmissionController extends Controller
{
    // form submit
    public function create(int $taskId)
    {
        $task = Task::findOrFail($taskId);

        $submission = TaskSubmission::where('task_id', $taskId)
            ->where('student_id', Auth::id())
            ->first();

        return view('siswa.submissions.create', compact(
            'task',
            'submission'
        ));
    }

    // submit tugas
    public function store(Request $request, int $taskId)
    {
        $task = Task::findOrFail($taskId);

        $request->validate([
            'submission_note' => 'nullable',
            'submission_link' => 'nullable|url',
            'submission_file' => 'nullable|file|max:4096',
            'progress' => 'required|integer|min:0|max:100',
        ]);

        $file = null;

        // upload file
        if ($request->hasFile('submission_file')) {

            $file = $request->file('submission_file')
                ->store('submission_files', 'public');
        }

        TaskSubmission::updateOrCreate(

            [
                'task_id' => $task->id,
                'student_id' => Auth::id(),
            ],

            [
                'submission_note' => $request->submission_note,

                'submission_link' => $request->submission_link,

                'submission_file' => $file,

                'progress' => $request->progress,
            ]
        );

        return back()
            ->with('success', 'Submission berhasil disimpan.');
    }
}