<?php

namespace App\Http\Controllers\Guru;

use App\Models\SchoolClass;
use App\Http\Controllers\Controller;

class StudentController extends Controller
{
    public function index()
    {
        $classes = SchoolClass::with([
            'students'
        ])->get();

        return view(
            'guru.students.index',
            compact('classes')
        );
    }
}