<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Models\SchoolClass;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // ================= LOGIN =================

    public function login()
    {
        return view('auth.login');
    }

    public function loginProcess(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::attempt($credentials)) {

            $user = Auth::user();

            // cek approval guru
            if ($user->role == 'guru' && !$user->is_approved) {

                Auth::logout();

                return back()->with('error', 'Akun guru belum disetujui admin.');
            }

            // redirect role
            if ($user->role == 'admin') {
                return redirect('/admin/dashboard');
            }

            if ($user->role == 'guru') {
                return redirect('/guru/dashboard');
            }

            if ($user->role == 'siswa') {
                return redirect('/siswa/dashboard');
            }
        }

        return back()->with('error', 'Email atau password salah.');
    }

    // ================= LOGOUT =================

    public function logout()
    {
        Auth::logout();

        return redirect('/login');
    }

    // ================= PILIH ROLE =================

    public function chooseRole()
    {
        return view('auth.choose-role');
    }

    // ================= REGISTER SISWA =================

    public function registerSiswa()
    {
        $classes = SchoolClass::where('status', 'active')
            ->withCount('students')
            ->orderByRaw("CASE grade WHEN 'X' THEN 1 WHEN 'XI' THEN 2 WHEN 'XII' THEN 3 ELSE 4 END")
            ->orderBy('rombel', 'asc')
            ->orderBy('name', 'asc')
            ->get();

        return view('auth.register-siswa', compact('classes'));
    }

    public function registerSiswaProcess(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'class_id' => 'nullable',
            'class' => 'nullable',
            'attendance_number' => 'required',
            'password' => 'required|min:6'
        ]);

        $classId = $request->class_id;
        $classInput = $request->class;
        $schoolClass = null;

        if ($classId) {
            $schoolClass = SchoolClass::find($classId);
            $className = $schoolClass ? $schoolClass->name : $classId;
        } elseif ($classInput) {
            if (is_numeric($classInput)) {
                $classId = (int)$classInput;
                $schoolClass = SchoolClass::find($classId);
                $className = $schoolClass ? $schoolClass->name : $classInput;
            } else {
                $schoolClass = SchoolClass::where('name', $classInput)->first();
                $classId = $schoolClass ? $schoolClass->id : null;
                $className = $classInput;
            }
        } else {
            return back()->withErrors(['class_id' => 'Kelas harus dipilih.'])->withInput();
        }

        // Validasi Status & Kuota Kelas
        if ($schoolClass) {
            if ($schoolClass->status !== 'active') {
                return back()->withErrors(['class_id' => "Kelas {$schoolClass->name} sedang tidak aktif."])->withInput();
            }

            if ($schoolClass->is_full) {
                return back()->withErrors([
                    'class_id' => "Kelas {$schoolClass->name} sudah mencapai batas kapasitas maksimal ({$schoolClass->capacity} siswa). Silakan pilih kelas lain atau hubungi admin."
                ])->withInput();
            }
        }

        User::create([
            'name' => $request->name,
            'email' => $request->email,

            'class' => $className,
            'class_id' => $classId,
            'attendance_number' => $request->attendance_number,

            'role' => 'siswa',
            'status' => 'active',

            'is_approved' => true,

            'password' => Hash::make($request->password),
        ]);

        return redirect('/login')
            ->with('success', 'Register siswa berhasil.');
    }

    // ================= REGISTER GURU =================

    public function registerGuru()
    {
        return view('auth.register-guru');
    }

    public function registerGuruProcess(Request $request)
    {
        $request->validate([
            'teacher_id' => 'required',
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6'
        ]);

        User::create([
            'teacher_id' => $request->teacher_id,

            'name' => $request->name,
            'email' => $request->email,

            'role' => 'guru',
            'status' => 'pending',

            'is_approved' => false,

            'password' => Hash::make($request->password),
        ]);

        return redirect('/login')
            ->with('success', 'Register guru berhasil. Tunggu persetujuan admin.');
    }
}