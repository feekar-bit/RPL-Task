<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\SchoolClass;
use App\Http\Controllers\Controller;

class AdminStudentController extends Controller
{
    public function index()
    {
        // ambil semua kelas diurutkan per angkatan dan rombel
        $classes = SchoolClass::with([
            'students'
        ])
        ->orderByRaw("CASE grade WHEN 'X' THEN 1 WHEN 'XI' THEN 2 WHEN 'XII' THEN 3 ELSE 4 END")
        ->orderBy('rombel', 'asc')
        ->orderBy('name', 'asc')
        ->get();

        return view(
            'admin.students.index',
            compact('classes')
        );
    }
}