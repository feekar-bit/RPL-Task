<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TeacherController extends Controller
{
    public function index()
    {
        $teachers = User::where('role', 'guru')
            ->where('is_approved', true)
            ->latest()
            ->get();

        return view(
            'admin.teachers.index',
            compact('teachers')
        );
    }
}