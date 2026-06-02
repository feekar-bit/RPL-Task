<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Task;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $totalGuru = User::where('role', 'guru')->count();

        $totalSiswa = User::where('role', 'siswa')->count();

        $totalTask = Task::count();

        return view('admin.dashboard', compact(
            'totalGuru',
            'totalSiswa',
            'totalTask'
        ));
    }
}