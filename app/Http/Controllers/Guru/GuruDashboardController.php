<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\TaskSubmission;
use Illuminate\Support\Facades\Auth;

class GuruDashboardController extends Controller
{
    public function index()
    {
        $teacherId = Auth::id();

        // total tugas guru
        $totalTask = Task::where(
            'teacher_id',
            $teacherId
        )->count();

        // ambil task id
        $taskIds = Task::where(
            'teacher_id',
            $teacherId
        )->pluck('id');

        // total submission
        $totalSubmission = TaskSubmission::whereIn(
            'task_id',
            $taskIds
        )->count();

        // rata-rata progress
        $averageProgress = TaskSubmission::whereIn(
            'task_id',
            $taskIds
        )->avg('progress');

        // data grafik
        $submissions = TaskSubmission::whereIn(
            'task_id',
            $taskIds
        )
        ->with('student')
        ->latest()
        ->take(10)
        ->get();

        return view('guru.dashboard', compact(
            'totalTask',
            'totalSubmission',
            'averageProgress',
            'submissions'
        ));
    }
}