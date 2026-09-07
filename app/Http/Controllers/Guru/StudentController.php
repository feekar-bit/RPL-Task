<?php

namespace App\Http\Controllers\Guru;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class StudentController extends Controller
{
    public function index()
    {
        $students = User::where('role', 'siswa')
            ->latest()
            ->get();

        return view(
            'guru.students.index',
            compact('students')
        );
    }
}