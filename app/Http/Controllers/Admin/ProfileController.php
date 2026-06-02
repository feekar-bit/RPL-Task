<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class ProfileController extends Controller
{
    // halaman profile
    public function index()
    {
        return view('admin.profile.index');
    }

    // update profile
    public function update(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();

        $request->validate([

            'name' => 'required',

            'photo' => 'nullable|image|max:5120',
        ]);

        // upload photo
        if ($request->hasFile('photo')) {

            $photo = $request->file('photo')
                ->store('profile_photos', 'public');

            $user->photo = $photo;
        }

        // update nama
        $user->name = $request->name;

        $user->save();

        return back()
            ->with('success', 'Profile admin berhasil diperbarui.');
    }
}