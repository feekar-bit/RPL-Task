<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class GuruApprovalController extends Controller
{
    // list guru pending
    public function index()
    {
        $gurus = User::where('role', 'guru')
            ->where('is_approved', false)
            ->latest()
            ->get();

        return view(
            'admin.guru-approval.index',
            compact('gurus')
        );
    }

    // approve guru
    public function approve($id)
    {
        $guru = User::findOrFail($id);

        $guru->is_approved = true;

        $guru->save();

        return back()->with(
            'success',
            'Guru berhasil di-approve.'
        );
    }
}