<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Http\Controllers\Controller;

class GuruManagementController extends Controller
{
    // daftar guru pending
    public function pending()
    {
        $gurus = User::where('role', 'guru')
            ->where('is_approved', false)
            ->latest()
            ->get();

        return view('admin.guru.pending', compact('gurus'));
    }

    // approve guru
    public function approve($id)
    {
        $guru = User::findOrFail($id);

        $guru->update([
            'is_approved' => true
        ]);

        return back()->with('success', 'Guru berhasil disetujui.');
    }

    // hapus guru
    public function delete($id)
    {
        $guru = User::findOrFail($id);

        $guru->delete();

        return back()->with('success', 'Guru berhasil dihapus.');
    }
}