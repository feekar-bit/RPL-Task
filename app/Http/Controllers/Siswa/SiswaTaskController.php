<?php

namespace App\Http\Controllers\Siswa;

use App\Models\Task;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;


class SiswaTaskController extends Controller
{
    // daftar tugas siswa
    public function index()
    {
        $studentClass = Auth::user()->class;

        $tasks = Task::where(
        'class_id',
        Auth::user()->class_id
    )
    ->latest()
    ->get();

        return view('siswa.tasks.index', compact('tasks'));
    }

    // detail tugas
    public function show(int $id)
    {
        $task = Task::findOrFail($id);

        return view('siswa.tasks.show', compact('task'));
    }
}