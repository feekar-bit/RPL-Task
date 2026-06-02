<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\SchoolClass;
use App\Http\Controllers\Controller;

class AdminStudentController extends Controller
{
    public function index()
    {
        // ambil semua kelas
        $classes = SchoolClass::with([
            'students'
        ])->get();

        return view(
            'admin.students.index',
            compact('classes')
        );
    }
}