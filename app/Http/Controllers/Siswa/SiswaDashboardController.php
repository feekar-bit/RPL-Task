<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\TaskSubmission;
use Illuminate\Support\Facades\Auth;

class SiswaDashboardController extends Controller
{
    public function index()
    {
        $studentId = Auth::id();

        // total submission
        $totalSubmission = TaskSubmission::where(
            'student_id',
            $studentId
        )->count();

        // rata-rata progress
        $averageProgress = TaskSubmission::where(
            'student_id',
            $studentId
        )->avg('progress');

        // tugas selesai
        $completedTask = TaskSubmission::where(
            'student_id',
            $studentId
        )
        ->where('progress', 100)
        ->count();

        return view('siswa.dashboard', compact(
            'totalSubmission',
            'averageProgress',
            'completedTask'
        ));
    }
}