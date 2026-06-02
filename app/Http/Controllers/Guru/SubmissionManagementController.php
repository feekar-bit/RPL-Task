<?php

namespace App\Http\Controllers\Guru;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\TaskSubmission;
use App\Http\Controllers\Controller;

class SubmissionManagementController extends Controller
{
    // daftar submission berdasarkan tugas
    public function index(int $taskId)
    {
        $task = Task::findOrFail($taskId);

        $submissions = TaskSubmission::where('task_id', $taskId)
            ->latest()
            ->get();

        return view('guru.submissions.index', compact(
            'task',
            'submissions'
        ));
    }

    // form feedback & nilai
    public function edit(int $id)
    {
        $submission = TaskSubmission::findOrFail($id);

        return view('guru.submissions.edit', compact(
            'submission'
        ));
    }

    // update feedback & nilai
    public function update(Request $request, int $id)
    {
        $submission = TaskSubmission::findOrFail($id);

        $request->validate([
            'teacher_feedback' => 'nullable',
            'grade' => 'nullable|integer|min:0|max:100',
        ]);

        $submission->update([

            'teacher_feedback' => $request->teacher_feedback,

            'grade' => $request->grade,
        ]);

        return back()
            ->with('success', 'Feedback berhasil disimpan.');
    }
}