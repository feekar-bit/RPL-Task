<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\SchoolClass;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        // ambil semua kelas
        $classes = SchoolClass::all();

        // ambil semua siswa
        $query = User::where('role', 'siswa');

        // filter berdasarkan kelas
        if ($request->class_id) {

            $query->where(
                'class_id',
                $request->class_id
            );
        }

        // ambil data siswa
        $students = $query
            ->with('schoolClass')
            ->latest()
            ->get();

        return view(
            'admin.students.index',
            compact('students', 'classes')
        );
    }
}