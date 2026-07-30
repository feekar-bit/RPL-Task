<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use App\Models\TaskSubmission;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    // ================= ADMIN =================

    public function admin()
    {
        $totalGuru = User::where('role', 'guru')->count();

        $guruPending = User::where('role', 'guru')
            ->where('is_approved', false)
            ->count();

        $totalSiswa = User::where('role', 'siswa')->count();

        $totalTugas = Task::count();

        return view('dashboard.admin', compact(
            'totalGuru',
            'guruPending',
            'totalSiswa',
            'totalTugas'
        ));
    }


    // ================= GURU =================

    public function guru()
    {
        $teacherId = Auth::id();

        // tugas guru
        $totalTugas = Task::where('teacher_id', $teacherId)
            ->count();

        // ambil semua task guru
        $taskIds = Task::where('teacher_id', $teacherId)
            ->pluck('id');

        // submission
        $totalSubmission = TaskSubmission::whereIn('task_id', $taskIds)
            ->count();

        // rata progress
        $averageProgress = TaskSubmission::whereIn('task_id', $taskIds)
            ->avg('progress');

        // data chart
        $chartData = TaskSubmission::whereIn('task_id', $taskIds)
            ->with('student')
            ->get();

        return view('dashboard.guru', compact(
            'totalTugas',
            'totalSubmission',
            'averageProgress',
            'chartData'
        ));
    }


    // ================= SISWA =================

    public function siswa()
    {
        $studentId = Auth::id();

        // total tugas kelas siswa
        $studentClass = Auth::user()->class;

        $totalTugas = Task::where('class_target', $studentClass)
            ->count();

        // tugas selesai
        $tugasSelesai = TaskSubmission::where('student_id', $studentId)
            ->where('progress', 100)
            ->count();

        // rata progress
        $averageProgress = TaskSubmission::where('student_id', $studentId)
            ->avg('progress');

        return view('dashboard.siswa', compact(
            'totalTugas',
            'tugasSelesai',
            'averageProgress'
        ));
    }
}