<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\SchoolClass;

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
        $classes = SchoolClass::all();

        return view(
            'auth.register-siswa',
            compact('classes')
        );
    }

    public function registerSiswaProcess(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'class_id' => 'required',
            'attendance_number' => 'required',
            'password' => 'required|min:6'
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,

            'class_id' => $request->class_id,
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