<?php

namespace App\Http\Controllers\Siswa;

use App\Models\Task;
use App\Models\SchoolClass;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class SiswaTaskController extends Controller
{
    // daftar tugas siswa
    public function index()
    {
        $user = Auth::user();

        $classIds = array_filter([
            $user->class_id,
            is_numeric($user->class) ? (int)$user->class : null,
            $user->class ? SchoolClass::where('name', $user->class)->value('id') : null
        ]);

        $tasks = Task::whereIn('class_id', $classIds)
            ->with(['teacher', 'schoolClass'])
            ->latest()
            ->get();

        return view('siswa.tasks.index', compact('tasks'));
    }

    // detail tugas
    public function show(int $id)
    {
        $task = Task::with(['teacher', 'schoolClass'])->findOrFail($id);

        return view('siswa.tasks.show', compact('task'));
    }
}