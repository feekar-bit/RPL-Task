<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Task;
use App\Models\SchoolClass;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $totalGuru = User::where('role', 'guru')->count();

        $totalSiswa = User::where('role', 'siswa')->count();

        $totalTask = Task::count();

        $totalKelas = SchoolClass::count();
        $totalKapasitas = SchoolClass::where('status', 'active')->sum('capacity');

        return view('admin.dashboard', compact(
            'totalGuru',
            'totalSiswa',
            'totalTask',
            'totalKelas',
            'totalKapasitas'
        ));
    }
}